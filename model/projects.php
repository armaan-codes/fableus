<?php
class ProjectsModel extends BaseModel 
{
	function my_open_projects($user_id) {
		$sql = "SELECT 
					mp.market_id		AS 'id',
					m.user_id			AS 'owner_id',
					m.name				AS 'owner_name',
					m.image				AS 'owner_image',
					mp.title			AS 'title',
					mp.category			AS 'type',
					mp.range_from		AS 'range_from',
					mp.range_to			AS 'range_to',
					CASE
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) < 60
							THEN CONCAT(TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()), ' ', 'Seconds ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 AND 60 * 60 - 1
							THEN CONCAT(TIMESTAMPDIFF(MINUTE, mp.ts_registered, NOW()), ' ', 'Minutes ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
							THEN CONCAT(TIMESTAMPDIFF(HOUR, mp.ts_registered, NOW()), ' ', 'Hours ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
							THEN CONCAT(TIMESTAMPDIFF(DAY, mp.ts_registered, NOW()), ' ', 'Days ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 * 60 * 24 * 7 AND 60 * 60 * 24 * 7 * 30 - 1
							THEN CONCAT(TIMESTAMPDIFF(WEEK, mp.ts_registered, NOW()), ' ', 'Weeks ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) >= 60 * 60 * 24 * 7 * 30
							THEN CONCAT(TIMESTAMPDIFF(MONTH, mp.ts_registered, NOW()), ' ', 'Months ago')
					END AS 'time'
				FROM market_place mp, member m
				WHERE mp.user_id = ?
				AND mp.status = ?
				AND m.user_id = mp.user_id
				ORDER BY mp.ts_registered DESC
				LIMIT 5";
		return $this->getAll($sql, array($user_id, PROJECT_OPEN));
	}

	function my_award_projects($user_id) {
		$sql = "SELECT 
					mp.market_id		AS 'id',
					m.user_id			AS 'owner_id',
					m.name				AS 'owner_name',
					m.image				AS 'owner_image',
					mp.title			AS 'title',
					mp.category			AS 'type',
					mp.range_from		AS 'range_from',
					mp.range_to			AS 'range_to',
					CASE
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) < 60
							THEN CONCAT(TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()), ' ', 'Seconds ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 AND 60 * 60 - 1
							THEN CONCAT(TIMESTAMPDIFF(MINUTE, mp.ts_registered, NOW()), ' ', 'Minutes ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
							THEN CONCAT(TIMESTAMPDIFF(HOUR, mp.ts_registered, NOW()), ' ', 'Hours ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
							THEN CONCAT(TIMESTAMPDIFF(DAY, mp.ts_registered, NOW()), ' ', 'Days ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 * 60 * 24 * 7 AND 60 * 60 * 24 * 7 * 30 - 1
							THEN CONCAT(TIMESTAMPDIFF(WEEK, mp.ts_registered, NOW()), ' ', 'Weeks ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) >= 60 * 60 * 24 * 7 * 30
							THEN CONCAT(TIMESTAMPDIFF(MONTH, mp.ts_registered, NOW()), ' ', 'Months ago')
					END AS 'time'
				FROM market_place mp, member m
				WHERE mp.user_id = ?
				AND mp.status = ?
				AND m.user_id = mp.user_id
				ORDER BY mp.ts_registered DESC
				LIMIT 5";
		return $this->getAll($sql, array($user_id, PROJECT_AWARD));
	}

	function search_project($user_id, $string) {
		$sql = "SELECT 
					mp.market_id		AS 'id',
					mp.user_id			AS 'owner_id',
					m.name				AS 'owner_name',
					m.image				AS 'owner_image',
					mp.title			AS 'title',
					mp.category			AS 'type',
					mp.range_from		AS 'range_from',
					mp.range_to			AS 'range_to',
					CASE
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) < 60
							THEN CONCAT(TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()), ' ', 'Seconds ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 AND 60 * 60 - 1
							THEN CONCAT(TIMESTAMPDIFF(MINUTE, mp.ts_registered, NOW()), ' ', 'Minutes ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
							THEN CONCAT(TIMESTAMPDIFF(HOUR, mp.ts_registered, NOW()), ' ', 'Hours ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
							THEN CONCAT(TIMESTAMPDIFF(DAY, mp.ts_registered, NOW()), ' ', 'Days ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 * 60 * 24 * 7 AND 60 * 60 * 24 * 7 * 30 - 1
							THEN CONCAT(TIMESTAMPDIFF(WEEK, mp.ts_registered, NOW()), ' ', 'Weeks ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) >= 60 * 60 * 24 * 7 * 30
							THEN CONCAT(TIMESTAMPDIFF(MONTH, mp.ts_registered, NOW()), ' ', 'Months ago')
					END AS 'time',
					mp.status				AS 'status'
				FROM market_place mp, member m
				WHERE mp.title LIKE ?
				AND mp.user_id = ?
				AND mp.status != ?
				AND m.user_id = mp.user_id
				ORDER BY mp.ts_registered DESC";
		return $this->getAll($sql, array($string, $user_id, PROJECT_DELETE));
	}

	function load_open_projects($user_id, $last_id) {
		$sql = "SELECT 
					mp.market_id		AS 'id',
					m.user_id			AS 'owner_id',
					m.name				AS 'owner_name',
					m.image				AS 'owner_image',
					mp.title			AS 'title',
					mp.category			AS 'type',
					mp.range_from		AS 'range_from',
					mp.range_to			AS 'range_to',
					CASE
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) < 60
							THEN CONCAT(TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()), ' ', 'Seconds ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 AND 60 * 60 - 1
							THEN CONCAT(TIMESTAMPDIFF(MINUTE, mp.ts_registered, NOW()), ' ', 'Minutes ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
							THEN CONCAT(TIMESTAMPDIFF(HOUR, mp.ts_registered, NOW()), ' ', 'Hours ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
							THEN CONCAT(TIMESTAMPDIFF(DAY, mp.ts_registered, NOW()), ' ', 'Days ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 * 60 * 24 * 7 AND 60 * 60 * 24 * 7 * 30 - 1
							THEN CONCAT(TIMESTAMPDIFF(WEEK, mp.ts_registered, NOW()), ' ', 'Weeks ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) >= 60 * 60 * 24 * 7 * 30
							THEN CONCAT(TIMESTAMPDIFF(MONTH, mp.ts_registered, NOW()), ' ', 'Months ago')
					END AS 'time'
				FROM market_place mp, member m
				WHERE mp.user_id = ?
				AND mp.status = ?
				AND mp.market_id < ?
				AND m.user_id = mp.user_id
				ORDER BY mp.ts_registered DESC
				LIMIT 5";
		return $this->getAll($sql, array($user_id, PROJECT_OPEN, $last_id));
	}

	function load_award_projects($user_id, $last_id) {
		$sql = "SELECT 
					mp.market_id		AS 'id',
					m.user_id			AS 'owner_id',
					m.name				AS 'owner_name',
					m.image				AS 'owner_image',
					mp.title			AS 'title',
					mp.category			AS 'type',
					mp.range_from		AS 'range_from',
					mp.range_to			AS 'range_to',
					CASE
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) < 60
							THEN CONCAT(TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()), ' ', 'Seconds ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 AND 60 * 60 - 1
							THEN CONCAT(TIMESTAMPDIFF(MINUTE, mp.ts_registered, NOW()), ' ', 'Minutes ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
							THEN CONCAT(TIMESTAMPDIFF(HOUR, mp.ts_registered, NOW()), ' ', 'Hours ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
							THEN CONCAT(TIMESTAMPDIFF(DAY, mp.ts_registered, NOW()), ' ', 'Days ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) BETWEEN 60 * 60 * 24 * 7 AND 60 * 60 * 24 * 7 * 30 - 1
							THEN CONCAT(TIMESTAMPDIFF(WEEK, mp.ts_registered, NOW()), ' ', 'Weeks ago')
						WHEN TIMESTAMPDIFF(SECOND, mp.ts_registered, NOW()) >= 60 * 60 * 24 * 7 * 30
							THEN CONCAT(TIMESTAMPDIFF(MONTH, mp.ts_registered, NOW()), ' ', 'Months ago')
					END AS 'time'
				FROM market_place mp, member m
				WHERE mp.user_id = ?
				AND mp.status = ?
				AND mp.market_id < ?
				AND m.user_id = mp.user_id
				ORDER BY mp.ts_registered DESC
				LIMIT 5";
		return $this->getAll($sql, array($user_id, PROJECT_AWARD, $last_id));
	}


	function edit($user_id, $project_id, $category, $range_from, $range_to, $description) {
		$sql = "UPDATE market_place SET category = ?, range_from = ?, range_to = ?, description = ? WHERE market_id = ? AND user_id = ?";
		return $this->update($sql, array($category, $range_from, $range_to, $description, $project_id, $user_id));
	}

	function award_project($user_id, $project_id, $data) {
		$this->beginTransaction();
		$sql = "UPDATE market_place SET status = ? WHERE market_id = ? AND user_id = ?";
		if ($this->update($sql, array(PROJECT_AWARD, $project_id, $user_id))) {
			foreach ($data as $input) {
				if (isset($input["task_name"], $input["author_id"], $input["task_days"], $input["task_amount"]) && !empty($input["task_name"]) && !empty($input["author_id"]) && !empty($input["task_days"]) && !empty($input["task_amount"])) {
					$sql = "INSERT INTO market_projects ( market_id, user_id, task_name, task_days, task_amount ) VALUES ( ?,?,?,?,? )";
					$this->create($sql, array($project_id, $input["author_id"], $input["task_name"], $input["task_days"], $input["task_amount"]));
				}
			}
			$this->endTransaction();
			return true;
		}
		$this->endTransaction();
		return false;
	}

	function project_collab($project_id) {
		$sql = "SELECT
					m.user_id		AS 'id',
					m.name			AS 'name'
				FROM member m, market_projects mp
				WHERE mp.market_id = ?
				AND m.user_id = mp.user_id";
		return $this->getAll($sql, array($project_id));
	}

	function add_project_collab($story_id, $collab_id, $room_id) {
		$sql = "INSERT INTO story_collab ( story_id, user_id, role ) VALUES ( ?,?,? )";
		if($this->create($sql, array($story_id, $collab_id, ROLE_CONTRIBUTOR))) {
			$sql = "INSERT INTO chat_room_member ( room_id, member_id, ts_registered ) VALUES ( ?,?,NOW() )";
			return $this->create($sql, array($room_id, $collab_id));
		}
	}

	function award_confirm($story_id, $user_id) {
		$sql = "UPDATE story SET market_place = 1 WHERE story_id = ? AND user_id = ?";
		return $this->update($sql, array($story_id, $user_id));
	}
}