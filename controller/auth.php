<?php

use Carbon\Carbon;
use Abraham\TwitterOAuth\TwitterOAuth;

class AuthController extends BaseController
{
	protected $models = array('stories', 'story');

	function payment_success($user_id) {
		$user_dlgt = $this->getDelegate('user');
		$user = $user_dlgt->get_user($user_id);

		if(isset($_GET['paymentId']) && isset($_GET['token']) && isset($_GET['PayerID']) && $user){
			$payment_id = $_GET['paymentId'];
			$payer_id = $_GET['PayerID'];
			$token = $_GET['token'];
			
			if($this->payment_execution($payment_id, $payer_id)){
				$auth_dlgt = $this->getDelegate("auth");
				
				$pay_id = $auth_dlgt->add_payment($user["user_id"], $payer_id, $payment_id, $token);
				$update_plan = $auth_dlgt->update_plan($user['user_id']);
				
				$payment = $auth_dlgt->get_payment($payment_id);
				$user = $user_dlgt->get_user($user["user_id"]);
				
				$this->tpl->assign('payment', $payment);
				$this->tpl->assign('user', $user); 
				$this->render('payment_success');
			} else {
				$this->render("payment_failure");
			}
		} else {
			$this->redirect("/");
		}
	}

	function payment_failure($user_id) {
		$this->render("payment_failure");
	}

