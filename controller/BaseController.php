<?php

require("vendor/autoload.php");

use Carbon\Carbon;
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

class BaseController
{

	protected $tpl = null;
	protected $base_models = array('user','auth');
	protected $base_helpers = array('diff');
	protected $models = array();
	protected $helpers = array();
	private $__models_cache = array();
	private $__delegates_cache = array();
	
	function __construct() {
		$this->tpl = new PlotViewTemplate;
		$this->models = array_unique(array_merge($this->base_models, $this->models));
		$this->helpers = array_unique(array_merge($this->base_helpers, $this->helpers));
		$this->loadModels();
		$this->loadHelpers();
		$this->check_daily_log();
	}
	
	function beforeLoad() {
		//Overwrite to do extra stuff right after constructor
		// if ($_SERVER["REQUEST_SCHEME"] != "https") {

		// 	$url = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

		// 	header("Location: " . $url);

		// }
	}

	function __version() {
		return "expand v0.1";
	}

	function index($args = array()) {
		echo $this->__version();
		exit();
	}
	
	public function __dispatch($method, $args = array()) {
		switch (count($args)) {
			case 0:
			return $this->{$method}();
			case 1:
			return $this->{$method}($args[0]);
			case 2:
			return $this->{$method}($args[0], $args[1]);
			case 3:
			return $this->{$method}($args[0], $args[1], $args[2]);
			case 4:
			return $this->{$method}($args[0], $args[1], $args[2], $args[3]);
			default:
			return call_user_func_array(array($this, $method), $args);
		}
		
		debug_backtrace();
	}
	
	protected function loadModels() {
		if(!$this->models || !is_array($this->models) || empty($this->models))
			return;
		foreach ($this->models as $model)
			$this->loadModel($model);
	}
	
	protected function loadHelpers() {
		if(!$this->helpers || !is_array($this->helpers) || empty($this->helpers))
			return;
		foreach ($this->helpers as $helper)
			$this->loadHelper($helper);
	}

	protected function loadHelper($helper_name) {
		_loadHelper($helper_name);
	}
	
	protected function loadModel($model_name) {
		if(!isset($this->__models_cache[$model_name]))		
			$this->__models_cache[$model_name] = _loadClass($model_name, 'model');
		
		$this->{$model_name} = $this->__models_cache[$model_name];
		
		return true;
	}
	
	protected function getDelegate($delegate_name) {
		if(!isset($this->__delegates_cache[$delegate_name])) {
			include_once(APP_PATH . DIR_SEP . 'delegate' .DIR_SEP. 'BaseDelegate.php');
		
			$delegate_obj = _loadClass($delegate_name, 'delegate');
			$delegate_obj->__load_models($this->__models_cache);
			$this->__delegates_cache[$delegate_name] = $delegate_obj;
		}
	
		return $this->__delegates_cache[$delegate_name];
	}
	
	//Overwrite this method to make the class publicly accessible.
	function loginRequired() {
		return true;
	}

	function render($tpl_name) {
		if($tpl_name != '.tpl') {
			$tpl_name .= '.tpl';
		}

		$this->tpl->display(APP_PATH. DIR_SEP . 'template'. DIR_SEP . $tpl_name);
	}
	
	function redirect($url) {
	   header('Location: ' . $url);
	   exit();
	}

	/* Kindly add new code below */
	
	function payment($user_id, $success_url, $failure_url, $price) {
		$paypal = new \PayPal\Rest\ApiContext(
			new \PayPal\Auth\OAuthTokenCredential(
				'AVBlTj8Z6tcYoeeUY7YcunxcHx2_1STeCscOGjTk47PiwExso3wj00XXz8W0PSrC1sR81JfPRk0nZkMO',// ClientID
				'EEYDs9KrXqYJlI3kQn4lcEy4CYL4mdQZrr7dFcq0Y4V7bTpjzpSg2RSeGtflGRILb1dnmIMWBqka3OrU' // ClientSecret
			)
		);

		$payer = new Payer();
		$payer->setPaymentMethod('paypal');

		$amount = new Amount();
		$amount->setCurrency('USD')
			->setTotal($price);

		$transaction = new Transaction();
		$transaction->setAmount($amount)
			->setDescription('Story Premium Membership ('. $price .'/Month)')
			->setInvoiceNumber(uniqid());

		$redirectUrls = new RedirectUrls();
		$redirectUrls->setReturnUrl($success_url.$user_id)
			->setCancelUrl($failure_url.$user_id);

		$payment = new Payment();
		$payment->setIntent('sale')
			->setPayer($payer)
			->setRedirectUrls($redirectUrls)
			->setTransactions([$transaction]);

		try {
			$payment->create($paypal);
		} catch (Exception $e) {
			return false;
		}

		return $payment->getApprovalLink();
	}

