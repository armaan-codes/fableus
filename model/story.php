<?php
class StoryModel extends BaseModel
{
	/*	Revised Code as on 07/06/2018	*/

	function story_stats($story_id)
	{
	
		$sql = "SELECT * FROM vw_story_stats WHERE story_id = ?";
	
		return $this->getRow($sql, array($story_id));
	
	}

	function story_last_edit($story_id)
	{

		$sql = "SELECT
				m.user_id		AS 'id',
				m.name			AS 'name',
				CASE
					WHEN TIMESTAMPDIFF(SECOND, se.ts_edited, NOW()) < 60
						THEN CONCAT(TIMESTAMPDIFF(SECOND, se.ts_edited, NOW()), ' ', 'Seconds ago')
					WHEN TIMESTAMPDIFF(SECOND, se.ts_edited, NOW()) BETWEEN 60 AND 60 * 60 - 1
						THEN CONCAT(TIMESTAMPDIFF(MINUTE, se.ts_edited, NOW()), ' ', 'Minutes ago')
					WHEN TIMESTAMPDIFF(SECOND, se.ts_edited, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
						THEN CONCAT(TIMESTAMPDIFF(HOUR, se.ts_edited, NOW()), ' ', 'Hours ago')
					WHEN TIMESTAMPDIFF(SECOND, se.ts_edited, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
						THEN CONCAT(TIMESTAMPDIFF(DAY, se.ts_edited, NOW()), ' ', 'Days ago')
					WHEN TIMESTAMPDIFF(SECOND, se.ts_edited, NOW()) BETWEEN 60 * 60 * 24 * 7 AND 60 * 60 * 24 * 7 * 30 - 1
						THEN CONCAT(TIMESTAMPDIFF(WEEK, se.ts_edited, NOW()), ' ', 'Weeks ago')
					WHEN TIMESTAMPDIFF(SECOND, se.ts_edited, NOW()) >= 60 * 60 * 24 * 7 * 30
						THEN CONCAT(TIMESTAMPDIFF(MONTH, se.ts_edited, NOW()), ' ', 'Months ago')
				END AS 'time'
			FROM story_edit se, member m
			WHERE se.story_id = ?
			AND m.user_id = se.user_id
			ORDER BY se.ts_edited DESC";
		
		return $this->getRow($sql, array($story_id));
	
	}

	function story_last_view($story_id)
	{

		$sql = "SELECT
				CASE
					WHEN TIMESTAMPDIFF(SECOND, ts_viewed, NOW()) < 60
						THEN CONCAT(TIMESTAMPDIFF(SECOND, ts_viewed, NOW()), ' ', 'Seconds ago')
					WHEN TIMESTAMPDIFF(SECOND, ts_viewed, NOW()) BETWEEN 60 AND 60 * 60 - 1
						THEN CONCAT(TIMESTAMPDIFF(MINUTE, ts_viewed, NOW()), ' ', 'Minutes ago')
					WHEN TIMESTAMPDIFF(SECOND, ts_viewed, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
						THEN CONCAT(TIMESTAMPDIFF(HOUR, ts_viewed, NOW()), ' ', 'Hours ago')
					WHEN TIMESTAMPDIFF(SECOND, ts_viewed, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
						THEN CONCAT(TIMESTAMPDIFF(DAY, ts_viewed, NOW()), ' ', 'Days ago')
					WHEN TIMESTAMPDIFF(SECOND, ts_viewed, NOW()) BETWEEN 60 * 60 * 24 * 7 AND 60 * 60 * 24 * 7 * 30 - 1
						THEN CONCAT(TIMESTAMPDIFF(WEEK, ts_viewed, NOW()), ' ', 'Weeks ago')
					WHEN TIMESTAMPDIFF(SECOND, ts_viewed, NOW()) >= 60 * 60 * 24 * 7 * 30
						THEN CONCAT(TIMESTAMPDIFF(MONTH, ts_viewed, NOW()), ' ', 'Months ago')
				END AS 'time'
			FROM story_view
			WHERE story_id = ?
			ORDER BY ts_viewed DESC";
		
		return $this->getRow($sql, array($story_id));
	
	}

	function story_last_comment($story_id)
	{

		$sql = "SELECT
				CASE
					WHEN TIMESTAMPDIFF(SECOND, ts_commented, NOW()) < 60
						THEN CONCAT(TIMESTAMPDIFF(SECOND, ts_commented, NOW()), ' ', 'Seconds ago')
					WHEN TIMESTAMPDIFF(SECOND, ts_commented, NOW()) BETWEEN 60 AND 60 * 60 - 1
						THEN CONCAT(TIMESTAMPDIFF(MINUTE, ts_commented, NOW()), ' ', 'Minutes ago')
					WHEN TIMESTAMPDIFF(SECOND, ts_commented, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
						THEN CONCAT(TIMESTAMPDIFF(HOUR, ts_commented, NOW()), ' ', 'Hours ago')
					WHEN TIMESTAMPDIFF(SECOND, ts_commented, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
						THEN CONCAT(TIMESTAMPDIFF(DAY, ts_commented, NOW()), ' ', 'Days ago')
					WHEN TIMESTAMPDIFF(SECOND, ts_commented, NOW()) BETWEEN 60 * 60 * 24 * 7 AND 60 * 60 * 24 * 7 * 30 - 1
						THEN CONCAT(TIMESTAMPDIFF(WEEK, ts_commented, NOW()), ' ', 'Weeks ago')
					WHEN TIMESTAMPDIFF(SECOND, ts_commented, NOW()) >= 60 * 60 * 24 * 7 * 30
						THEN CONCAT(TIMESTAMPDIFF(MONTH, ts_commented, NOW()), ' ', 'Months ago')
				END AS 'time'
			FROM story_comment
			WHERE story_id = ?
			AND message <> ' '
			ORDER BY ts_commented DESC";
		
		return $this->getRow($sql, array($story_id));
	
	}

	function get_user_role($story_id, $user_id)
	{

		$sql = "SELECT role FROM story_collab WHERE story_id = ? AND user_id = ?";

		return $this->getRow($sql, array($story_id, $user_id));

	}

	function story_check($story_id)
	{	
		if(is_numeric($story_id)) {

			$sql = "SELECT * FROM story WHERE story_id = ? AND deleted = 0";
			
		} else {

			$sql = "SELECT * FROM story WHERE slug = ? AND deleted = 0";

		}
		
		return $this->getRow($sql, array($story_id));
	
	}

	function get_story($story_id)
	{

		$sql = "SELECT
				s.story_id				AS 'id',
				s.slug					AS 'slug',
				s.user_id				AS 'owner_id',
				m.name					AS 'owner_name',
				m.image					AS 'owner_image',
				s.type					AS 'type',
				s.title					AS 'title',
				s.chat_room				AS 'room_id',
				s.parent_story_id		AS 'parent_id',
				s.image 				AS 'image',
				s.image_width 			AS 'image_width',
				s.image_height 			AS 'image_height',
				CASE
					WHEN TIMESTAMPDIFF(SECOND, s.ts_created, NOW()) < 60
						THEN CONCAT(TIMESTAMPDIFF(SECOND, s.ts_created, NOW()), ' ', 'Seconds ago')
					WHEN TIMESTAMPDIFF(SECOND, s.ts_created, NOW()) BETWEEN 60 AND 60 * 60 - 1
						THEN CONCAT(TIMESTAMPDIFF(MINUTE, s.ts_created, NOW()), ' ', 'Minutes ago')
					WHEN TIMESTAMPDIFF(SECOND, s.ts_created, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
						THEN CONCAT(TIMESTAMPDIFF(HOUR, s.ts_created, NOW()), ' ', 'Hours ago')
					WHEN TIMESTAMPDIFF(SECOND, s.ts_created, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
						THEN CONCAT(TIMESTAMPDIFF(DAY, s.ts_created, NOW()), ' ', 'Days ago')
					WHEN TIMESTAMPDIFF(SECOND, s.ts_created, NOW()) BETWEEN 60 * 60 * 24 * 7 AND 60 * 60 * 24 * 7 * 30 - 1
						THEN CONCAT(TIMESTAMPDIFF(WEEK, s.ts_created, NOW()), ' ', 'Weeks ago')
					WHEN TIMESTAMPDIFF(SECOND, s.ts_created, NOW()) >= 60 * 60 * 24 * 7 * 30
						THEN CONCAT(TIMESTAMPDIFF(MONTH, s.ts_created, NOW()), ' ', 'Months ago')
				END AS 'time_created',
				CASE
					WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) < 60
						THEN CONCAT(TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()), ' ', 'Seconds ago')
					WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) BETWEEN 60 AND 60 * 60 - 1
						THEN CONCAT(TIMESTAMPDIFF(MINUTE, s.ts_updated, NOW()), ' ', 'Minutes ago')
					WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
						THEN CONCAT(TIMESTAMPDIFF(HOUR, s.ts_updated, NOW()), ' ', 'Hours ago')
					WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
						THEN CONCAT(TIMESTAMPDIFF(DAY, s.ts_updated, NOW()), ' ', 'Days ago')
					WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) BETWEEN 60 * 60 * 24 * 7 AND 60 * 60 * 24 * 7 * 30 - 1
						THEN CONCAT(TIMESTAMPDIFF(WEEK, s.ts_updated, NOW()), ' ', 'Weeks ago')
					WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) >= 60 * 60 * 24 * 7 * 30
						THEN CONCAT(TIMESTAMPDIFF(MONTH, s.ts_updated, NOW()), ' ', 'Months ago')
				END AS 'time_updated',
				s.publish			AS 'publish'
			FROM story s, member m
			WHERE s.story_id = ?
			AND s.deleted = 0
			AND m.user_id = s.user_id";

		return $this->getRow($sql, array($story_id));
	
	}

	function get_story_collab($story_id)
	{

		$sql = "SELECT
					m.user_id		AS 'id',
					m.name 			AS 'name',
					m.image 		AS 'image',
					m.first_name	AS 'first_name',
					m.last_name		AS 'last_name',
					m.bio			AS 'bio'
				FROM story_collab sc, member m
				WHERE sc.story_id = ?
				AND sc.role = ?
				AND sc.active = 1
				AND m.user_id = sc.user_id";
		
		return $this->getAll($sql, array($story_id, ROLE_CONTRIBUTOR));
	
	}

	function get_toc($story_id, $view = false)
	{

		$sql = "SELECT * FROM story_part WHERE story_id = ? AND indicator = ?";

		$parts = $this->getAll($sql, array($story_id, INDICATOR_TITLE));

		foreach ($parts as &$item) {
		
			$part_index = array_values($this->get_part_words($item['part_id']));
		
			$item['title'] = implode(" ", $part_index);
		
		}
		
		reset($parts);

		$title_part = current($parts);

		$toc = $this->build_toc_tree($parts, $story_id, $view);

		return array('toc' => $toc, 'title_part' => $title_part);
	
	}

	function get_part_words($part_id)
	{
		$part = $this->get_part_detail($part_id);

		$part_row = $part['part_row'];
		$part_words = $part['part_words'];

		if(!$part_row || empty($part_row) || !$part_words || empty($part_words))
			return array();

		$word_index = array();
		foreach ($part_words as $part_word) {
			$word_index[$part_word['word_id']] = $part_word['word'];
		}

		$word_pos = explode(",", $part_row['words_position']);

		return array_intersect_key($word_index, array_flip($word_pos));
	}

	function build_toc_tree(array &$parts, $parent_part_id, $view = false)
	{
	
		$branch = array();

		if($view) {

			foreach ($parts as &$part) {

				if($part["publish"] == 1) {

					if ($part["parent_part_id"] == $parent_part_id) {
			
						$part_entry = array(
			
							TOC_DATA_KEY => $part,
			
							TOC_CHILDREN_KEY => array()
			
						);

						if ($children = $this->build_toc_tree($parts, $part['part_id'], $view)) {

							$part_entry[TOC_CHILDREN_KEY] = $children;

						}

						$branch[(int)$part['display_order']] = $part_entry;

						unset($part);

					}
					
				}

			}

		} else {

			foreach ($parts as &$part) {

				if ($part["parent_part_id"] == $parent_part_id) {
		
					$part_entry = array(
		
						TOC_DATA_KEY => $part,
		
						TOC_CHILDREN_KEY => array()
		
					);

					if ($children = $this->build_toc_tree($parts, $part['part_id'])) {

						$part_entry[TOC_CHILDREN_KEY] = $children;

					}

					$branch[(int)$part['display_order']] = $part_entry;

					unset($part);

				}

			}

		}	

		return $branch;

	}

	function get_title($story_id, $title_part_id)
	{
	
		return $this->get_part_words_details($title_part_id);
	
	}

	function get_part_words_details($part_id)
	{
		
		$part = $this->get_part_detail($part_id);

		$part_row = $part["part_row"];
		
		$part_words = $part["part_words"];

		if(!$part_row || empty($part_row['words_position']) || !$part_words || empty($part_words))
			return array();

		$word_index = array();

		foreach ($part_words as $part_word) {
		
			$word_index[$part_word['word_id']] = $part_word;
		
		}
		
		$word_pos = explode(",", $part_row['words_position']);
		
		$words = array();

		foreach ($word_pos as $index) {
		
			array_push($words, $word_index[$index]);
		
		}

		return $words;
	
	}

	function get_part_detail($part_id)
	{
		
		$part_row = $this->get_part_row($part_id);
		
		$sql = "SELECT
				spw.word_id		AS 'word_id',
				m.user_id		AS 'editor_id',
				m.name			AS 'editor_name',
				spw.word		AS 'word'
			FROM story_part_word spw, member m
			WHERE spw.part_id = ?
			AND m.user_id = spw.user_id";

		$part_words = $this->getAll($sql, array($part_id));
		
		return array("part_row" => $part_row, "part_words" => $part_words);
	
	}

	function get_part_row($part_id)
	{

		$sql = "SELECT
				sp.part_id				AS 'part_id',
				sp.story_id				AS 'story_id',
				sp.indicator			AS 'indicator',
				sp.latest_version_id	AS 'latest_version_id',
				sp.parent_part_id		AS 'parent_part_id',
				sp.display_order		AS 'display_order',
				sp.title_part_id		AS 'title_part_id',
				m.user_id				AS 'editor_id',
				m.name					AS 'editor_name',
				spv.words_position		AS 'words_position',
				sp.publish				AS 'publish'
			FROM story_part sp, story_part_version spv, member m
			WHERE sp.part_id = ?
			AND spv.part_id = sp.part_id
			AND spv.version_id = sp.latest_version_id
			AND m.user_id = spv.user_id";
		
		return $this->getRow($sql, array($part_id));
	
	}

	function get_body($story_id, $title_part_id)
	{

		$sql = "SELECT * FROM story_part WHERE story_id = ? AND title_part_id = ? AND indicator = ?";
		
		$part = $this->getRow($sql, array($story_id, $title_part_id, INDICATOR_BODY));

		if (empty($part))
			return false;

		return $this->get_part_words_details($part["part_id"]);
	
	}

	function story_comments($story_id)
	{

		$sql = "SELECT
				sc.id		AS 'id',
				sc.user_id	AS 'user_id',
				m.name		AS 'user_name',
				m.image		AS 'user_image',
				sc.message	AS 'message',
				CASE
					WHEN TIMESTAMPDIFF(SECOND, sc.ts_commented, NOW()) < 60
						THEN CONCAT(TIMESTAMPDIFF(SECOND, sc.ts_commented, NOW()), ' ', 'Seconds ago')
					WHEN TIMESTAMPDIFF(SECOND, sc.ts_commented, NOW()) BETWEEN 60 AND 60 * 60 - 1
						THEN CONCAT(TIMESTAMPDIFF(MINUTE, sc.ts_commented, NOW()), ' ', 'Minutes ago')
					WHEN TIMESTAMPDIFF(SECOND, sc.ts_commented, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
						THEN CONCAT(TIMESTAMPDIFF(HOUR, sc.ts_commented, NOW()), ' ', 'Hours ago')
					WHEN TIMESTAMPDIFF(SECOND, sc.ts_commented, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
						THEN CONCAT(TIMESTAMPDIFF(DAY, sc.ts_commented, NOW()), ' ', 'Days ago')
					WHEN TIMESTAMPDIFF(SECOND, sc.ts_commented, NOW()) BETWEEN 60 * 60 * 24 * 7 AND 60 * 60 * 24 * 7 * 30 - 1
						THEN CONCAT(TIMESTAMPDIFF(WEEK, sc.ts_commented, NOW()), ' ', 'Weeks ago')
					WHEN TIMESTAMPDIFF(SECOND, sc.ts_commented, NOW()) >= 60 * 60 * 24 * 7 * 30
						THEN CONCAT(TIMESTAMPDIFF(MONTH, sc.ts_commented, NOW()), ' ', 'Months ago')
				END AS 'time'
			FROM story_comment sc, member m
			WHERE sc.story_id = ?
			AND m.user_id = sc.user_id
			AND sc.message <> ''
			ORDER BY sc.ts_commented DESC";

		return $this->getAll($sql, array($story_id));
	
	}

	function story_active_contributors($story_id)
	{

		$sql = "SELECT * FROM story s, story_part sp WHERE s.story_id = ? AND sp.story_id = s.story_id AND indicator = ? AND deleted = 0";
		
		$result = $this->getAll($sql, array($story_id, INDICATOR_BODY));

		$collabs_id = array();
		
		foreach ($result as $part) {
		
			$sql = "SELECT words_position AS 'position' FROM story_part_version WHERE version_id = ?";
		
			$string = $this->getRow($sql, array($part["latest_version_id"]));
		
			$position_array = explode(",", $string["position"]);

			foreach ($position_array as $position) {
		
				$sql = "SELECT
							user_id		AS 'id'
						FROM story_part_word
						WHERE word_id = ?";
		
				$collab_id = $this->getRow($sql, array($position));
		
				$diff = array_diff($collab_id, $collabs_id);
		
				if ($diff)
					array_push($collabs_id, $collab_id["id"]);
				
			}

		}

		return $collabs_id;
	
	}

	function word_analysis($story_id)
	{

		$sql = "SELECT part_id FROM story_part WHERE story_id = ? AND indicator = ?";
		
		$part_ids = $this->getAll($sql, array($story_id, INDICATOR_BODY));
	
		$sql = "SELECT
					spw.user_id				AS 'user_id',
					m.name					AS 'user_name',
					COUNT(spw.word_id)		AS 'no_words'
				FROM story_part_word spw, member m
				WHERE (";
		
		$data = array();
		
		foreach ($part_ids as $id) {
		
			$sql .= "spw.part_id = ? OR ";
		
			array_push($data, $id["part_id"]);
		
		}

		$sql = substr($sql, 0, -3);
		
		$sql .= ") AND m.user_id = spw.user_id GROUP BY spw.user_id";

		return $this->getAll($sql, $data);
	
	}

	function time_analysis($story_id)
	{

		$sql = "SELECT
				m.user_id			AS 'user_id',
				m.name				AS 'user_name',
				tr.ts_spent			AS 'ts_spent'
			FROM time_record tr, member m
			WHERE tr.story_id = ?
			AND m.user_id = tr.user_id
			GROUP BY tr.user_id";
		
		return $this->getAll($sql, array($story_id));
	
	}

	function add_view($story_id)
	{
		
		$sql = "SELECT * FROM story_view WHERE story_id = ? AND visitor_ip = ?";
		
		if (!$this->getRow($sql, array($story_id, $_SERVER["REMOTE_ADDR"]))) {
		
			$sql = "INSERT INTO story_view ( story_id, visitor_ip, ts_viewed ) VALUES ( ?,?, NOW() )";
		
			return $this->create($sql, array($story_id, $_SERVER["REMOTE_ADDR"]));
		
		}
		
		return false;
	
	}

	function last_message_id($room_id)
	{

		$sql = "SELECT id FROM chat_room_message WHERE room_id = ? ORDER BY ts_message DESC";
		
		return $this->getRow($sql, array($room_id))["id"];
	
	}

	function add_comment($story_id, $user_id, $message)
	{
		
		$sql = "INSERT INTO story_comment ( story_id, user_id, message, ts_commented ) VALUES ( ?,?,?, NOW() )";
		
		return $this->create($sql, array($story_id, $user_id, $message));
	
	}

	function get_comment($user_id, $comment_id)
	{

		$sql = "SELECT * FROM story_comment WHERE user_id = ? AND id = ?";
		
		return $this->getRow($sql, array($user_id, $comment_id));
	
	}

	function update_comment($comment_id, $message)
	{
		
		$sql = "UPDATE story_comment SET message = ? WHERE id = ?";
		
		return $this->update($sql, array($message, $comment_id));
	
	}

	function publish_story($story_id)
	{
		
		$sql = "UPDATE story SET publish = ? WHERE story_id = ?";
		
		return $this->update($sql, array(PUBLISH, $story_id));
	
	}

	function unpublish_story($story_id)
	{
		
		$sql = "UPDATE story SET publish = ? WHERE story_id = ?";
		
		return $this->update($sql, array(DEVELOPMENT, $story_id));
	
	}

	function invite_check($story_id, $owner_id, $collab_id)
	{
		$sql = "SELECT *
				FROM story_collab_invite
				WHERE story_id = ? AND owner_id = ? AND collab_id = ? AND status = ?";
		
		return $this->getRow($sql, array($story_id, $owner_id, $collab_id, REQUEST_PENDING));
	
	}

	function apply_check($story_id, $owner_id, $collab_id)
	{

		$sql = "SELECT *
				FROM story_collab_apply
				WHERE story_id = ? AND owner_id = ? AND collab_id = ? AND status = ?";
		
		return $this->getRow($sql, array($story_id, $owner_id, $collab_id, REQUEST_PENDING));
	
	}

	function invite_collab($story_id, $owner_id, $collab_id)
	{

		$sql = "INSERT INTO story_collab_invite ( story_id, owner_id, collab_id, status, ts_requested ) VALUES ( ?,?,?,?, NOW() )";
		
		return $this->create($sql, array($story_id, $owner_id, $collab_id, REQUEST_PENDING));
	
	}

	function invite_read_check($story_id, $owner_id, $collab_id)
	{
		$sql = "SELECT *
				FROM story_collab_read_invite
				WHERE story_id = ? AND owner_id = ? AND collab_id = ? AND status = ?";
		
		return $this->getRow($sql, array($story_id, $owner_id, $collab_id, REQUEST_PENDING));
	
	}

	function invite_read_collab($story_id, $owner_id, $collab_id)
	{

		$sql = "INSERT INTO story_collab_read_invite ( story_id, owner_id, collab_id, status, ts_requested ) VALUES ( ?,?,?,?, NOW() )";
		
		return $this->create($sql, array($story_id, $owner_id, $collab_id, REQUEST_PENDING));
	
	}

	function get_user_vote($user_id, $story_id)
	{

		$sql = "SELECT * FROM story_vote WHERE user_id = ? AND story_id = ?";
		
		return $this->getRow($sql, array($user_id, $story_id));
	
	}

	function up_vote($user_id, $story_id)
	{

		$sql = "INSERT INTO story_vote ( story_id, user_id, ts_voted ) VALUES ( ?,?, NOW() )";
		
		return $this->create($sql, array($story_id, $user_id));
	
	}

	function time_record($story_id, $user_id, $time)
	{

		$sql = "SELECT * FROM time_record WHERE story_id = ? AND user_id = ?";
		
		if(!$this->getRow($sql, array($story_id, $user_id))) {
		
			$sql = "INSERT INTO time_record ( story_id, user_id, ts_spent ) VALUES ( ?,?,? )";
		
			return $this->create($sql, array($story_id, $user_id, $time));
		
		}

		$sql = "UPDATE time_record SET ts_spent = ts_spent + ? WHERE story_id = ? AND user_id = ?";
		
		return $this->update($sql, array($time, $story_id, $user_id));
	
	}

	function story_image_upload($story_id, $user_id, $image, $width, $height)
	{

		$this->beginTransaction();

		$sql = "INSERT INTO story_edit ( story_id, user_id, ts_edited ) VALUES ( ?,?, NOW() )";
		
		$this->create($sql, array($story_id, $user_id));

		$sql = "UPDATE story SET image = ?, image_width = ?, image_height = ?, ts_updated = NOW() WHERE story_id = ?";
		
		$update = $this->update($sql, array($image, $width, $height, $story_id));

		$this->endTransaction();
		
		return $update;
	
	}
	
	function story_image_resize($story_id, $user_id, $width, $height)
	{

		$this->beginTransaction();

		$sql = "INSERT INTO story_edit ( story_id, user_id, ts_edited ) VALUES ( ?,?, NOW() )";
		
		$this->create($sql, array($story_id, $user_id));

		$sql = "UPDATE story SET image_width = ?, image_height = ?, ts_updated = NOW() WHERE story_id = ?";
		
		$update = $this->update($sql, array($width, $height, $story_id));

		$this->endTransaction();
		
		return $update;
	
	}

	function add_story_child_part($user_id, $story_id, $parent_part_id, $display_order, $title, $body = false)
	{

		$this->beginTransaction();

		$title_part_id = $this->create_story_part($user_id, $story_id, $title, INDICATOR_TITLE, $parent_part_id, $display_order);

		if(empty($body)) {
		
			$this->endTransaction();
		
			return array( "title_part_id" => $title_part_id );
		
		}

		$body_part_id = $this->create_story_part($user_id, $story_id, $body, INDICATOR_BODY, $parent_part_id, $display_order, $title_part_id);

		$this->endTransaction();
		
		return array( "title_part_id" => $title_part_id, "body_part_id" => $body_part_id);
	
	}

	function create_story_part($user_id, $story_id, $text, $indicator = false, $parent_part_id = false, $display_order = false, $title_part_id = false)
	{

		$this->beginTransaction();

		$sql = "INSERT INTO story_part ( story_id";
		
		$data = array($story_id);

		$parameters = array(
		
			'indicator' => $indicator,
		
			'parent_part_id' => $parent_part_id,
		
			'display_order' => $display_order,
		
			'title_part_id' => $title_part_id
		
		);

		foreach ($parameters as $key => $value) {
		
			if ($value) {
		
				$sql .= ", " . $key;
		
				array_push($data, $value);
		
			}
		
		}

		$sql .= " ) VALUES ( ". implode(',', array_fill(0, count($data), '?')) ." )";
		
		$part_id = $this->create($sql, $data);
		
		$words = string_to_array($text);
		
		$words_position = array();

		foreach ($words as $word) {
		
			$word_id = $this->add_part_word($part_id, $user_id, $word);
		
			array_push($words_position, $word_id);
		
		}

		$position = implode( ",", $words_position );
		
		$part_version_id = $this->add_part_version($part_id, $user_id, $position);

		$this->endTransaction();
		
		return $part_id;
	
	}

	function add_part_word($part_id, $user_id, $word)
	{
	
		$this->beginTransaction();
	
		$sql = "INSERT INTO story_part_word ( part_id, user_id, word ) VALUES ( ?,?,? )";
	
		$word_id = $this->create($sql, array($part_id, $user_id, $word));
	
		$this->endTransaction();
	
		return $word_id;
	
	}

	function add_part_version($part_id, $user_id, $words_position)
	{
	
		$this->beginTransaction();

		$sql = "INSERT INTO story_part_version ( part_id, user_id, words_position ) VALUES ( ?,?,? )";
	
		$version_id = $this->create($sql, array($part_id, $user_id, $words_position));

		$sql = "UPDATE story_part SET latest_version_id = ? WHERE part_id = ?";
	
		$this->update($sql, array($version_id, $part_id));

		$this->endTransaction();
	
		return $version_id;
	
	}

	function update_story_part($user_id, $story_id, $title_part_id, $title = false, $body = false)
	{

		$this->beginTransaction();

		$sql = "SELECT * FROM story_part WHERE story_id = ? AND title_part_id = ? AND indicator = ?";
		
		$body_part = $this->getRow($sql, array($story_id, $title_part_id, INDICATOR_BODY));

		if ($title)
			$this->update_part($user_id, $story_id, $title_part_id, $title);

		if ($body)
			$this->update_part($user_id, $story_id, $body_part["part_id"], $body);

		$this->endTransaction();

	}

	function update_part($user_id, $story_id, $part_id, $new_text_string)
	{
		
		$new_part =  string_to_array($new_text_string);
		$new_indices = array();
	
		$part_words_indexed = $this->get_part_words($part_id, $story_id);
	
		$part_words = array_values($part_words_indexed);
		
		if(strcmp(implode(" ", $part_words), $new_text_string) === 0)
			return false;
		
		$old_indices = array_keys($part_words_indexed);
	
		$part_diff = index_diff($part_words, $new_part);

		$this->beginTransaction();
		
		foreach ($part_diff as $index => $meta) {
	
			if(!is_array($meta)) {
	
				array_push($new_indices, array_shift($old_indices));
	
				continue;
	
			}

			if(isset($meta[INSERT_IDENTIFIER]) && !empty($meta[INSERT_IDENTIFIER])) {
	
				foreach ($meta[INSERT_IDENTIFIER] as $in_word) {
	
					$new_index = $this->add_part_word($part_id, $user_id, $in_word);
	
					array_push($new_indices, (int)$new_index);
	
				}
	
			}

			if(isset($meta[DELETE_IDENTIFIER]) && !empty($meta[DELETE_IDENTIFIER])) {
	
				foreach ($meta[DELETE_IDENTIFIER] as $in_word) {
	
					array_shift($old_indices);
	
				}
	
			}
		}
	
		$position = implode( ",", $new_indices );
	
		$version_id = $this->add_part_version($part_id, $user_id, $position);

		$this->endTransaction();
	
		return $version_id;
	
	}

	function add_edit($story_id, $user_id)
	{

		$this->beginTransaction();

		$sql = "INSERT INTO story_edit ( story_id, user_id, ts_edited ) VALUES ( ?,?, NOW() )";
		
		$this->create($sql, array($story_id, $user_id));
		
		$sql = "UPDATE story SET ts_updated = NOW() WHERE story_id = ?";
		
		$update = $this->update($sql, array($story_id));

		$this->endTransaction();
		
		return $update;
	
	}

	function delete_story($story_id)
	{
	
		$sql = "DELETE story.*, chat_room.* from story, chat_room WHERE story.chat_room=chat_room.room_id AND story.story_id= ?";
		
	
		return $this->update($sql, array($story_id));
	
	}

	function create_story($user_id, $story_type, $story_title, $story_body = false)
	{

		$this->beginTransaction();

		$story_id = $this->create_story_id($user_id, $story_type, $story_title);

		$title_part_id = $this->create_story_part($user_id, $story_id, $story_title, INDICATOR_TITLE, $story_id);

		$intro_part_id = $this->create_story_part($user_id, $story_id, "Introduction", INDICATOR_TITLE, $story_id);

		$body_part_id = $this->create_story_part($user_id, $story_id, $story_body, INDICATOR_BODY, $story_id, false, $intro_part_id);
		
		$sql = "INSERT INTO story_vote ( story_id, user_id, ts_voted ) VALUES ( ?,?, NOW() )";
		
		$this->create($sql, array($story_id, $user_id));

		$sql = "INSERT INTO story_comment ( story_id, user_id, message, ts_commented ) VALUES ( ?,?,?, NOW() )";
		
		$this->create($sql, array($story_id, $user_id, " "));

		$sql = "INSERT INTO story_edit ( story_id, user_id, ts_edited ) VALUES ( ?,?, NOW() )";
		
		$this->create($sql, array($story_id, $user_id));

		$sql = "INSERT INTO chat_room ( user_id, chat_type, ts_created ) VALUES ( ?,?,NOW() )";
		
		if ($room_id = $this->create($sql, array($user_id, CHAT_STORY))) {
		
			$sql = "INSERT INTO chat_room_member ( room_id, member_id, ts_registered ) VALUES ( ?,?, NOW() )";
		
			$this->create($sql, array($room_id, $user_id));

			$sql = "UPDATE story SET chat_room = ? WHERE story_id = ?";
		
			$this->update($sql, array($room_id, $story_id));

			$sql = "INSERT INTO chat_room_message ( room_id, member_id, message, ts_message ) VALUES ( ?,?,?, NOW() )";

			$this->create($sql, array($room_id, $user_id, "Story Created."));
		}

		$this->endTransaction();

		return $this->story_check($story_id);
	
	}

	function create_story_id($user_id, $story_type, $story_title)
	{

		$this->beginTransaction();

		$sql = "INSERT INTO story ( user_id, slug, type, title, ts_created, ts_updated ) VALUES ( ?,?,?,?, NOW(), NOW() )";
		
		$story_id = $this->create($sql, array($user_id, uniqid(), $story_type, $story_title));
		
		$sql = "INSERT INTO story_collab ( story_id, user_id, role ) VALUES ( ?,?,? )";
		
		$this->create($sql, array($story_id, $user_id, ROLE_OWNER));

		$this->endTransaction();
		
		return $story_id;
	
	}

	function apply_collab($story_id, $owner_id, $collab_id)
	{
	
		$sql = "INSERT INTO story_collab_apply ( story_id, owner_id, collab_id, status, ts_requested ) VALUES ( ?,?,?,?, NOW() )";
	
		return $this->create($sql, array($story_id, $owner_id, $collab_id, REQUEST_PENDING));
	
	}

	function get_story_types()
	{

		$sql = "SELECT * FROM story_type WHERE status = ? ";

		return $this->getAll($sql, array(1));

	}

	function check_user_rating($story_id, $user_id)
	{

		$sql = "SELECT * FROM story_ratings WHERE story_id = ? AND user_id = ?";

		return $this->getRow($sql, array($story_id, $user_id));

	}

	function add_user_rating($story_id, $user_id, $rating)
	{

		$sql = "INSERT INTO story_ratings ( story_id, user_id, rating ) VALUES ( ?, ?, ? )";

		return $this->create($sql, array($story_id, $user_id, $rating));

	}

	function update_user_rating($rating_id, $rating)
	{

		$sql = "UPDATE story_ratings SET rating = ? WHERE id = ?";

		return $this->update($sql, array($rating, $rating_id));

	}

	function story_rating($story_id)
	{

		$sql = "SELECT * FROM story_ratings WHERE story_id = ?";

		$ratings = $this->getAll($sql, array($story_id));

		$total_user_ratings = 0;

		foreach ($ratings as $rating) {
			
			$total_user_ratings += $rating["rating"];

		}

		if($total_user_ratings) {
			
			$average = $total_user_ratings / count($ratings);
			
			$before_decimal = substr($average, 0, strpos($average, '.') );

			$after_decimal = substr($average, strpos($average, '.') + 1, 1);

			if(!$after_decimal) {

				return $average;

			} elseif($after_decimal > 0 && $after_decimal <= 5) {

				return $before_decimal . '.5';

			} elseif($after_decimal > 5) {

				return $before_decimal + 1;

			}

		}

		return $total_user_ratings;

	}

	function get_all_stories($user_id = false, $filter = false)
	{
		
		$data = array(PUBLISH);
		
		$sql = "SELECT
					s.story_id		AS 'id',
					s.slug			AS 'slug',
					s.user_id		AS 'owner_id',
					m.name			AS 'owner_name',
					m.image			AS 'owner_image',
					s.type			AS 'type',
					s.title			AS 'title',
					s.image			AS 'image',
					CASE
						WHEN TIMESTAMPDIFF(SECOND, s.ts_created, NOW()) < 60
							THEN CONCAT(TIMESTAMPDIFF(SECOND, s.ts_created, NOW()), ' ', 'Seconds ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_created, NOW()) BETWEEN 60 AND 60 * 60 - 1
							THEN CONCAT(TIMESTAMPDIFF(MINUTE, s.ts_created, NOW()), ' ', 'Minutes ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_created, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
							THEN CONCAT(TIMESTAMPDIFF(HOUR, s.ts_created, NOW()), ' ', 'Hours ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_created, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
							THEN CONCAT(TIMESTAMPDIFF(DAY, s.ts_created, NOW()), ' ', 'Days ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_created, NOW()) BETWEEN 60 * 60 * 24 * 7 AND 60 * 60 * 24 * 7 * 30 - 1
							THEN CONCAT(TIMESTAMPDIFF(WEEK, s.ts_created, NOW()), ' ', 'Weeks ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_created, NOW()) >= 60 * 60 * 24 * 7 * 30
							THEN CONCAT(TIMESTAMPDIFF(MONTH, s.ts_created, NOW()), ' ', 'Months ago')
					END AS 'time_created',
					CASE
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) < 60
							THEN CONCAT(TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()), ' ', 'Seconds ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) BETWEEN 60 AND 60 * 60 - 1
							THEN CONCAT(TIMESTAMPDIFF(MINUTE, s.ts_updated, NOW()), ' ', 'Minutes ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
							THEN CONCAT(TIMESTAMPDIFF(HOUR, s.ts_updated, NOW()), ' ', 'Hours ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
							THEN CONCAT(TIMESTAMPDIFF(DAY, s.ts_updated, NOW()), ' ', 'Days ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) BETWEEN 60 * 60 * 24 * 7 AND 60 * 60 * 24 * 7 * 30 - 1
							THEN CONCAT(TIMESTAMPDIFF(WEEK, s.ts_updated, NOW()), ' ', 'Weeks ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) >= 60 * 60 * 24 * 7 * 30
							THEN CONCAT(TIMESTAMPDIFF(MONTH, s.ts_updated, NOW()), ' ', 'Months ago')
					END AS 'time_updated',
					UNIX_TIMESTAMP(s.ts_updated)		AS 'ts_updated'
				FROM story s, member m, vw_story_stats ss
				WHERE s.publish = ?
				AND s.deleted = 0
				AND m.user_id = s.user_id
				AND ss.story_id = s.story_id ";

			switch ($filter) {
				case 'novel':
					$filter = NOVEL;
					$sql .= "AND s.type = ? ORDER BY s.ts_created DESC";
					array_push($data, $filter);
					break;

				case 'screenplay':
					$filter = SCREENPLAY;
					$sql .= "AND s.type = ? ORDER BY s.ts_created DESC";
					array_push($data, $filter);
					break;

				case 'short-story':
					$filter = SHORT_STORY;
					$sql .= "AND s.type = ? ORDER BY s.ts_created DESC";
					array_push($data, $filter);
					break;

				case 'story':
					$filter = STORY;
					$sql .= "AND s.type = ? ORDER BY s.ts_created DESC";
					array_push($data, $filter);
					break;

				case 'recent-stories':
					$sql .= "ORDER BY s.ts_created DESC";
					break;

				case 'top-stories':
					$sql .= "ORDER BY ss.no_votes + ss.no_views + ss.no_comments DESC";
					break;
				
				default:
					$sql .= "ORDER BY s.ts_created DESC";
					break;
			}

		$sql .= " LIMIT 5";
		
		return $this->getAll($sql, $data);
	
	}

	function best_stories($id = false)
	{

		$best_stories = array();

		$sql = "SELECT year FROM story_best GROUP BY year ORDER BY year DESC";

		$years = $this->getAll($sql, array());

		foreach($years as $year) {

			$sql = "SELECT month FROM story_best WHERE year = ? GROUP BY month ORDER BY month DESC";

			$months = $this->getAll($sql, array($year['year']));

			foreach($months as $month) {

				$sql = "SELECT * FROM story_best WHERE month = ? AND year = ? ORDER BY rank ASC";

				$sql = "SELECT
					s.story_id		AS 'id',
					sb.rank			AS 'rank',
					s.slug			AS 'slug',
					s.user_id		AS 'owner_id',
					m.name			AS 'owner_name',
					m.image			AS 'owner_image',
					s.type			AS 'type',
					s.title			AS 'title',
					s.image			AS 'image'
				FROM story s, member m, story_best sb
				WHERE s.publish = ?
				AND s.deleted = 0
				AND m.user_id = s.user_id
				AND sb.story_id = s.story_id
				AND sb.month = ?
				AND sb.year = ?";

				$best_stories[$year['year']][\Carbon\Carbon::createFromFormat('m', $month["month"])->format('F')] = $this->getAll($sql, array(PUBLISH, $month["month"], $year['year']));

			}

		}

		return $best_stories;

	}

	function check_best_story_of_month($month, $year)
	{
		$sql = "SELECT * FROM story_best WHERE month = ? AND year = ? ORDER BY rank DESC";

		return $this->getAll($sql, array($month, $year));
	}

	function best_story_of_month($story_id, $month, $year, $timestamp, $user_id, $rank)
	{
		$sql = "INSERT INTO story_best ( story_id, month, year, timestamp, user_id, rank ) VALUES ( ?, ?, ?, ?, ?, ? )";

		return $this->create($sql, array($story_id, $month, $year, $timestamp, $user_id, $rank));
	}

	// function update_best_story_of_month($best_story_id, $story_id, $timestamp, $user_id)
	// {
	// 	$sql = "UPDATE story_best SET story_id = ?, timestamp = ?, user_id = ? WHERE id = ?";

	// 	return $this->update($sql, array($story_id, $timestamp, $user_id, $best_story_id));
	// }

	function unpublish_chapter($story_id, $title_part_id)
	{

		$sql = "UPDATE story_part SET publish = ? WHERE part_id = ? AND story_id = ?";

		return $this->update($sql, array(DEVELOPMENT, $title_part_id, $story_id));

	}

	function publish_chapter($story_id, $title_part_id)
	{

		$sql = "UPDATE story_part SET publish = ? WHERE part_id = ? AND story_id = ?";

		return $this->update($sql, array(PUBLISH, $title_part_id, $story_id));

	}

	function non_member_invite($story_id, $email, $role)
	{
		$sql = "INSERT INTO non_member_invite ( story_id, story_role, email ) VALUES ( ?,?,? )";

		return $this->create($sql, array($story_id, $role, $email));
	}

	function add_member_invites($user_id, $email)
	{

		$sql = "SELECT * FROM non_member_invite WHERE email = ?";

		$data = $this->getAll($sql, array($email));

		foreach ($data as $invite) {

			$story = $this->get_story($invite["story_id"]);
			
			if($invite["story_role"] == ROLE_CONTRIBUTOR) {

				$sql = "INSERT INTO story_collab_invite (story_id, owner_id, collab_id, status) VALUES ( ?,?,?,? )";

				$this->create($sql, array($story["id"], $story["owner_id"], $user_id, REQUEST_PENDING));

			} elseif($invite["story_role"] == ROLE_CONTRIBUTOR_READER) {

				$sql = "INSERT INTO story_collab_read_invite (story_id, owner_id, collab_id, status) VALUES ( ?,?,?,? )";

				$this->create($sql, array($story["id"], $story["owner_id"], $user_id, REQUEST_PENDING));

			}

		}

		return;

	}

	function update_story_editor($story_id, $user_id)
	{

		$sql = "UPDATE story SET editor = ? WHERE story_id = ?";

		return $this->update($sql, array($user_id, $story_id));

	}

	function clear_story_editor($story_id)
	{

		$sql = "UPDATE story SET editor = ? WHERE story_id = ?";

		return $this->update($sql, array(null, $story_id));

	}
}