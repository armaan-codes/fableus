<?php
class MarketModel extends BaseModel 
{
	function get_open_projects($user_id) {
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
				WHERE mp.user_id != ?
				AND mp.status = ?
				AND m.user_id = mp.user_id
				ORDER BY mp.ts_registered DESC
				LIMIT 5";
		return $this->getAll($sql, array($user_id, PROJECT_OPEN));
	}

	function get_award_projects($user_id) {
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
				WHERE mp.user_id != ?
				AND mp.status = ?
				AND m.user_id = mp.user_id
				ORDER BY mp.ts_registered DESC
				LIMIT 5";
		return $this->getAll($sql, array($user_id, PROJECT_AWARD));
	}

	function project_bids_count($project_id) {
		$sql = "SELECT COUNT(*) AS 'no' FROM market_bids WHERE market_id = ?";
		return $this->getRow($sql, array($project_id))["no"];
	}

	function project_50_words($project_id) {
		$sql = "SELECT description FROM market_place WHERE market_id = ?";
		$desc = string_to_array(strip_tags($this->getRow($sql, array($project_id))["description"]));

		$desc_50 = array();
		for ($i=0; $i <= 50; $i++) { 
			array_push($desc_50, $desc[$i]);
		}
		return implode(" ", $desc_50);
	}

	function get_top_authors($user_id) {
		$sql = "SELECT
					m.user_id			AS 'id',
					m.name				AS 'name',
					m.image				AS 'image'
				FROM member m, vw_user_stats vus
				WHERE vus.user_id != ?
				AND vus.plan = ?
				AND m.user_id = vus.user_id
				AND m.active = 1
				ORDER BY vus.no_stories DESC
				LIMIT 5";
		return $this->getAll($sql, array($user_id, MEMBER_AUTHOR));
	}

	function get_author_stats($user_id) {
		$stats = array();

		$sql = "SELECT COUNT(*) AS 'no' FROM story WHERE user_id = ? AND publish = ? AND deleted = 0";
		$stats["stories"] = $this->getRow($sql, array($user_id, PUBLISH))["no"];

		$sql = "SELECT COUNT(*) AS 'no' FROM story_collab WHERE user_id = ? AND role = ? AND active = 1";
		$stats["collabs"] = $this->getRow($sql, array($user_id, ROLE_CONTRIBUTOR))["no"];

		$sql = "SELECT COUNT(*) AS 'no' FROM market_place WHERE user_id = ?";
		$stats["projects"] = $this->getRow($sql, array($user_id))["no"];
		
		return $stats;
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
				AND mp.user_id != ?
				AND mp.status != ?
				AND m.user_id = mp.user_id
				ORDER BY mp.ts_registered DESC";
		return $this->getAll($sql, array($string, $user_id, PROJECT_DELETE));
	}

	function search_authors($user_id, $author) {
		$sql = "SELECT
					user_id,
					name,
					image
				FROM member
				WHERE (name LIKE ? OR email LIKE ?)
				AND user_id != ?
				AND plan = ?";
		return $this->getAll($sql, array($author, $author, $user_id, MEMBER_AUTHOR));
	}

	function create_project($user_id, $title, $category, $description, $range_from, $range_to) {
		$sql = "INSERT INTO market_place ( user_id, category, title, description, range_from, range_to, ts_registered ) VALUES ( ?,?,?,?,?,?, NOW() )";
		return $this->create($sql, array($user_id, $category, $title, $description, $range_from, $range_to));
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
				WHERE mp.user_id != ?
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
				WHERE mp.user_id != ?
				AND mp.status = ?
				AND mp.market_id < ?
				AND m.user_id = mp.user_id
				ORDER BY mp.ts_registered DESC
				LIMIT 5";
		return $this->getAll($sql, array($user_id, PROJECT_AWARD, $last_id));
	}