	function payment_execution($paymentId, $payerId) {
		$paypal = new \PayPal\Rest\ApiContext(
			new \PayPal\Auth\OAuthTokenCredential(
				'AVBlTj8Z6tcYoeeUY7YcunxcHx2_1STeCscOGjTk47PiwExso3wj00XXz8W0PSrC1sR81JfPRk0nZkMO',// ClientID
				'EEYDs9KrXqYJlI3kQn4lcEy4CYL4mdQZrr7dFcq0Y4V7bTpjzpSg2RSeGtflGRILb1dnmIMWBqka3OrU' // ClientSecret
			)
		);

		$payment = Payment::get($paymentId, $paypal);

		$execute = new PaymentExecution();
		$execute->setPayerId($payerId);

		try {
			$result = $payment->execute($execute, $paypal);
		} catch (Exception $e) {
			return false;
		}
		return $result;
	}

	function facebook_object() {
		$facebook = new Facebook\Facebook([
			'app_id' => '266003227543374', // Replace {app-id} with your app id
			'app_secret' => 'dd31c3a73219bb10df842fa50e599bbf',
			'default_graph_version' => 'v2.9',
		]);
		return $facebook;
	}

	/*	Revised code as on 07/06/2018	*/

	function email_verification($email)
	{
		$url = "http://apilayer.net/api/check?access_key=" . MAIL_BOX_API . "&email=" . $email . "&format=1";

		$check = json_decode(file_get_contents($url));

		if($check->smtp_check)
			return true;

		return false;

	}

