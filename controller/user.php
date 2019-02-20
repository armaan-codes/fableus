<?php
class UserController extends BaseController
{	
	protected $models = array('stories', 'story');
	
	function index($args = array()) {
	
		$this->redirect("/user/profile");
	
	}

	function upgrade_plan() {
		$user = $this->getSessionUser();
		$user_dlgt = $this->getDelegate("user");
		
		if (isset($_POST["plan"]) && !empty($_POST["plan"]) && $user) {
			$plan = trim($_POST["plan"]);

			if($plan == "Free") {

				$plan = MEMBER_BASIC;
			
			} else {
				
				$sep_pos = strpos($plan, "/");
				
				$price = substr($plan, 1, $sep_pos - 1);
				
				$period = strtoupper(substr($plan, $sep_pos + 1));

				if ($price == "1.99" || $price == "14.99") {
				
					$plan = MEMBER_PREMIUM;
				
				}

				if ($price == "2.99" || $price == "24.99") {
				
					$plan = MEMBER_AUTHOR;
				
				}
			
			}

			if($user["plan"] != $plan) {

				if($plan == MEMBER_BASIC) {

					if($user_dlgt->initialize_free_plan($user["user_id"])) {

						$response["status"] = "Null";
						
						$response["data"] = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Plan changed.</div>';

						echo json_encode($response);

						return;

					}

					$response["status"] = "Null";
						
					$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>hehehehehehe</div>';

					echo json_encode($response);
						
					return;

				} else {

					if ($user_dlgt->initialize_plan($user["user_id"], $plan, $price, $period)) {
						
						$success_url = BASE_URL.'/user/payment_success/';
						
						$failure_url = BASE_URL.'/user/payment_failure/';

						if($paypal_link = $this->payment($user["user_id"], $success_url, $failure_url, $price)) {
								
							$response["status"] = "Success";
						
							$response["data"] = $paypal_link;
						
							echo json_encode($response);
						
							return;
						
						}

						$response["status"] = "Null";
						
						$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Unable to connect to paypal services. Try again Later.</div>';

						echo json_encode($response);
						
						return;
					
					}

					$response["status"] = "Null";
					
					$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Already raised request for plan upgrade. Kindly make payment OR delete the upgrade request, before proceeding.</div>';
					
					echo json_encode($response);
					
					return;

				}

			}

		}

		$response["status"] = "Failure";
		
		echo json_encode($response);
		
		return;
	
	}

#######################################################################################################

	function login(){
		$user = $this->getSessionUser();
		$user_dlgt = $this->getDelegate("user");

		if (!$user)
			return;

		if($user_dlgt->login_update($user["user_id"])) {
			unset($_SESSION["user"]["login"]);
			return;
		}
	}

	function payment_success($user_id) {
		$user = $this->getSessionUser();


		if($user_id != $user["user_id"] || empty($user) || !isset($_GET['paymentId'], $_GET['token'], $_GET['PayerID']))
			$this->redirect("/");

		$payment_id = $_GET['paymentId'];
		$payer_id = $_GET['PayerID'];
		$token = $_GET['token'];
		
		if($this->payment_execution($payment_id, $payer_id)){
			$auth_dlgt = $this->getDelegate("auth");
			$user_dlgt = $this->getDelegate("user");
			
			if ($auth_dlgt->add_payment($user_id, $payer_id, $payment_id, $token) && $auth_dlgt->update_plan($user_id)) {
				
				if (isset($_SESSION["user"])) {
					unset($_SESSION["user"]);
				}
				
				$user = $user_dlgt->get_user($user_id);
				$_SESSION["user"] = $user;

				$payment = $auth_dlgt->get_payment($payment_id);

				$this->tpl->assign('payment', $payment);
				$this->tpl->assign('user', $user);
				$this->render('upgrade_success');
			}	
		} else {
            $this->tpl->assign('user', $user);
            $this->render("upgrade_failure");
        }
    }

