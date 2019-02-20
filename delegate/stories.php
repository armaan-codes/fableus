<?php
class StoriesDelegate extends BaseDelegate
{

	/*	Revised code as on 07-06-2018	*/

	function get_all_stories($user_id = false, $filter = false)
	{
		$stories = $this->stories->get_all_stories($user_id, $filter);
		
		foreach ($stories as &$story) {
			
			$story["collabs"] = $this->story->get_story_collab($story["id"]);
		
		}

		return $stories;
	}

	function get_other_stories($user_id = false)
	{
	
		return $this->stories->get_other_stories($user_id);
	
	}

	function load_more_stories($user_id = false, $last_story_id, $filter = false)
	{

		$stories = $this->stories->load_more_stories($user_id, $last_story_id, $filter);
		
		foreach ($stories as &$story) {
			
			$story["collabs"] = $this->story->get_story_collab($story["id"]);
		
		}
		
		return $stories;
	
	}

	function get_user_stories($user_id, $filter = false)
	{
	
		$stories = $this->stories->get_user_stories($user_id, $filter);
	
		foreach ($stories as &$story) {
	
			$story["collabs"] = $this->story->get_story_collab($story["id"]);
	
		}
	
		return $stories;
	
	}

	function apply_check($story_id, $owner_id)
	{

		return $this->stories->apply_check($story_id, $owner_id);

	}

	function load_user_stories($user_id, $last_story_id, $filter = false)
	{
		
		$stories = $this->stories->load_user_stories($user_id, $last_story_id, $filter);
		
		foreach ($stories as &$story) {
		
			$story["collabs"] = $this->story->get_story_collab($story["id"]);
		
		}
		
		return $stories;
	
	}

	function get_apply_request($story_id, $owner_id)
	{
		return $this->stories->get_apply_request($story_id, $owner_id);
	
	}

	function user_collab_stories($user_id, $filter = false)
	{

		$stories = $this->stories->user_collab_stories($user_id, $filter);
		
		foreach ($stories as &$story) {
		
			$story["collabs"] = $this->story->get_story_collab($story["id"]);
		
		}
		
		return $stories;
	
	}

	function load_collab_stories($user_id, $last_story_id, $filter = false)
	{

		$stories = $this->stories->load_collab_stories($user_id, $last_story_id, $filter);
		
		foreach ($stories as &$story) {
		
			$story["collabs"] = $this->story->get_story_collab($story["id"]);
		
		}
		
		return $stories;
	
	}

	function get_stories_invite($collab_id)
	{

		return $this->stories->get_stories_invite($collab_id);
	
	}

	function accept_invite($invite_id, $collab_id, $story_id, $room_id)
	{

		return $this->stories->accept_invite($invite_id, $collab_id, $story_id, $room_id);
	
	}

	function decline_invite($invite_id)
	{

		return $this->stories->decline_invite($invite_id);
	
	}

	function accept_apply($apply_id, $collab_id, $story_id, $room_id)
	{

		return $this->stories->accept_apply($apply_id, $collab_id, $story_id, $room_id);
	
	}

	function decline_apply($apply_id)
	{

		return $this->stories->decline_apply($apply_id);
	
	}

	function load_member_stories($member_id, $last_story_id, $filter = false)
	{
		
		$stories = $this->stories->load_member_stories($member_id, $last_story_id, $filter);
		
		foreach ($stories as &$story) {
		
			$story["collabs"] = $this->story->get_story_collab($story["id"]);
		
		}
		
		return $stories;
	
	}

	function accept_invite_read($invite_id, $collab_id, $story_id, $room_id)
	{

		return $this->stories->accept_invite_read($invite_id, $collab_id, $story_id, $room_id);
	
	}

	
	function decline_invite_read($invite_id)
	{

		return $this->stories->decline_invite_read($invite_id);
	
	}

}