	function send_activation_mail($to, $to_name, $md5)
	{

		$email = new \SendGrid\Mail\Mail();

		$email->setFrom("donotreply@fableus.com", "Fableus");

		$email->setSubject("Fableus: Account Activation");

		$email->addTo($to, $to_name);
	    
	    $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Story</title><meta name="viewport" content="width=device-width, initial-scale=1.0"/><style type="text/css">td{font-size: 16px;color: #807d7d;} a {color: #529ecc;} .button a {color: white;}</style></head><body style="margin: 0; padding: 0;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td><table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: #f5f5f561;"><tr><td align="center" style="height:150px"><img src="'.BASE_URL.'/resource/img/elements/book-icons.png" alt="story-logo" width="300" style="display: block;" /></td></tr><tr><td style="padding: 40px 30px 40px 30px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 20px;">Thank You! For registering with us...</td></tr><tr><td style="padding: 20px 10px 20px 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td bgcolor="#e5f9ff" style="padding: 25px 25px 25px 25px; border-radius: 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td>&nbsp;</td></tr><tr><td>Dear '.$to_name.',</td></tr><tr><td>&nbsp;</td>	</tr><tr><td>Kindly click on the link below to activate your story account.</td></tr><tr><td>&nbsp;</td></tr><tr><td><table cellpadding="0" cellspacing="0" width="100%"><tr><td style="font-size: 0; line-height: 0;" width="100">&nbsp;</td><td valign="center" align="center" style="background-color:#529ecc;border-color:#529ecc;color:white;padding:5px 18px;border-radius:8px;font-family:\'ProximaNovaRg\';" class="button"><a href="'.BASE_URL.'/auth/activate_user?email='.$to.'&key='.$md5.'">Activate Account.</a></td><td style="font-size: 0; line-height: 0;" width="100">&nbsp;</td></tr></table></td></tr></table></td></tr></table></td></tr></table></td></tr><tr><td align="center" style="padding: 15px 10px 15px 10px;">Copyright © Fableus.com 2018. All Rights Reserved</td></tr></table></td></tr></table></body></html>';

        $email->addContent("text/html", $message);

		$sendgrid = new \SendGrid(SENDGRID_API_KEY);

		try {
		
			$response = $sendgrid->send($email);
		
		} catch (Exception $e) {
		
			echo 'Caught exception: '. $e->getMessage() ."\n";
		
		}

		return $response;

    }

    protected function getSessionUser()
    {
		
		if (isset($_SESSION["user"]) && !empty($_SESSION["user"]))
			return $_SESSION["user"];

		return false;
	
	}

	function send_invite_mail($title, $from, $from_name, $to, $to_name)
	{

		$email = new \SendGrid\Mail\Mail();

		$email->setFrom("donotreply@fableus.com", "Fableus");

		$email->setSubject("Fableus: Story Contribution Invite");

		$email->addTo($to, $to_name);

		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Story</title><meta name="viewport" content="width=device-width, initial-scale=1.0"/><style type="text/css">td{font-size: 16px;color: #807d7d;} a {color: #529ecc;} .button a {color: white;}</style></head><body style="margin: 0; padding: 0;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td><table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: #f5f5f561;"><tr><td align="center" style="height:150px"><img src="'.BASE_URL.'/resource/img/elements/book-icons.png" alt="story-logo" width="300" style="display: block;" /></td></tr><tr><td style="padding: 40px 30px 40px 30px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 20px;">You have been Invited to Contribute for the story...</td></tr><tr><td style="padding: 20px 10px 20px 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td bgcolor="#e5f9ff" style="padding: 25px 25px 25px 25px; border-radius: 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 30px;"><a style="color: #529ecc; text-transform: capitalize;">'.$title.'</a></td></tr><tr><td>&nbsp;</td></tr><tr><td>Dear '.$to_name.',</td></tr><tr><td>&nbsp;</td>	</tr><tr><td>'.$from_name.' has invited you to contribute to his/her story. Play an important role helping another user and completing the article.</td></tr><tr><td><hr></td></tr><tr><td align="center">Respond to invite ?</td></tr><tr><td>&nbsp;</td></tr><tr><td><table cellpadding="0" cellspacing="0" width="100%"><tr><td style="font-size: 0; line-height: 0;" width="100">&nbsp;</td><td valign="top" align="center" style="background-color:#529ecc;border-color:#529ecc;color:white;padding:5px 18px;border-radius:8px;font-family:\'ProximaNovaRg\';" class="button"><a href="'.BASE_URL.'/stories/collabs" style="color:white">Accept</a></td><td style="font-size: 0; line-height: 0;" width="50">&nbsp;</td><td valign="top" align="center" style="background-color: #529ecc; border-color: #529ecc; color:  white; padding:  5px 18px; border-radius: 8px; font-family: \'ProximaNovaRg\';" class="button"><a href="'.BASE_URL.'/stories/collabs">Decline</a></td><td style="font-size: 0; line-height: 0;" width="100">&nbsp;</td></tr></table></td></tr></table></td></tr></table></td></tr><tr><td align="center" style="padding: 15px 10px 15px 10px;">Copyright © Fableus.com 2018. All Rights Reserved</td></tr></table></td></tr></table></body></html>';

		$email->addContent("text/html", $message);

		$sendgrid = new \SendGrid(SENDGRID_API_KEY);

		try {
		
			$response = $sendgrid->send($email);
		
		} catch (Exception $e) {
		
			echo 'Caught exception: '. $e->getMessage() ."\n";
		
		}

		return $response;
	
	}

	function send_invite_read_mail($title, $from, $from_name, $to, $to_name)
	{

		$email = new \SendGrid\Mail\Mail();

		$email->setFrom("donotreply@fableus.com", "Fableus");

		$email->setSubject("Fableus: Story Read Contribution Invite");

		$email->addTo($to, $to_name);

		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Story</title><meta name="viewport" content="width=device-width, initial-scale=1.0"/><style type="text/css">td{font-size: 16px;color: #807d7d;} a {color: #529ecc;} .button a {color: white;}</style></head><body style="margin: 0; padding: 0;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td><table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: #f5f5f561;"><tr><td align="center" style="height:150px"><img src="'.BASE_URL.'/resource/img/elements/book-icons.png" alt="story-logo" width="300" style="display: block;" /></td></tr><tr><td style="padding: 40px 30px 40px 30px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 20px;">You have been Invited to read the story...</td></tr><tr><td style="padding: 20px 10px 20px 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td bgcolor="#e5f9ff" style="padding: 25px 25px 25px 25px; border-radius: 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 30px;"><a style="color: #529ecc; text-transform: capitalize;">'.$title.'</a></td></tr><tr><td>&nbsp;</td></tr><tr><td>Dear '.$to_name.',</td></tr><tr><td>&nbsp;</td>	</tr><tr><td>'.$from_name.' has invited you to read to his/her story. Play an important role helping another user and completing the article.</td></tr><tr><td><hr></td></tr><tr><td align="center">Respond to invite ?</td></tr><tr><td>&nbsp;</td></tr><tr><td><table cellpadding="0" cellspacing="0" width="100%"><tr><td style="font-size: 0; line-height: 0;" width="100">&nbsp;</td><td valign="top" align="center" style="background-color:#529ecc;border-color:#529ecc;color:white;padding:5px 18px;border-radius:8px;font-family:\'ProximaNovaRg\';" class="button"><a href="'.BASE_URL.'/stories/collabs" style="color:white">Accept</a></td><td style="font-size: 0; line-height: 0;" width="50">&nbsp;</td><td valign="top" align="center" style="background-color: #529ecc; border-color: #529ecc; color:  white; padding:  5px 18px; border-radius: 8px; font-family: \'ProximaNovaRg\';" class="button"><a href="'.BASE_URL.'/stories/collabs">Decline</a></td><td style="font-size: 0; line-height: 0;" width="100">&nbsp;</td></tr></table></td></tr></table></td></tr></table></td></tr><tr><td align="center" style="padding: 15px 10px 15px 10px;">Copyright © Fableus.com 2018. All Rights Reserved</td></tr></table></td></tr></table></body></html>';

		$email->addContent("text/html", $message);

		$sendgrid = new \SendGrid(SENDGRID_API_KEY);

		try {
		
			$response = $sendgrid->send($email);
		
		} catch (Exception $e) {
		
			echo 'Caught exception: '. $e->getMessage() ."\n";
		
		}

		return $response;
	
	}

	function invite_read_accept_mail($title, $from_name, $to, $to_name)
	{

		$email = new \SendGrid\Mail\Mail();

		$email->setFrom("donotreply@fableus.com", "Fableus");

		$email->setSubject("Fableus: Story Read Contribution Invite Accepted");

		$email->addTo($to, $to_name);

		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Story</title><meta name="viewport" content="width=device-width, initial-scale=1.0"/><style type="text/css">td{font-size: 16px;color: #807d7d;} a {color: #529ecc;} .button a {color: white;}</style></head><body style="margin: 0; padding: 0;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td><table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: #f5f5f561;"><tr><td align="center" style="height:150px"><img src="'.BASE_URL.'/resource/img/elements/book-icons.png" alt="story-logo" width="300" style="display: block;" /></td></tr><tr><td style="padding: 40px 30px 40px 30px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 20px;">Invite accepted</td></tr><tr><td style="padding: 20px 10px 20px 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td bgcolor="#e5f9ff" style="padding: 25px 25px 25px 25px; border-radius: 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 30px;"><a style="color: #529ecc; text-transform: capitalize;">'.$title.'</a></td></tr><tr><td>&nbsp;</td></tr><tr><td>Dear '.$to_name.',</td></tr><tr><td>&nbsp;</td>	</tr><tr><td>'.$from_name.' has accepted your invite to story.</td></tr><tr><td><hr></td></tr></table></td></tr></table></td></tr><tr><td align="center" style="padding: 15px 10px 15px 10px;">Copyright © Fableus.com 2018. All Rights Reserved</td></tr></table></td></tr></table></body></html>';

		$email->addContent("text/html", $message);

		$sendgrid = new \SendGrid(SENDGRID_API_KEY);

		try {
		
			$response = $sendgrid->send($email);
		
		} catch (Exception $e) {
		
			echo 'Caught exception: '. $e->getMessage() ."\n";
		
		}

		return $response;
	
	}

	function invite_read_decline_mail($title, $from_name, $to, $to_name)
	{

		$email = new \SendGrid\Mail\Mail();

		$email->setFrom("donotreply@fableus.com", "Fableus");

		$email->setSubject("Fableus: Story Read Contribution Invite Declined");

		$email->addTo($to, $to_name);

		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Story</title><meta name="viewport" content="width=device-width, initial-scale=1.0"/><style type="text/css">td{font-size: 16px;color: #807d7d;} a {color: #529ecc;} .button a {color: white;}</style></head><body style="margin: 0; padding: 0;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td><table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: #f5f5f561;"><tr><td align="center" style="height:150px"><img src="'.BASE_URL.'/resource/img/elements/book-icons.png" alt="story-logo" width="300" style="display: block;" /></td></tr><tr><td style="padding: 40px 30px 40px 30px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 20px;">Invite declined</td></tr><tr><td style="padding: 20px 10px 20px 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td bgcolor="#e5f9ff" style="padding: 25px 25px 25px 25px; border-radius: 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 30px;"><a style="color: #529ecc; text-transform: capitalize;">'.$title.'</a></td></tr><tr><td>&nbsp;</td></tr><tr><td>Dear '.$to_name.',</td></tr><tr><td>&nbsp;</td>	</tr><tr><td>'.$from_name.' has declined your invite to story.</td></tr><tr><td><hr></td></tr></table></td></tr></table></td></tr><tr><td align="center" style="padding: 15px 10px 15px 10px;">Copyright © Fableus.com 2018. All Rights Reserved</td></tr></table></td></tr></table></body></html>';

		$email->addContent("text/html", $message);

		$sendgrid = new \SendGrid(SENDGRID_API_KEY);

		try {
		
			$response = $sendgrid->send($email);
		
		} catch (Exception $e) {
		
			echo 'Caught exception: '. $e->getMessage() ."\n";
		
		}

		return $response;
	
	}

	function invite_accept_mail($title, $from_name, $to, $to_name)
	{

		$email = new \SendGrid\Mail\Mail();

		$email->setFrom("donotreply@fableus.com", "Fableus");

		$email->setSubject("Fableus: Story Contribution Invite Accepted");

		$email->addTo($to, $to_name);

		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Story</title><meta name="viewport" content="width=device-width, initial-scale=1.0"/><style type="text/css">td{font-size: 16px;color: #807d7d;} a {color: #529ecc;} .button a {color: white;}</style></head><body style="margin: 0; padding: 0;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td><table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: #f5f5f561;"><tr><td align="center" style="height:150px"><img src="'.BASE_URL.'/resource/img/elements/book-icons.png" alt="story-logo" width="300" style="display: block;" /></td></tr><tr><td style="padding: 40px 30px 40px 30px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 20px;">Invite accepted</td></tr><tr><td style="padding: 20px 10px 20px 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td bgcolor="#e5f9ff" style="padding: 25px 25px 25px 25px; border-radius: 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 30px;"><a style="color: #529ecc; text-transform: capitalize;">'.$title.'</a></td></tr><tr><td>&nbsp;</td></tr><tr><td>Dear '.$to_name.',</td></tr><tr><td>&nbsp;</td>	</tr><tr><td>'.$from_name.' has accepted your invite to story.</td></tr><tr><td><hr></td></tr></table></td></tr></table></td></tr><tr><td align="center" style="padding: 15px 10px 15px 10px;">Copyright © Fableus.com 2018. All Rights Reserved</td></tr></table></td></tr></table></body></html>';

		$email->addContent("text/html", $message);

		$sendgrid = new \SendGrid(SENDGRID_API_KEY);

		try {
		
			$response = $sendgrid->send($email);
		
		} catch (Exception $e) {
		
			echo 'Caught exception: '. $e->getMessage() ."\n";
		
		}

		return $response;
	
	}

	function invite_decline_mail($title, $from_name, $to, $to_name)
	{

		$email = new \SendGrid\Mail\Mail();

		$email->setFrom("donotreply@fableus.com", "Fableus");

		$email->setSubject("Fableus: Story Contribution Invite Declined");

		$email->addTo($to, $to_name);

		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Story</title><meta name="viewport" content="width=device-width, initial-scale=1.0"/><style type="text/css">td{font-size: 16px;color: #807d7d;} a {color: #529ecc;} .button a {color: white;}</style></head><body style="margin: 0; padding: 0;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td><table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: #f5f5f561;"><tr><td align="center" style="height:150px"><img src="'.BASE_URL.'/resource/img/elements/book-icons.png" alt="story-logo" width="300" style="display: block;" /></td></tr><tr><td style="padding: 40px 30px 40px 30px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 20px;">Invite declined</td></tr><tr><td style="padding: 20px 10px 20px 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td bgcolor="#e5f9ff" style="padding: 25px 25px 25px 25px; border-radius: 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 30px;"><a style="color: #529ecc; text-transform: capitalize;">'.$title.'</a></td></tr><tr><td>&nbsp;</td></tr><tr><td>Dear '.$to_name.',</td></tr><tr><td>&nbsp;</td>	</tr><tr><td>'.$from_name.' has declined your invite to story.</td></tr><tr><td><hr></td></tr></table></td></tr></table></td></tr><tr><td align="center" style="padding: 15px 10px 15px 10px;">Copyright © Fableus.com 2018. All Rights Reserved</td></tr></table></td></tr></table></body></html>';

		$email->addContent("text/html", $message);

		$sendgrid = new \SendGrid(SENDGRID_API_KEY);

		try {
		
			$response = $sendgrid->send($email);
		
		} catch (Exception $e) {
		
			echo 'Caught exception: '. $e->getMessage() ."\n";
		
		}

		return $response;
	
	}

	function apply_accept_mail($title, $from_name, $to, $to_name)
	{
		$email = new \SendGrid\Mail\Mail();

		$email->setFrom("donotreply@fableus.com", "Fableus");

		$email->setSubject("Fableus: Story Contribution Request Accepted");

		$email->addTo($to, $to_name);

		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Story</title><meta name="viewport" content="width=device-width, initial-scale=1.0"/><style type="text/css">td{font-size: 16px;color: #807d7d;} a {color: #529ecc;} .button a {color: white;}</style></head><body style="margin: 0; padding: 0;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td><table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: #f5f5f561;"><tr><td align="center" style="height:150px"><img src="'.BASE_URL.'/resource/img/elements/book-icons.png" alt="story-logo" width="300" style="display: block;" /></td></tr><tr><td style="padding: 40px 30px 40px 30px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 20px;">Apply for Contribution accepted</td></tr><tr><td style="padding: 20px 10px 20px 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td bgcolor="#e5f9ff" style="padding: 25px 25px 25px 25px; border-radius: 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 30px;"><a style="color: #529ecc; text-transform: capitalize;">'.$title.'</a></td></tr><tr><td>&nbsp;</td></tr><tr><td>Dear '.$to_name.',</td></tr><tr><td>&nbsp;</td>	</tr><tr><td>'.$from_name.' has accepted your request to contribute to story.</td></tr><tr><td><hr></td></tr></table></td></tr></table></td></tr><tr><td align="center" style="padding: 15px 10px 15px 10px;">Copyright © Fableus.com 2018. All Rights Reserved</td></tr></table></td></tr></table></body></html>';

		$email->addContent("text/html", $message);

		$sendgrid = new \SendGrid(SENDGRID_API_KEY);

		try {
		
			$response = $sendgrid->send($email);
		
		} catch (Exception $e) {
		
			echo 'Caught exception: '. $e->getMessage() ."\n";
		
		}

		return $response;
	
	}

	function apply_decline_mail($title, $from_name, $to, $to_name)
	{
		$email = new \SendGrid\Mail\Mail();

		$email->setFrom("donotreply@fableus.com", "Fableus");

		$email->setSubject("Fableus: Story Contribution Request Declined");

		$email->addTo($to, $to_name);

		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Story</title><meta name="viewport" content="width=device-width, initial-scale=1.0"/><style type="text/css">td{font-size: 16px;color: #807d7d;} a {color: #529ecc;} .button a {color: white;}</style></head><body style="margin: 0; padding: 0;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td><table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: #f5f5f561;"><tr><td align="center" style="height:150px"><img src="'.BASE_URL.'/resource/img/elements/book-icons.png" alt="story-logo" width="300" style="display: block;" /></td></tr><tr><td style="padding: 40px 30px 40px 30px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 20px;">Apply for Contribution  declined</td></tr><tr><td style="padding: 20px 10px 20px 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td bgcolor="#e5f9ff" style="padding: 25px 25px 25px 25px; border-radius: 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 30px;"><a style="color: #529ecc; text-transform: capitalize;">'.$title.'</a></td></tr><tr><td>&nbsp;</td></tr><tr><td>Dear '.$to_name.',</td></tr><tr><td>&nbsp;</td>	</tr><tr><td>'.$from_name.' has declined your request to contribute to story.</td></tr><tr><td><hr></td></tr></table></td></tr></table></td></tr><tr><td align="center" style="padding: 15px 10px 15px 10px;">Copyright © Fableus.com 2018. All Rights Reserved</td></tr></table></td></tr></table></body></html>';

		$email->addContent("text/html", $message);

		$sendgrid = new \SendGrid(SENDGRID_API_KEY);

		try {
		
			$response = $sendgrid->send($email);
		
		} catch (Exception $e) {
		
			echo 'Caught exception: '. $e->getMessage() ."\n";
		
		}

		return $response;
	
	}

	function send_invite_unregistered_user($from, $from_name, $to)
	{

		$email = new \SendGrid\Mail\Mail();

		$email->setFrom("donotreply@fableus.com", "Fableus");

		$email->setSubject("Fableus: Registration Invite");

		$email->addTo($to, strstr($to, '@', true));

        $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Story</title><meta name="viewport" content="width=device-width, initial-scale=1.0"/><style type="text/css">td{font-size: 16px;color: #807d7d;} a {color: #529ecc;} .button a {color: white;}</style></head><body style="margin: 0; padding: 0;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td><table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: #f5f5f561;"><tr><td align="center" style="height:150px"><img src="'.BASE_URL.'/resource/img/elements/book-icons.png" alt="story-logo" width="300" style="display: block;" /></td></tr><tr><td style="padding: 40px 30px 40px 30px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 20px;">You have been invited to register with us...</td></tr><tr><td style="padding: 20px 10px 20px 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td bgcolor="#e5f9ff" style="padding: 25px 25px 25px 25px; border-radius: 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td>&nbsp;</td></tr><tr><td>Dear '. strstr($to, '@', true) .',</td></tr><tr><td>&nbsp;</td>	</tr><tr><td>You have been invited to register for fableus and can also contribute to other stories.</td></tr><tr><td><hr></td></tr><tr><td><table cellpadding="0" cellspacing="0" width="100%"><tr><td style="font-size: 0; background-color: #e5f9ff; line-height: 0;" width="150">&nbsp;</td><td valign="top" align="center" style="background-color: #529ecc; border-color: #529ecc; color:  white; padding:  5px 18px; border-radius: 8px; font-family: \'ProximaNovaRg\';" class="button"><a href="'.BASE_URL.'">Register with Us</a></td><td style="font-size: 0; line-height: 0;" width="150">&nbsp;</td></table></td></tr></table></td></tr></table></td></tr><tr><td align="center" style="padding: 15px 10px 15px 10px;">Copyright © Fableus.com 2018. All Rights Reserved</td></tr></table></td></tr></table></body></html>';


        $email->addContent("text/html", $message);

		$sendgrid = new \SendGrid(SENDGRID_API_KEY);

		try {
		
			$response = $sendgrid->send($email);
		
		} catch (Exception $e) {
		
			echo 'Caught exception: '. $e->getMessage() ."\n";
		
		}

		return $response;
	
	}

	function get_story_type($type_string)
	{

		if ($type_string == "Novel") {
		
			return NOVEL;
		
		} elseif ($type_string == "Screenplay") {
		
			return SCREENPLAY;
		
		} elseif ($type_string == "Short Story") {
		
			return SHORT_STORY;
		
		} elseif ($type_string == "Story") {
		
			return STORY;
		
		} else {
		
			return UNDEFINED;
		
		}
	
	}

	function send_apply_mail($title, $from, $from_name, $to, $to_name)
	{

		$email = new \SendGrid\Mail\Mail();

		$email->setFrom("donotreply@fableus.com", "Fableus");

		$email->setSubject("Fableus: Story Contribution Request");

		$email->addTo($to, $to_name);

		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Story</title><meta name="viewport" content="width=device-width, initial-scale=1.0"/><style type="text/css">td{font-size: 16px;color: #807d7d;} a {color: #529ecc;} .button a {color: white;}</style></head><body style="margin: 0; padding: 0;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td><table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: #f5f5f561;"><tr><td align="center" style="height:150px"><img src="'.BASE_URL.'/resource/img/elements/book-icons.png" alt="story-logo" width="300" style="display: block;" /></td></tr><tr><td style="padding: 40px 30px 40px 30px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 20px;">You have contribution request for your story...</td></tr><tr><td style="padding: 20px 10px 20px 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td bgcolor="#e5f9ff" style="padding: 25px 25px 25px 25px; border-radius: 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 30px;"><a href="'.BASE_URL.'" style="color: #529ecc; text-transform: capitalize;">'.$title.'</a></td></tr><tr><td>&nbsp;</td></tr><tr><td>Hello '.$to_name.',</td></tr><tr><td>&nbsp;</td>	</tr><tr><td>'.$from_name.' has requested to contribute for your story.</td></tr><tr><td><hr></td></tr><tr><td align="center">Respond to request ?</td></tr><tr><td>&nbsp;</td></tr><tr><td><table cellpadding="0" cellspacing="0" width="100%"><tr><td style="font-size: 0; line-height: 0;" width="100">&nbsp;</td><td valign="top" align="center" style="background-color: #529ecc; border-color: #529ecc; color:  white; padding:  5px 18px; border-radius: 8px; font-family: \'ProximaNovaRg\';" class="button"><a href="'.BASE_URL.'/stories/collabs">Accept</a></td><td style="font-size: 0; line-height: 0;" width="50">&nbsp;</td><td valign="top" align="center" style="background-color:#529ecc;border-color:#529ecc;color:white;padding:5px 18px;border-radius:8px;font-family:\'ProximaNovaRg\';" class="button"><a href="'.BASE_URL.'/stories/collabs" style="color:white">Decline</a></td><td style="font-size: 0; line-height: 0;" width="100">&nbsp;</td></tr></table></td></tr></table></td></tr></table></td></tr></table></td></tr><tr><td align="center" style="padding: 15px 10px 15px 10px;">Copyright © Fableus.com 2018. All Rights Reserved</td></tr></table></td></tr></table></body></html>';

		$email->addContent("text/html", $message);

		$sendgrid = new \SendGrid(SENDGRID_API_KEY);

		try {
		
			$response = $sendgrid->send($email);
		
		} catch (Exception $e) {
		
			echo 'Caught exception: '. $e->getMessage() ."\n";
		
		}

		return $response;
	
	}
	
	
	function send_feedback_mail($name, $subject, $email, $contact_message)
	{

		$email = new \SendGrid\Mail\Mail();

		$email->setFrom("donotreply@fableus.com", "Fableus");

		$email->setSubject("Feedback: $subject");

		$email->addTo("armaan1506@gmail.com", "Armaan");

		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Story</title><meta name="viewport" content="width=device-width, initial-scale=1.0"/><style type="text/css">td{font-size: 16px;color: #807d7d;}a {color: #529ecc;}.button a {color: white;}</style></head><body style="margin: 0; padding: 0;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td><table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: #f5f5f561;"><tr><td align="center" style="height:150px"><img src="'.BASE_URL.'/resource/img/elements/book-icons.png" alt="story-logo" width="300" style="display: block;" /></td></tr><tr><td style="padding: 40px 30px 40px 30px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 20px;">You have recieved feedback on Fableus</td></tr><tr><td style="padding: 20px 10px 20px 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td bgcolor="#e5f9ff" style="padding: 25px 25px 25px 25px; border-radius: 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td>&nbsp;</td></tr><tr><td>Hello Admin,</td></tr><tr><td>&nbsp;</td></tr><tr><td><b>'.$name.'</b> has given some feedback for fableus.</td></tr><tr><td>&nbsp;</td></tr><tr><td><b>Email:</b> '.$email.'</td></tr><tr><td>&nbsp;</td></tr><tr><td><b>Message:</b> '.$contact_message.'</td></tr><tr><td>&nbsp;</td></tr></table></td></tr></table></td></tr></table></td></tr><tr><td align="center" style="padding: 15px 10px 15px 10px;">Copyright © Fableus.com 2018. All Rights Reserved</td></tr></table></td></tr></table></body></html>';

		$email->addContent("text/html", $message);

		$sendgrid = new \SendGrid(SENDGRID_API_KEY);

		try {
		
			$response = $sendgrid->send($email);
		
		} catch (Exception $e) {
		
			echo 'Caught exception: '. $e->getMessage() ."\n";
		
		}

		return $response;
	
	}

	function check_daily_log() {

		$l_name = 'logs/' . Carbon::now()->subDay()->toDateString() . '-error-log';

		if (file_exists($l_name)) {
		
			$l_file = fopen($l_name, "a");
		
		} else {
		
			$l_file = fopen($l_name, "w");
			
		}

		$e_file = fopen("error_log", "r+");

		@fwrite($l_file, fread($e_file, filesize("error_log")));

		ftruncate($e_file, 0);

		fclose($e_file);

		fclose($l_file);

	}

}