	function payment_failure($user_id) {
		$user = $this->getSessionUser();

		if(empty($user))
			$this->redirect("/");

		$this->tpl->assign('user', $user);
		$this->render("upgrade_failure");
	}

	function activate() {
		$user = $this->getSessionUser();
		$user_dlgt = $this->getDelegate("user");
		$pending_plan = $user_dlgt->get_pending_plan($user["user_id"]);

		if(empty($user) || empty($pending_plan))
			$this->redirect("/");

		$this->tpl->assign("user", $user);
		$this->tpl->assign("plan", $pending_plan);
		$this->render("activate_plan");
	}

	function pay_pending() {
		$user = $this->getSessionUser();
		$user_dlgt = $this->getDelegate("user");
		$pending_plan = $user_dlgt->get_pending_plan($user["user_id"]);

		if(empty($user) || empty($pending_plan)) {
			$response["status"] = "Failure";
			$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><span style="color: red;">Warning!</span></strong> You are not authorised.</div>';
			echo json_encode($response);
			return;
		}

		$success_url = BASE_URL.'/user/payment_success/';
		$failure_url = BASE_URL.'/user/payment_failure/';

		$paypal_link = $this->payment($user["user_id"], $success_url, $failure_url, $pending_plan["amount"]);
		if ($paypal_link) {
			$response["status"] = "Success";
			$response["data"] = $paypal_link;
			echo json_encode($response);
			return;
		}

		$response["status"] = "Failure";
		$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><span style="color: red;">Warning!</span></strong> Something went wrong.</div>';
		echo json_encode($response);
		return;
	}

	function delete_pending() {
		$user = $this->getSessionUser();
		$user_dlgt = $this->getDelegate("user");
		$pending_plan = $user_dlgt->get_pending_plan($user["user_id"]);

		if(empty($user) || empty($pending_plan)) {
			$response["status"] = "Failure";
			$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><span style="color: red;">Warning!</span></strong> You are not authorised.</div>';
			echo json_encode($response);
			return;
		}

		if ($user_dlgt->delete_pending_plan($pending_plan["plan_id"])) {
			unset($_SESSION["user"]);
			$_SESSION["user"] = $user_dlgt->get_user($pending_plan["user_id"]);
			$response["status"] = "Success";
			$response["data"] = "/stories";
			echo json_encode($response);
			return;
		}

		$response["status"] = "Failure";
		$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><span style="color: red;">Warning!</span></strong> Something went wrong.</div>';
		echo json_encode($response);
		return;
	}

	/*	Revised Code as on 09/06/2018	*/

	function profile()
	{

		$user = $this->getSessionUser();
		
		$user_dlgt = $this->getDelegate("user");

		if (!$user)
			$this->redirect("/");

		$user["no_stories"] = $user_dlgt->no_user_stories($user["user_id"]);
		
		$user["no_contributions"] = $user_dlgt->no_user_contributions($user["user_id"]);

		$user["followers"] = $user_dlgt->user_followers($user["user_id"]);

		$this->tpl->assign('user', $user);
		
		$this->render('profile_page_new');
	
	}

	function image_upload()
	{

		$user = $this->getSessionUser();
		
		$user_dlgt = $this->getDelegate("user");

		if (!$user)
			$this->redirect("/");

		if (isset($_FILES["user-image"]) && $user_dlgt->upload_user_image($user["user_id"], $_FILES["user-image"])) {
		
			if (isset($_SESSION["user"]))
				unset($_SESSION["user"]);
			
			$user = $user_dlgt->get_user($user["user_id"]);
			
			$_SESSION["user"] = $user;
		
		}

		$this->redirect("/user/profile");
	
	}

