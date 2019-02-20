<?php
class StoryDelegate extends BaseDelegate
{
	/*	Revised Code as on 07/06/2018	*/

	function story_stats($story_id)
	{

		$stats = $this->story->story_stats($story_id);

		if (empty($stats))
			$stats = array( "story_id" => $story_id, "no_votes" => 0, "no_views" => 0, "no_comments" => 0, "no_contributors" => 0, "no_edits" => 1);

		$stats["rating"] = $this->story->story_rating($story_id);

		$stats["last_edit"] = $this->story->story_last_edit($story_id);

		$stats["last_view"] = $this->story->story_last_view($story_id);

		$stats["last_comment"] = $this->story->story_last_comment($story_id);

		return $stats;
	}

	function get_user_role($story_id, $user_id)
	{
	
		$role = $this->story->get_user_role($story_id, $user_id)["role"];
	
		if (empty($role))
			return ROLE_READER;
	
		return $role;
	}

	function story_check($story_id)
	{

		return $this->story->story_check($story_id);
	
	}

	function get_story($story_id, $title_part_id = false, $view = false)
	{
		
		$story = $this->story->get_story($story_id);
		
		$story["collab"] = $this->story->get_story_collab($story_id);

		$toc = $this->story->get_toc($story_id, $view);

		if (!$title_part_id)
			$title_part_id = $toc["toc"]["1"][TOC_DATA_KEY]["part_id"];


		if ($title_part_id == $toc["toc"]["1"][TOC_DATA_KEY]["part_id"])
			$story["intro_chap"] = true;
		
		$story["toc"] = $toc["toc"];
		
		$story["def_chapter"] = $this->get_body_with_title($story_id, $title_part_id, $view);
		
		return $story;
	
	}

	function get_body_with_title($story_id, $title_part_id, $view = false)
	{
		$title = $this->story->get_title($story_id, $title_part_id);

		$val = array();

		foreach ($title as $word) {

			if($view) {

				if(!preg_match('/(<[^>]*>)/i', $word["word"])) {

					array_push($val, '<span data-user="' . $word["editor_id"] . '">' . $word["word"] . '</span>');

				}

			} else {

				array_push($val, $word["word"]);

			}

		}

		$title = implode(" ", $val);

		$body = $this->story->get_body($story_id, $title_part_id);

		$val = array();

		for ($x = 0; $x <= 50; $x++) {

			if(!preg_match('/(<[^>]*>)/i', $body[$x]["word"])) {
		
				array_push($val, $body[$x]["word"]);
			
			}

		}

		$words = implode(" ", $val);

		$val = array();

		foreach ($body as $word) {

			if($view) {

				if(preg_match('/(<[^>]*>)/i', $word["word"])) {

					array_push($val, $word["word"]);

				} else {

					array_push($val, '<span data-user="' . $word["editor_id"] . '">' . $word["word"] . '</span>');

				}

			} else {

				array_push($val, $word["word"]);

			}

		}

		$body = implode(" ", $val);

		$part_detail = $this->story->get_part_detail($title_part_id);

		return array( "title" => trim($title), "body" => trim($body), "title_part_id" => $title_part_id, "50_words" => trim($words), "publish" => $part_detail["part_row"]["publish"]);
	}

	function story_comments($story_id)
	{

		return $this->story->story_comments($story_id);
	
	}

	function story_active_contributors($story_id)
	{

		$collab_ids = $this->story->story_active_contributors($story_id);
		
		$collabs = array();

		$i = 0;
		
		foreach ($collab_ids as $collab_id) {
		
			$collab = $this->user->get_user($collab_id);
		
			$collabs[] = array("id" => $collab["user_id"], "class" => "collab_class_".$i, "name" => $collab["name"], "first_name" => $collab["first_name"], "last_name" => $collab["last_name"], "image" => $collab["image"], "bio" => $collab["bio"]);

			$i++;
		
		}
		
		return $collabs;
	
	}

	function get_user_vote($user_id, $story_id)
	{

		$vote = $this->story->get_user_vote($user_id, $story_id);

		if (!empty($vote))
			return true;
	
		return false;
	
	}

	function get_analysis($story_id)
	{

		$word_analysis = $this->story->word_analysis($story_id);
		
		$time_analysis = $this->story->time_analysis($story_id);
		
		return array( "words" => $word_analysis, "time" => $time_analysis);
	
	}

	function add_view($story_id)
	{

		return $this->story->add_view($story_id);
	
	}

	function last_message_id($room_id)
	{

		return $this->story->last_message_id($room_id);
	
	}

	function add_comment($story_id, $user_id, $message)
	{

		return $this->story->add_comment($story_id, $user_id, $message);
	
	}

	function get_comment($user_id, $comment_id)
	{
		
		return $this->story->get_comment($user_id, $comment_id);
	
	}

	function update_comment($comment_id, $message)
	{
	
		return $this->story->update_comment($comment_id, $message);
	
	}