	function get_project($project_id) {
		$sql = "SELECT
					mp.market_id			AS 'id',
					mp.user_id				AS 'owner_id',
					m.name					AS 'owner_name',
					m.image					AS 'owner_image',
					mp.title				AS 'title',
					mp.category				AS 'category',
					mp.description			AS 'description',
					mp.range_from			AS 'range_from',
					mp.range_to				AS 'range_to',
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
				WHERE mp.market_id = ?
				AND m.user_id = mp.user_id
				ORDER BY mp.ts_registered DESC";
		return $this->getRow($sql, array($project_id));
	}

	function get_bids($project_id) {
		$sql = "SELECT
					mb.bid_id		AS 'id',
					mb.market_id	AS 'project_id',
					m.user_id		AS 'bidder_id',
					m.name			AS 'bidder_name',
					m.image			AS 'bidder_image',
					mb.amount		AS 'bid_amount',
					mb.proposal		AS 'bid_proposal',
					CASE
						WHEN TIMESTAMPDIFF(SECOND, mb.ts_registered, NOW()) < 60
							THEN CONCAT(TIMESTAMPDIFF(SECOND, mb.ts_registered, NOW()), ' ', 'Seconds ago')
						WHEN TIMESTAMPDIFF(SECOND, mb.ts_registered, NOW()) BETWEEN 60 AND 60 * 60 - 1
							THEN CONCAT(TIMESTAMPDIFF(MINUTE, mb.ts_registered, NOW()), ' ', 'Minutes ago')
						WHEN TIMESTAMPDIFF(SECOND, mb.ts_registered, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
							THEN CONCAT(TIMESTAMPDIFF(HOUR, mb.ts_registered, NOW()), ' ', 'Hours ago')
						WHEN TIMESTAMPDIFF(SECOND, mb.ts_registered, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
							THEN CONCAT(TIMESTAMPDIFF(DAY, mb.ts_registered, NOW()), ' ', 'Days ago')
						WHEN TIMESTAMPDIFF(SECOND, mb.ts_registered, NOW()) BETWEEN 60 * 60 * 24 * 7 AND 60 * 60 * 24 * 7 * 30 - 1
							THEN CONCAT(TIMESTAMPDIFF(WEEK, mb.ts_registered, NOW()), ' ', 'Weeks ago')
						WHEN TIMESTAMPDIFF(SECOND, mb.ts_registered, NOW()) >= 60 * 60 * 24 * 7 * 30
							THEN CONCAT(TIMESTAMPDIFF(MONTH, mb.ts_registered, NOW()), ' ', 'Months ago')
					END AS 'time'
					FROM market_bids mb, member m
					WHERE market_id = ?
					AND m.user_id = mb.user_id";
		return $this->getAll($sql, array($project_id));
	}

	function user_bid($user_id, $project_id) {
		$sql = "SELECT * FROM market_bids WHERE user_id = ? AND market_id = ?";
		return $this->getRow($sql, array($user_id, $project_id));
	}

	function bid_project($project_id, $user_id, $amount, $days, $proposal) {
		$sql = "SELECT * FROM market_bids WHERE market_id = ? AND user_id = ?";
		
		if (!$this->getRow($sql, array($project_id, $user_id))) {
			$sql = "INSERT INTO market_bids ( market_id, user_id, amount, days, proposal, ts_registered ) VALUES ( ?,?,?,?,?, NOW() )";
			return $this->create($sql, array($project_id, $user_id, $amount, $days, $proposal));
		}

		return false;
	}

	function update_proposal($project_id, $user_id, $proposal) {
		$sql = "UPDATE market_bids SET proposal = ? WHERE market_id = ? AND user_id = ?";
		return $this->update($sql, array($proposal, $project_id, $user_id));
	}

	function get_awards($project_id) {
		$sql = "SELECT
					mp.user_id			AS 'author_id',
					m.name 				AS 'author_name',
					mp.task_name		AS 'author_task',
					mp.task_days		AS 'task_days',
					mp.task_amount		AS 'task_amount',
					m.image 			AS 'author_image'
				FROM market_projects mp, member m
				WHERE mp.market_id = ?
				AND m.user_id = mp.user_id";
		return $this->getAll($sql, array($project_id));
	}
}
?>