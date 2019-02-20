<?php
class AuthDelegate extends BaseDelegate
{

	

	function add_payment($user_id, $payer_id, $payment_id, $token) {
		return $this->auth->add_payment($user_id, $payer_id, $payment_id, $token);
	}

	function update_plan($user_id) {
		return $this->auth->update_plan($user_id);
	}

	function get_payment($payment_id) {
		return $this->auth->get_payment($payment_id);
	}

	function facebook_login($profile) {
		if(empty($profile['email'])){
			$user = $this->user->get_user_by_email($profile['id']);
		} else {
			$user = $this->user->get_user_by_email($profile['email']);
		}


		if (empty($user)) {
			$password = $this->random_password();
			$user_id = $this->auth->facebook_signup($profile['name'], $profile['id'], $password, $profile['email']);
			return $this->user->get_user($user_id);
		} elseif ($user['active'] == 0) {
		    $this->user->activate_user($user);
        }
		return $user;
	}

    /*	Revised code as on 07/06/2018	*/

    function check_user($email)
    {
	
		return $this->auth->check_user($email);
	
	}

	function register_user($name, $email, $password)
	{

		$user = $this->auth->register_user($name, $email, $password);

		if($user)
			$this->story->add_member_invites($user, $email);
		
		return $user;
	}

	function twitter_login($profile)
	{
		
		$twitter_id = $profile->id_str;
		
		$twitter_name = $profile->name;
		
		$screen_name = $profile->screen_name;

		if ($user = $this->user->get_user_by_email($twitter_id)) {
			
			if ($user['active'] == 0)
				$this->user->activate_user($user);

			return $user["user_id"];
		
		} else {

			$password = $this->random_password();
			
			return $this->auth->twitter_signup($twitter_name, $twitter_id, $password, $screen_name);
		
		}
		
	}

	function authenticate($email, $password)
	{

		return $this->auth->authenticate($email, $password);

	}

	function new_password($email, $password)
	{
	
		return $this->auth->new_password($email, $password);
	
	}

	function activate_user($email, $key)
	{

		return $this->auth->activate_user($email, $key);

	}

	function get_stories_search($string)
	{

		$stories = $this->stories->get_stories_search($string);
		
		foreach ($stories as &$story) {
		
			$story["collabs"] = $this->story->get_story_collab($story["id"]);
		
		}
		
		return $stories;
	
	}

}
?>