	function publish_story($story_id)
	{

		return $this->story->publish_story($story_id);
	
	}

	function unpublish_story($story_id)
	{

		return $this->story->unpublish_story($story_id);
	
	}

	function invite_check($story_id, $owner_id, $collab_id)
	{

		return $this->story->invite_check($story_id, $owner_id, $collab_id);
	
	}

	function invite_read_check($story_id, $owner_id, $collab_id)
	{

		return $this->story->invite_read_check($story_id, $owner_id, $collab_id);
	
	}

	function apply_check($story_id, $owner_id, $collab_id)
	{
	
		return $this->story->apply_check($story_id, $owner_id, $collab_id);
	
	}

	function invite_collab($story_id, $owner_id, $collab_id)
	{

		return $this->story->invite_collab($story_id, $owner_id, $collab_id);
	
	}

	function invite_read_collab($story_id, $owner_id, $collab_id)
	{

		return $this->story->invite_read_collab($story_id, $owner_id, $collab_id);
	
	}

	function up_vote($user_id, $story_id)
	{

		return $this->story->up_vote($user_id, $story_id);
	
	}

	function story_image_upload($story_id, $user_id, $image, $width, $height)
	{
	
		$image_name = $this->upload_image($image, "story");
	
		return $this->story->story_image_upload($story_id, $user_id, $image_name, $width, $height);
	
	}
	
	function story_image_resize($story_id, $user_id, $width, $height)
	{
	
		return $this->story->story_image_resize($story_id, $user_id, $width, $height);
	
	}

	function time_record($story_id, $user_id, $time)
	{
	
		return $this->story->time_record($story_id, $user_id, $time);
	
	}

	function add_story_child_part($user_id, $story_id, $parent_part_id, $display_order, $title, $body = false)
	{
	
		return $this->story->add_story_child_part($user_id, $story_id, $parent_part_id, $display_order, $title, $body);
	
	}

	function update_story_part($user_id, $story_id, $title_part_id, $title = false, $body = false)
	{
		return $this->story->update_story_part($user_id, $story_id, $title_part_id, $title, $body);
	
	}

	function add_edit($story_id, $user_id)
	{

		return $this->story->add_edit($story_id, $user_id);
	
	}

	function delete_story($story_id)
	{

		return $this->story->delete_story($story_id);
	
	}

	function create_story($user_id, $story_type, $story_title)
	{

		return $this->story->create_story($user_id, $story_type, $story_title);
	
	}

	function apply_collab($story_id, $owner_id, $collab_id)
	{

		return $this->story->apply_collab($story_id, $owner_id, $collab_id);
	
	}
	
	function story_upload_image($story_id, $user_id, $image)
	{
	
		return $this->upload_image($image, "story");
	
	}

	function get_story_types()
	{

		return $this->story->get_story_types();

	}	

	function check_user_rating($story_id, $user_id)
	{

		return $this->story->check_user_rating($story_id, $user_id);

	}

	function add_user_rating($story_id, $user_id, $rating)
	{

		return $this->story->add_user_rating($story_id, $user_id, $rating);

	}

	function update_user_rating($rating_id, $rating)
	{

		return $this->story->update_user_rating($rating_id, $rating);

	}

	function get_all_stories()
	{
		$stories = $this->story->get_all_stories();
		
		foreach ($stories as &$story) {
			
			$story["collabs"] = $this->story->get_story_collab($story["id"]);
		
		}

		return $stories;
	}

	function best_stories($id = false)
	{
		return $this->story->best_stories($id);
	}

	function check_best_story_of_month($month, $year)
	{
		return $this->story->check_best_story_of_month($month, $year);
	}

	function best_story_of_month($story_id, $month, $year, $timestamp, $user_id, $rank)
	{
		return $this->story->best_story_of_month($story_id, $month, $year, $timestamp, $user_id, $rank);
	}

	// function update_best_story_of_month($best_story_id, $story_id, $timestamp, $user_id)
	// {
	// 	return $this->story->update_best_story_of_month($best_story_id, $story_id, $timestamp, $user_id);
	// }

	function unpublish_chapter($story_id, $title_part_id)
	{
		$part = $this->story->get_part_detail($title_part_id);

		if( !$part["part_row"] || $part["part_row"]["story_id"] != $story_id)
			return false;

		return $this->story->unpublish_chapter($story_id, $title_part_id);
	}

	function publish_chapter($story_id, $title_part_id)
	{
		$part = $this->story->get_part_detail($title_part_id);

		if( !$part["part_row"] || $part["part_row"]["story_id"] != $story_id)
			return false;

		return $this->story->publish_chapter($story_id, $title_part_id);
	}

	function non_member_invite($story_id, $email, $role)
	{
		return $this->story->non_member_invite($story_id, $email, $role);
	}

	function update_story_editor($story_id, $user_id)
	{
		return $this->story->update_story_editor($story_id, $user_id);
	}

	function clear_story_editor($story_id)
	{
		return $this->story->clear_story_editor($story_id);
	}
}