	function change_password()
	{

		$user = $this->getSessionUser();
		
		$user_dlgt = $this->getDelegate("user");

		if (!$user){
			
			$response["status"] = "Failure";
			
			$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Not allowed.</div>';
			
			echo json_encode($response);
			
			return;
		
		}

		if (isset($_POST["current-password"], $_POST["new-password"], $_POST["confirm-new-password"])) {
			
			$pass = md5(trim($_POST["current-password"]));
			
			$new_pass = trim($_POST["new-password"]);
			
			$new_pass_confirm = trim($_POST["confirm-new-password"]);


			if ($pass != $user["password"]) {
			
				$response["status"] = "Failure";
			
				$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Incorrect Password.</div>';
			
				echo json_encode($response);
			
				return;
			
			}

			if ($new_pass != $new_pass_confirm) {

				$response["status"] = "Failure";
				
				$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> New password and confirm new password do not match.</div>';
				
				echo json_encode($response);
				
				return;
			
			}

			if ($user_dlgt->update_password($user["user_id"], $new_pass)) {
			
				if (isset($_SESSION["user"]))
					unset($_SESSION["user"]);
				
				$user = $user_dlgt->get_user($user["user_id"]);

				$_SESSION["user"] = $user;

				$response["status"] = "Success";
				
				$response["data"] = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> Password Changed.</div>';
				
				echo json_encode($response);
				
				return;
			
			}
		
		}

		$response["status"] = "Failure";
		
		$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Something went wrong.</div>';
		
		echo json_encode($response);
		
		return;
	
	}

	function view($member_id)
	{

		$user = $this->getSessionUser();
		
		$user_dlgt = $this->getDelegate("user");
		
		$story_dlgt = $this->getDelegate("story");
		
		$stories_dlgt = $this->getDelegate("stories");

		$member = $user_dlgt->get_user($member_id);
		
		$member["no_stories"] = $user_dlgt->no_user_stories($member_id);
		
		$member["no_contributions"] = $user_dlgt->no_user_contributions($member_id);

		$member["followers"] = $user_dlgt->get_followers($member["user_id"]);
		
		if (!$user || !$member)
			$this->redirect("/");

		if ($user["user_id"] == $member["user_id"])
			$this->redirect("/user/profile");

		$filter = $_GET["filter"] ? trim($_GET["filter"]) : null;

		$types = $story_dlgt->get_story_types();

		$stories = $stories_dlgt->get_user_stories($member_id, $filter);
		
		$public_stories = array();

		foreach ($stories as &$story) {
		
			$story["stats"] = $story_dlgt->story_stats($story["id"]);
		
			$story["user_role"] = $story_dlgt->get_user_role($story["id"], $user["user_id"]);
		
			$story["50_words"] = trim($story_dlgt->get_story($story["id"])["def_chapter"]["50_words"]);

			if ($story["publish"] == PUBLISH) {
		
				$public_stories[] = $story;
		
				$last_story_id = $story["id"];
		
			}
		
		}

		$other_stories = $stories_dlgt->get_other_stories($member_id);

		$follow_check = array('member_id' => $member["user_id"], 'member_follower_id' => $user['user_id']);

		$this->tpl->assign('user', $user);
		
		$this->tpl->assign('filter', $filter);

		$this->tpl->assign("types", $types);

		$this->tpl->assign('member', $member);

		$this->tpl->assign('follow_check', $follow_check);
		
		$this->tpl->assign('stories', $public_stories);
		
		$this->tpl->assign('other_stories', $other_stories);
		
		$this->tpl->assign('last_story_id', $last_story_id);
		
		$this->render('user_view_new');
	
	}