	function facebook_callback() {
		$facebook = $this->facebook_object();
		$helper = $facebook->getRedirectLoginHelper();

		try {
			if(isset($_SESSION['facebook_access_token'])) {
				$accessToken = $_SESSION['facebook_access_token'];
			} else {
				$accessToken = $helper->getAccessToken();
			}
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		if(isset($accessToken)) {
			if(isset($_SESSION['facebook_access_token'])) {
				$facebook->setDefaultAccessToken($_SESSION['facebook_access_token']);
			} else {
				$_SESSION['facebook_access_token'] = (string) $accessToken;
				$oAuth2Client = $facebook->getOAuth2Client();
				$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
				$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
				$facebook->setDefaultAccessToken($_SESSION['facebook_access_token']);
			}
			
			try {
				$profile_request = $facebook->get('/me?fields=name,first_name,last_name,email');
				$profile = $profile_request->getGraphNode()->asArray();
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				echo 'Graph returned an error: ' . $e->getMessage();
				session_destroy();
				header("Location: ./");
				exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
			}
			
			$auth_dlgt = $this->getDelegate("auth");
			if ($user = $auth_dlgt->facebook_login($profile)) {
				$_SESSION["user"] = $user;
				$this->redirect("/stories");
			}
		}
		$this->redirect('/');
	}

	/*	Revised Code as on 07-06-2018	*/

	function index($args = array())
	{
	
		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate('story');
		
		$stories_dlgt = $this->getDelegate('stories');

		$filter = $_GET["filter"] ? trim($_GET["filter"]) : null;

		$types = $story_dlgt->get_story_types();

		$stories = $stories_dlgt->get_all_stories($user["user_id"], $filter);
		

		foreach ($stories as &$story) {
		
			$story["stats"] = $story_dlgt->story_stats($story["id"]);
		
			$story["user_role"] = $story_dlgt->get_user_role($story["id"], $user["user_id"]);
		
			$story["50_words"] = trim($story_dlgt->get_story($story["id"])["def_chapter"]["50_words"]);
		
			$last_story_id = $story["id"];

		}

		$other_stories = $stories_dlgt->get_other_stories($user["user_id"]);

		$now = Carbon::now();

		$best_story_check = $story_dlgt->check_best_story_of_month($now->format('m'), $now->format('Y'));

		if($best_story_check) {

			$best_story = $story_dlgt->best_stories($best_story_check['id']);

			$this->tpl->assign("best_story", $best_story[0]);
		
		}

		$this->tpl->assign("user", $user);
		
		$this->tpl->assign("stories", $stories);
		
		$this->tpl->assign("filter", $filter);

		$this->tpl->assign("types", $types);
		
		$this->tpl->assign("other_stories", $other_stories);
		
		$this->tpl->assign("last_story_id", $last_story_id);
		
		$this->render('home_page');
	
	}

	function register()
	{
		
		$auth_dlgt = $this->getDelegate("auth");
		
		$user_dlgt = $this->getDelegate("user");

		if (isset($_POST["rg_name"], $_POST["rg_email"], $_POST["rg_pass"], $_POST["rg_pass_confirm"])) {

			$name = trim($_POST["rg_name"]);
			
			$email = trim($_POST["rg_email"]);
			
			$pass = trim($_POST["rg_pass"]);
			
			$pass_confirm = trim($_POST["rg_pass_confirm"]);

			if (empty($email)) {

				$response["status"] = "Failure";

				$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Email field cannot be empty.</div>';

				echo json_encode($response);

				return;

			}

			if (!$this->email_verification($email)) {

				$response["status"] = "Failure";

				$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Email does not exist. Please enter valid email.</div>';

				echo json_encode($response);

				return;

			}

			if ($auth_dlgt->check_user($email)) {
				
				$response["status"] = "Failure";
				
				$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> User with the email already exists.</div>';
				
				echo json_encode($response);
				
				return;
			
			}

			if (empty($name)) {

				$response["status"] = "Failure";

				$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Name cannot be empty.</div>';

				echo json_encode($response);

				return;

			}

			if (empty($pass)) {
				
				$response["status"] = "Failure";
                
                $response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Password cannot be empty.</div>';
                
                echo json_encode($response);
                
                return;
            
            }

			if ($pass != $pass_confirm) {
				
				$response["status"] = "Failure";
				
				$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Passwords do not match.</div>';
				
				echo json_encode($response);
				
				return;

			}

			$user_id = $auth_dlgt->register_user($name, $email, $pass);

			if ($user = $user_dlgt->get_user_universal($user_id)) {

				$this->send_activation_mail($user['email'], $user['name'], $user['password']);

				$response["status"] = "Success";
				
				$response["data"] = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><span style="color: forestgreen;">Success!</span></strong> Kindly check your mailbox and activate your account.</div>';
				
				echo json_encode($response);
				
				return;
			
			}

		}

		$response["status"] = "Failure";

		$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Something went wrong. Kindly try again.</div>';

		echo json_encode($response);

		return;
	
	}

	function facebook()
	{
		
		$facebook = $this->facebook_object();
		
		$helper = $facebook->getRedirectLoginHelper();
		
		$permissions = ['email'];
		
		$callback_url = BASE_URL.'/auth/facebook_callback';
		
		$loginUrl = $helper->getLoginUrl($callback_url, $permissions);

		$this->redirect($loginUrl);
	}

	function twitter()
	{
		
		if(!isset($_SESSION['access_token'])) {

			$callback_url = BASE_URL."/auth/twitter_callback";
			
			$connecton = new TwitterOAuth(TWITTER_KEY, TWITTER_SECRET);
			
			$request_token = $connecton->oauth('oauth/request_token', array('oauth_callback' => $callback_url));
			
			$_SESSION['oauth_token'] = $request_token['oauth_token'];
			
			$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
			
			$url = $connecton->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
			
			$this->redirect($url);
		
		} else {
			
			$access_token = $_SESSION['access_token'];
			
			$connection = new TwitterOAuth(TWITTER_KEY, TWITTER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

			$profile = $connection->get("account/verify_credentials");

			$auth_dlgt = $this->getDelegate("auth");
			
			if ($user_id = $auth_dlgt->twitter_login($profile)) {

				$user_dlgt = $this->getDelegate("user");

				$user = $user_dlgt->get_user($user_id);
				
				$_SESSION["user"] = $user;
				
				$this->redirect("/stories");
			
			}
		
		}

		$this->redirect("/");
	
	}

	function twitter_callback()
	{

		if(isset($_REQUEST['oauth_verifier'], $_REQUEST['oauth_token']) && $_REQUEST['oauth_token'] == $_SESSION['oauth_token']) {
		
			$request_token = [];
		
			$request_token['oauth_token'] = $_SESSION['oauth_token'];
		
			$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
		
			$connection = new TwitterOAuth(TWITTER_KEY, TWITTER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
		
			$access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
		
			$_SESSION['access_token'] = $access_token;

			header('Location: /auth/twitter/');
		
		}
	}

	function signin()
	{
	
		if (isset($_SESSION["user"]))
			unset($_SESSION["user"]);
		
		$auth_dlgt = $this->getDelegate("auth");
		
		if (isset($_POST["login_email"], $_POST["login_pass"])) {
		
			$email = trim($_POST["login_email"]);
		
			$password = trim($_POST["login_pass"]);

			$user = $auth_dlgt->authenticate($email, $password);

			if ($user) {

				if($user["active"] == 0) {

					$this->send_activation_mail($user["email"], $user["name"], $user["password"]);

					$response["status"] = "Failure";
			
					$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Kindly activate your account by clicking on the link sent to your email address.';

					echo json_encode($response);
				
					return;

				}

				$_SESSION["user"] = $user;
				
				$response["status"] = "Success";
				
				$response["data"] = "/stories";
				
				echo json_encode($response);
				
				return;
			}

			$response["status"] = "Failure";
			
			$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Check your credentials.';
			
			echo json_encode($response);
			
			return;
		}

		$response["status"] = "Failure";
		
		$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Something went wrong try again.';
		
		echo json_encode($response);
		
		return;
	
	}

	function forgot_password()
	{
		if (isset($_POST['forgot_email']) && !empty($_POST['forgot_email'])) {

			$email = trim($_POST['forgot_email']);

			$auth_dlgt = $this->getDelegate('auth');

			if ($user = $auth_dlgt->check_user($email)) {

				$token = $user['password'];

				$to = $user['email'];

				$email = new \SendGrid\Mail\Mail();

				$email->setFrom("donotreply@fableus.com", "Fableus");

				$email->setSubject("Fableus: Story Reset Password");

				$email->addTo($to, $user["name"]);

				$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Story</title><meta name="viewport" content="width=device-width, initial-scale=1.0"/><style type="text/css">td{font-size: 16px;color: #807d7d;} a {color: #529ecc;} .button a {color: white;}</style></head><body style="margin: 0; padding: 0;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td><table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: #f5f5f561;"><tr><td align="center" style="height:150px"><img src="'.BASE_URL.'/resource/img/elements/site-logo.jpg" alt="story-logo" width="300" height="70" style="display: block;" /></td></tr><tr><td style="padding: 40px 30px 40px 30px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td align="center" style="font-size: 20px;">We received a reset password request..</td></tr><tr><td style="padding: 20px 10px 20px 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td bgcolor="#e5f9ff" style="padding: 25px 25px 25px 25px; border-radius: 10px;"><table cellpadding="0" cellspacing="0" width="100%"><tr><td>&nbsp;</td></tr><tr><td>Hello sir/mam,</td></tr><tr><td>&nbsp;</td>	</tr><tr><td>Please click on the following link to reset your password.</td></tr><tr><td>&nbsp;</td></tr><tr><td><table cellpadding="0" cellspacing="0" width="100%"><tr><td style="font-size: 0; line-height: 0;" width="150">&nbsp;</td><td valign="center" align="center" style="background-color:#529ecc;border-color:#529ecc;color:white;padding:5px 18px;border-radius:8px;font-family:\'ProximaNovaRg\';" class="button"><a href="'.BASE_URL.'/auth/reset?email='.$email.'&token='.$token.'">Reset Password</a></td><td style="font-size: 0; line-height: 0;" width="150">&nbsp;</td></tr></table></td></tr></table></td></tr></table></td></tr></table></td></tr><tr><td align="center" style="padding: 15px 10px 15px 10px;">Copyright © Story.com 2018. All Rights Reserved</td></tr></table></td></tr></table></body></html>';

				$email->addContent("text/html", $message);

				$sendgrid = new \SendGrid(SENDGRID_API_KEY);

				try {
				
					$res = $sendgrid->send($email);
				
				} catch (Exception $e) {
				
					echo 'Caught exception: '. $e->getMessage() ."\n";
				
				}

				$response["status"] = "Success";
				
				$response["data"] = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success:</strong> Email has been sent with reset link.</div>';

				echo json_encode($response);

				return;

			}

			$response["status"] = "Failure";

			$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong>Email does not exists. Kindly register.</div>';

			echo json_encode($response);

			return;

		}

	}

	function reset()
	{
	
		$auth_dlgt = $this->getDelegate('auth');
		
		if(isset($_GET['email'], $_GET['token']) && !empty($_GET['email']) && !empty($_GET['token']) && $user = $auth_dlgt->check_user($_GET['email'])){

			if($user['password'] == $_GET['token']) {

				$this->tpl->assign("user", $user);

				$_SESSION["email"] = $user["email"];
				
				$this->render('reset_password');
			
			}
		
		}
		
		$this->redirect('/');
	
	}

	function new_password()
	{
		
		$auth_dlgt = $this->getDelegate("auth");
		
		$email =  $_SESSION["email"];
		
		if (isset($_POST["password"], $_POST["confirm_password"])) {

			$password = trim($_POST["password"]);
			
			$pass_confirm = trim($_POST["confirm_password"]);

			if (empty($password)) {
				
				$response["status"] = "Failure";
				
				$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Password field cannot be empty.</div>';
				
				echo json_encode($response);
				
				return;
			}

			if ($password != $pass_confirm) {
				
				$response["status"] = "Failure";
				
				$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Passwords Do not match.</div>';
				
				echo json_encode($response);
				
				return;
			}

			$user_id = $auth_dlgt->new_password($email, $password);

			if ($user_id) {

				unset($_SESSION["email"]);

				$response["status"] = "Success";
				
				$response["data"] = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success:</strong> Password Changed. Kindly Login.</div>';
				
				echo json_encode($response);
				
				return;
	        }
		
		}

		$response["status"] = "Failure";
		
		$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Something went wrong. Kindly try again.</div>';
		
		echo json_encode($response);
		
		return;

	}

	function activate_user()
	{
		
		$auth_dlgt = $this->getDelegate("auth");
	
		if(isset($_GET['email'], $_GET['key']) && !empty($_GET['email']) && !empty($_GET['key'])) {

			$email = trim($_GET['email']);

			$key = trim($_GET['key']);

			if($auth_dlgt->activate_user($email, $key))
				$message = "Account has been activated. Kindly login.";
			else
				$this->redirect('/');

			$this->tpl->assign('message', $message);

			$this->render('account_activation');

		} else {

			$this->redirect('/');
	
		}
	
	}

	function story_invite()
	{
		$user = $this->getSessionUser();

		if(!$user || !isset($_GET["email"]) || !$this->email_verification(trim($_GET["email"])))
			$this->redirect("/");

		$email = trim($_GET["email"]);

		$this->send_invite_unregistered_user($user["email"], $user["name"], $email);

		$this->redirect($_SERVER["HTTP_REFERER"]);
	
	}

	function logout()
	{
	
		unset($_SESSION['user']);
	
		session_destroy();
	
		$this->redirect('/');
	
	}

	function search()
	{
	
		$user = $this->getSessionUser();
	
		$auth_dlgt = $this->getDelegate("auth");
	
		$story_dlgt = $this->getDelegate("story");
	
		$stories_dlgt = $this->getDelegate("stories");

		if (isset($_POST["search"]) && !empty($_POST["search"])) {
	
			$string = trim($_POST["search"]);
	
			$stories = $auth_dlgt->get_stories_search($string, $user["user_id"]);

			foreach ($stories as &$story) {
	
				$story["stats"] = $story_dlgt->story_stats($story["id"]);
	
				$story["user_role"] = $story_dlgt->get_user_role($story["id"], $user["user_id"]);
	
				$story["story_apply"] = $stories_dlgt->apply_check($story["id"], $user["user_id"]);
	
				$story["50_words"] = trim($story_dlgt->get_story($story["id"])["def_chapter"]["50_words"]);
	
				$last_story_id = $story["id"];
	
			}
			
		}

		$this->tpl->assign("user", $user);
	
		$this->tpl->assign("stories", $stories);
	
		$this->tpl->assign("last_story_id", $last_story_id);
	
		$this->render('search_page');
	
	}
	
	function contact() {
	    
	    if (isset($_POST["contact_name"], $_POST["contact_subject"], $_POST["contact_email"], $_POST["contact_message"])) {
	        
	        $name = trim($_POST["contact_name"]);
	        
	        $subject = trim($_POST["contact_subject"]);
	        
	        $email = trim($_POST["contact_email"]);
	        
	        $message = trim($_POST["contact_message"]);
	
			if(empty($name)) {
			    
			    $response["status"] = "Failure";
		
        		$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Name is required.</div>';
        		
        		echo json_encode($response);
        		
        		return;
			    
			}
			
			if(empty($subject)) {
			    
			    $response["status"] = "Failure";
		
        		$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Subject is required.</div>';
        		
        		echo json_encode($response);
        		
        		return;
			    
			}
			
			if(empty($email)) {
			    
			    $response["status"] = "Failure";
		
        		$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Email is required.</div>';
        		
        		echo json_encode($response);
        		
        		return;
			    
			}
			
			if (!$this->email_verification($email)) {

				$response["status"] = "Failure";

				$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Email does not exist. Please enter valid email.</div>';

				echo json_encode($response);

				return;

			}
			
			if(empty($message)) {
			    
			    $response["status"] = "Failure";
		
        		$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Message is required.</div>';
        		
        		echo json_encode($response);
        		
        		return;
			    
			}
			
			
			if($this->send_feedback_mail($name, $subject, $email, $message)) {
			    
			    $response["status"] = "Success";
		
        		$response["data"] = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> Feedback Submitted.</div>';
        		
        		echo json_encode($response);
        		
        		return;
			    
			}
			
		}
		
		$response["status"] = "Failure";
		
		$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Something went wrong. Kindly try again.</div>';
		
		echo json_encode($response);
		
		return;
	    
	}

}