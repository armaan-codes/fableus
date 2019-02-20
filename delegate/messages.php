<?php
class MessagesDelegate extends BaseDelegate
{
	function chat_room($chat_type, $user_id, $member_id = false){
		if ($chat_type = CHAT_PROJECT) {
			return $this->project_chat_room($user_id, $member_id);
		}
	}

	function project_chat_room($user_id, $member_id) {
		return $this->messages->project_chat_room($user_id, $member_id);
	}

	function create_chat_room($user_id, $member_id, $chat_type) {
		return $this->messages->create_chat_room($user_id, $member_id, $chat_type);
	}

	function latest_message($room_id) {
		return $this->messages->latest_message($room_id);
	}

	function last_message_id($room_id) {
		return $this->messages->last_message_id($room_id);
	}

##############################################################################

	function get_message($message_id) {
		return $this->messages->get_message($message_id);
	}

	/*	Revised Code as on 28/06/2018	*/

	function new_message_count($room_id, $last_message_id)
	{

		return $this->messages->new_message_count($room_id, $last_message_id);
	
	}

	function room_chat($user_id, $room_id)
	{
		$members = $this->messages->chat_room_members($room_id);

		$chat_title = "";
		
		foreach ($members as $member) {
		
			if ($member["member_id"] != $user_id) {
		
				if ($chat_title != "")
					$chat_title .= ", ";
				
				$chat_title .= $member["member_name"];
			
			}
		
		}
		
		$chat["title"] = trim($chat_title);
		
		$chat["messages"] = $this->messages->room_messages($room_id);
		
		return $chat;
	
	}

	function room_member($user_id, $room_id)
	{
	
		return $this->messages->room_member($user_id, $room_id);
	
	}

	function send_message($user_id, $room_id, $message)
	{

		return $this->messages->send_message($user_id, $room_id, $message);
	
	}

	function user_chat_rooms($user_id)
	{
		
		$story_rooms = $this->messages->user_chat_rooms($user_id, CHAT_STORY);

		$rooms = array();

		foreach ($story_rooms as $story_room) {

			$members = $this->messages->chat_room_members($story_room["room_id"]);

			array_shift($members);

			if($members) {

				$story = $this->messages->story_from_room($story_room["room_id"]);

				$room["room_id"] = $story_room["room_id"];
			
				$room["title"] = trim($story["title"]);
			
				$room["message"] = $this->messages->room_last_message($story_room["room_id"]);
			
				$room["unix_time"] = $room["message"]["timestamp"];

				$rooms[] = $room;

			}

		}

		return array( "story" => array_sort($rooms, "unix_time",SORT_DESC) );
	}
}