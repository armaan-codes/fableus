<?php
class UserDelegate extends BaseDelegate
{

	function no_user_contributions($user_id) {
		$count = $this->user->no_user_contributions($user_id);
		if (!$count["no_contributions"]) {
			return 0;
		}
		return $count["no_contributions"];
	}

	function no_user_projects($user_id) {
		$count = $this->user->no_user_projects($user_id);
		if (!$count["no_projects"]) {
			return 0;
		}
		return $count["no_projects"];
	}

	function get_user_plan($user_id) {
		return $this->user->get_user_plan($user_id);
	}

	function initialize_plan($user_id, $plan, $price, $period) {
		return $this->user->initialize_plan($user_id, $plan, $price, $period);
	}

	function initialize_free_plan($user_id) {
		return $this->user->initialize_free_plan($user_id);
	}
######################################################################################################

	function get_pending_plan($user_id) {
		return $this->user->get_pending_plan($user_id);
	}

	function delete_pending_plan($plan_id) {
		return $this->user->delete_pending_plan($plan_id);
	}

	function login_update($user_id) {
		return $this->user->login_update($user_id);
	}

	/*	Revised Code as on 07/06/2018	*/
	
	function get_user_universal($user_id)
	{
	
		return $this->user->get_user_universal($user_id);
	
	}

	function get_user($user_id)
	{
		
		return $this->user->get_user($user_id);
	
	}

	function get_user_by_email($email)
	{

		return $this->user->get_user_by_email($email);
	
	}

	function no_user_stories($user_id)
	{

		$count = $this->user->no_user_stories($user_id);
	
		if (!$count["no_stories"])
			return 0;

		return $count["no_stories"];
	
	}

	function upload_user_image($user_id, $image)
	{

		$image = $this->upload_image($image, "user");

		if ($image == false)
			$image = 'user.png';

		return $this->user->upload_user_image($user_id, $image);
	
	}

	function update_password($user_id, $password)
	{

		return $this->user->update_password($user_id, $password);
	
	}

	function update_user_info($user_id, $name, $first_name, $last_name = false, $bio = false)
	{

		return $this->user->update_user_info($user_id, $name, $first_name, $last_name, $bio);

	}

	function user_followers($user_id)
	{
		$folowers =  $this->user->user_followers($user_id);

		foreach ($folowers as $folower) {

			$followers[] = $this->user->get_user($folower["member_follower_id"]);
			
		}

		return $followers;

	}
	
	function get_followers($user_id)
	{
		return $this->user->get_followers($user_id);
	}

	function check_follow($user_id, $member_id)
	{
		return $this->user->check_follow($user_id, $member_id);
	}

	function follow_member($user_id, $member_id)
	{
		return $this->user->follow_member($user_id, $member_id);
	}

	function unfollow_member($user_id, $member_id)
	{
		return $this->user->unfollow_member($user_id, $member_id);
	}
}
?>