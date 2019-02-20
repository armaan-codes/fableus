<?php
class AuthModel extends BaseModel
{

	function facebook_signup($name, $facebook_id, $password, $email = false) {
		$this->beginTransaction();
		if(!empty($email))
			$user_id = $this->register_user($name, $email, $password);
		else
			$user_id = $this->register_user($name, $facebook_id, $password);
		
		$sql = "INSERT INTO facebook (user_id, facebook_id, facebook_name, facebook_email, ts_registered) VALUES (?,?,?,?, NOW())";
		
		$this->create($sql, array($user_id, $facebook_id, $name, $email));
		$this->endTransaction();
		return $user_id;
	}

	function add_payment($user_id, $payer_id, $payment_id, $token) {
		$sql = "INSERT INTO payment ( user_id, payer_id, payment_id, payment_token, ts_registered ) VALUES ( ?,?,?,?, NOW() )";
		return $this->create($sql, array($user_id, $payer_id, $payment_id, $token));
	}

	function update_plan($user_id) {
		$this->beginTransaction();

		$sql = "SELECT * FROM member_plan WHERE user_id = ? AND status = ?";
		
		if ($init_plan = $this->getRow($sql, array($user_id, WAITING_CONFIRMATION))) {
			$sql = "SELECT * FROM member_plan WHERE user_id = ? AND status = ?";
		
			if ($prev = $this->getRow($sql, array($user_id, ACTIVE_PLAN))) {
				$sql = "UPDATE member_plan SET status = ? WHERE plan_id = ?";
				$this->update($sql, array(UPGRADED_PLAN, $prev["plan_id"]));
			}
			
			$sql = "UPDATE member_plan SET status = ? WHERE plan_id = ?";
			$this->update($sql, array(ACTIVE_PLAN, $init_plan['plan_id']));

			$sql = "UPDATE member SET plan = ? WHERE user_id = ?";
			$update = $this->update($sql, array($init_plan['plan'], $user_id));
		}

		$this->endTransaction();

		if ($update)
			return true;
		return false;
	}

	function get_payment($payment_id) {
		$sql = "SELECT * FROM payment WHERE payment_id = ?";
		return $this->getRow($sql, array($payment_id));
	}

	/*	Revised code as on 07/06/2018	*/

	function check_user($email)
	{
		
		$sql = "SELECT * FROM member WHERE email = ?";
		
		return $this->getRow($sql, array($email));
	
	}

	function register_user($name, $email, $password)
	{
	
		$this->beginTransaction();

		$sql = "INSERT INTO member ( plan, name, email, password, ts_registered ) VALUES ( ?,?,?,?, NOW() )";
	
		$user_id = $this->create($sql, array(MEMBER_BASIC, $name, $email, md5($password)));

		$this->endTransaction();
	
		return $user_id;
	
	}

	function twitter_signup($name, $twitter_id, $password, $screen_name)
	{
	
		$this->beginTransaction();

		$user_id = $this->register_user($name, $twitter_id, $password);

		$sql = "UPDATE member SET active = 1 WHERE user_id = ?";

		$this->update($sql, array($user_id));
	
		$sql = "INSERT INTO twitter (user_id, twitter_id, twitter_name, screen_name, ts_registered) VALUES (?,?,?,?, NOW())";
	
		$this->create($sql, array($user_id, $twitter_id, $name, $screen_name));
	
		$this->endTransaction();
	
		return $user_id;
	
	}

	function authenticate($email, $password)
	{
		
		$sql = "SELECT
					user_id,
					role,
					plan,
					name,
					first_name,
					last_name,
					bio,
					email,
					password,
					login,
					active,
					image
				FROM member
				WHERE email = ?
				AND password = ?";

		return $this->getRow($sql, array($email, md5($password)));

	}

	function new_password($email, $password)
	{
		
		$sql = "UPDATE member SET password = ? WHERE email = ? AND active = 1";
		
		return $this->update($sql, array(md5($password), $email));
	
	}

	function activate_user($email, $key)
	{
		$sql = "SELECT * FROM member WHERE email = ? AND password = ? AND active = 0";

		if($user = $this->getRow($sql, array($email, $key))) {
	    
			$sql = "UPDATE member SET active = 1 where user_id = ?";
			
			return $this->update($sql, array($user['user_id']));
		
		}

        return false;
    
    }
}