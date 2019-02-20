<?php
class TeamDelegate extends BaseDelegate
{
	/*	Revised Code as on 08/06/2018	*/

	function get_teams($user_id)
	{

		return $this->user->get_teams($user_id);
	
	}

	function get_team($user_id, $team_id)
	{

		$team = $this->user->get_team($user_id, $team_id);
		
		$team["members"] = $this->user->get_members($team_id);

		return $team;
	
	}

	function create_team($user_id, $team_name, $team_desc = false, $team_image)
	{
		
		$image = $this->upload_image($team_image, "team");

		if ($image == false)
			$image = 'team.png';

		return $this->user->add_team($user_id, $team_name, $team_desc, $image);
	
	}

	function update_team($team_id, $team_name, $team_desc, $team_image)
	{
		
		$image = $this->upload_image($team_image, "team");
		
		return $this->user->update_team($team_id, $team_name, $team_desc, $image);
	
	}

	function add_member($team_id, $member_id)
	{

		return $this->user->add_member($team_id, $member_id);
	
	}

	function delete_member($team_id, $member_id)
	{

		return $this->user->delete_member($team_id, $member_id);
	
	}

	function delete_team($user_id, $team_id)
	{

		return $this->user->delete_team($user_id, $team_id);
	
	}
}