	function change_information()
	{

		$user = $this->getSessionUser();
		
		$user_dlgt = $this->getDelegate("user");

		if (!$user){
			
			$response["status"] = "Failure";
			
			$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Not allowed.</div>';
			
			echo json_encode($response);
			
			return;
		
		}

		if (isset($_POST["nickname"], $_POST["first-name"], $_POST["last-name"], $_POST["bio"])) {

			$name = trim($_POST["nickname"]);

			$first_name = trim($_POST["first-name"]);

			$last_name = trim($_POST["last-name"]);

			$bio = trim($_POST["bio"]);

			if (empty($name)) {
			
				$response["status"] = "Failure";
			
				$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Nickname is required.</div>';
			
				echo json_encode($response);
			
				return;
			
			}

			if (empty($first_name)) {
			
				$response["status"] = "Failure";
			
				$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> First Name is required.</div>';
			
				echo json_encode($response);
			
				return;
			
			}

			if ($user_dlgt->update_user_info($user['user_id'], $name, $first_name, $last_name, $bio)) {
			
				if (isset($_SESSION["user"]))
					unset($_SESSION["user"]);
				
				$user = $user_dlgt->get_user($user["user_id"]);

				$_SESSION["user"] = $user;

				$response["status"] = "Success";
				
				$response["data"] = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> Information Updated.</div>';
				
				echo json_encode($response);
				
				return;
			
			}
		
		}

		$response["status"] = "Failure";
		
		$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Something went wrong.</div>';
		
		echo json_encode($response);
		
		return;
	
	}

	function follow()
	{
		$user = $this->getSessionUser();
		
		$user_dlgt = $this->getDelegate("user");

		if (!$user || !isset($_POST["member_id"]) || empty($_POST["member_id"])){
			
			$response["status"] = "Failure";
			
			$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Not allowed.</div>';
			
			echo json_encode($response);
			
			return;
		
		}

		$member = $user_dlgt->get_user(trim($_POST["member_id"]));

		if (!$member){
			
			$response["status"] = "Failure";
			
			$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Invalid Request.</div>';
			
			echo json_encode($response);
			
			return;
		
		}

		if (!$user_dlgt->check_follow($user["user_id"], $member["user_id"]) && $user_dlgt->follow_member($user["user_id"], $member["user_id"])) {

			$response["status"] = "Success";
			
			$response["data"] = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> Member followed.</div>';
			
			echo json_encode($response);
			
			return;

		}


		$response["status"] = "Failure";
			
		$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Invalid Request.</div>';
		
		echo json_encode($response);
		
		return;

	}

	function unfollow()
	{
		$user = $this->getSessionUser();
		
		$user_dlgt = $this->getDelegate("user");

		if (!$user || !isset($_POST["member_id"]) || empty($_POST["member_id"])){
			
			$response["status"] = "Failure";
			
			$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Not allowed.</div>';
			
			echo json_encode($response);
			
			return;
		
		}

		$member = $user_dlgt->get_user(trim($_POST["member_id"]));

		if (!$member){
			
			$response["status"] = "Failure";
			
			$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Invalid Request.</div>';
			
			echo json_encode($response);
			
			return;
		
		}

		if ($user_dlgt->check_follow($user["user_id"], $member["user_id"]) && $user_dlgt->unfollow_member($user["user_id"], $member["user_id"])) {

			$response["status"] = "Success";
			
			$response["data"] = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> Member followed.</div>';
			
			echo json_encode($response);
			
			return;

		}


		$response["status"] = "Failure";
			
		$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Invalid Request.</div>';
		
		echo json_encode($response);
		
		return;

	}

	function compose()
	{
		$user = $this->getSessionUser();
		
		$user_dlgt = $this->getDelegate("user");

		if (!$user || !isset($_POST["follower-email"]) || empty($_POST["follower-email"])){
			
			$response["status"] = "Failure";
			
			$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Not allowed.</div>';
			
			echo json_encode($response);
			
			return;
		
		}

		$member = $user_dlgt->get_user_by_email(trim($_POST["follower-email"]));

		if (!$member){
			
			$response["status"] = "Failure";
			
			$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> User do not exists.</div>';
			
			echo json_encode($response);
			
			return;
		
		}
		
		if ($user_dlgt->check_follow($member["user_id"], $user["user_id"])) {

			$response["status"] = "Success";
			
			$response["data"] = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> Email sent.</div>';
			
			echo json_encode($response);
			
			return;

		}


		$response["status"] = "Failure";
			
		$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Invalid Request.</div>';
		
		echo json_encode($response);
		
		return;

	}

}
?>