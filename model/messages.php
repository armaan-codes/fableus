<?php
class MessagesModel extends BaseModel 
{
	function project_chat_room($user_id, $member_id) {
		$sql = "SELECT
					crm1.room_id		AS 'room_id'
				FROM chat_room cr, chat_room_member crm1, chat_room_member crm2
				WHERE crm1.room_id = crm2.room_id
				AND crm1.id != crm2.id
				AND crm1.member_id = ? AND crm2.member_id = ?
				AND cr.room_id = crm1.room_id
				AND cr.chat_type = ?";
		return $this->getRow($sql, array($user_id, $member_id, CHAT_PROJECT));
	}

	function create_chat_room($user_id, $member_id, $chat_type) {
		$this->beginTransaction();

		$sql = "INSERT INTO chat_room ( user_id, chat_type, ts_created ) VALUES ( ?,?, NOW() )";
		$room_id = $this->create($sql, array( $user_id, $chat_type ));
		$members = array($user_id, $member_id);
		foreach ($members as $member) {
			$sql = "INSERT INTO chat_room_member ( room_id, member_id, ts_registered ) VALUES ( ?,?, NOW() )";
			$this->create($sql, array( $room_id, $member ));
		}

		$this->endTransaction();
		return $room_id;
	}

	function latest_message($room_id) {
		$sql = "SELECT
					crm.id				AS 'id',
					crm.message			AS 'message',
					CASE
						WHEN TIMESTAMPDIFF(SECOND, crm.ts_message, NOW()) < 60 * 60 * 24 - 1
							THEN DATE_FORMAT(crm.ts_message, '%H:%i')
						WHEN TIMESTAMPDIFF(SECOND, crm.ts_message, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
							THEN DATE_FORMAT(crm.ts_message, '%a')
						WHEN TIMESTAMPDIFF(SECOND, crm.ts_message, NOW()) >= 60 * 60 * 24 * 7
							THEN DATE_FORMAT(crm.ts_message, '%d-%m')
					END AS 'time'
				FROM chat_room_message crm
				WHERE crm.room_id = ?
				ORDER BY crm.ts_message DESC";
		return $this->getRow($sql, array($room_id));
	}
##############################################################################################

	function last_message_id($room_id) {
		$sql = "SELECT id FROM chat_room_message WHERE room_id = ? ORDER BY ts_message DESC";
		return $this->getRow($sql, array($room_id))["id"];
	}

	function get_message($message_id) {
		$sql = "SELECT
					crm.id				AS 'id',
					crm.message			AS 'message',
					CASE
						WHEN TIMESTAMPDIFF(SECOND, crm.ts_message, NOW()) < 60 * 60 * 24 - 1
							THEN DATE_FORMAT(crm.ts_message, '%H:%i')
						WHEN TIMESTAMPDIFF(SECOND, crm.ts_message, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
							THEN DATE_FORMAT(crm.ts_message, '%a')
						WHEN TIMESTAMPDIFF(SECOND, crm.ts_message, NOW()) >= 60 * 60 * 24 * 7
							THEN DATE_FORMAT(crm.ts_message, '%d-%m')
					END AS 'time'
				FROM chat_room_message crm
				WHERE crm.id = ?
				ORDER BY crm.ts_message DESC";
		return $this->getRow($sql, array($message_id));
	}


	/*	Revised Code as on 08/06/2018	*/

	function new_message_count($room_id, $last_message_id)
	{

		$sql = "SELECT COUNT(*) FROM chat_room_message WHERE room_id = ? AND id > ?";
		
		return $this->getRow($sql, array($room_id, $last_message_id));
	
	}

	function chat_room_members($room_id)
	{

		$sql = "SELECT
				m.user_id			AS 'member_id',
				m.name				AS 'member_name',
				m.image				AS 'member_image'
			FROM chat_room_member crm, member m
			WHERE crm.room_id = ?
			AND m.user_id = crm.member_id
			GROUP BY crm.member_id";
		
		return $this->getAll($sql, array($room_id));
	
	}

	function room_messages($room_id)
	{
		
		$sql = "SELECT
				crm.id				AS 'id',
				m.user_id			AS 'member_id',
				m.name				AS 'member_name',
				m.image				AS 'member_image',
				crm.message			AS 'message',
				CASE
					WHEN TIMESTAMPDIFF(SECOND, crm.ts_message, NOW()) < 60 * 60 * 24 - 1
						THEN DATE_FORMAT(crm.ts_message, '%H:%i')
					WHEN TIMESTAMPDIFF(SECOND, crm.ts_message, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
						THEN DATE_FORMAT(crm.ts_message, '%a')
					WHEN TIMESTAMPDIFF(SECOND, crm.ts_message, NOW()) >= 60 * 60 * 24 * 7
						THEN DATE_FORMAT(crm.ts_message, '%d-%m')
				END AS 'time'
			FROM chat_room_message crm, member m
			WHERE crm.room_id = ?
			AND m.user_id = crm.member_id
			ORDER BY crm.ts_message ASC";

		return $this->getAll($sql, array($room_id));
	
	}

	function room_member($user_id, $room_id)
	{
		
		$sql = "SELECT * FROM chat_room_member WHERE room_id = ? AND member_id = ?";
		
		return $this->getRow($sql, array($room_id, $user_id));
	
	}


	function send_message($user_id, $room_id, $message)
	{
 	
 		$sql = "INSERT INTO chat_room_message ( room_id, member_id, message, ts_message ) VALUES (?,?,?, NOW())";
	
		return $this->create($sql, array($room_id, $user_id, $message));
	
	}

	function user_chat_rooms($user_id, $chat_type)
	{

		$sql = "SELECT
				crm.room_id		AS 'room_id'
			FROM chat_room_member crm, chat_room cr
			WHERE crm.member_id = ?
			AND cr.chat_type = ?
			AND cr.room_id = crm.room_id
			AND cr.deleted = 0
			GROUP BY crm.room_id";
		
		return $this->getAll($sql, array($user_id, $chat_type));
	
	}

	function story_from_room($room_id)
	{

		$sql = "SELECT * FROM story WHERE chat_room = ? AND deleted = 0";
		
		return $this->getRow($sql, array($room_id));
	
	}

	function room_last_message($room_id)
	{

		$sql = "SELECT
					crm.id				AS 'id',
					crm.message			AS 'message',
					CASE
						WHEN TIMESTAMPDIFF(SECOND, crm.ts_message, NOW()) < 60 * 60 * 24 - 1
							THEN DATE_FORMAT(crm.ts_message, '%H:%i')
						WHEN TIMESTAMPDIFF(SECOND, crm.ts_message, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
							THEN DATE_FORMAT(crm.ts_message, '%a')
						WHEN TIMESTAMPDIFF(SECOND, crm.ts_message, NOW()) >= 60 * 60 * 24 * 7
							THEN DATE_FORMAT(crm.ts_message, '%d-%m')
					END AS 'time',
					UNIX_TIMESTAMP(crm.ts_message)	AS 'timestamp'
				FROM chat_room_message crm
				WHERE crm.room_id = ?
				ORDER BY crm.ts_message DESC";
		
		return $this->getRow($sql, array($room_id));
	
	}
}