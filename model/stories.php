<?php
class StoriesModel extends BaseModel
{
#################################################################################################

	function user_project_stories($user_id) {
		$sql = "SELECT
					s.story_id		AS 'id',
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
						WHEN TIMESTAMPDIFF(SECOND, s.ts_created, NOW()) >= 60 * 60 * 24
							THEN CONCAT(TIMESTAMPDIFF(DAY, s.ts_created, NOW()), ' ', 'Days ago')
					END AS 'time_created',
					CASE
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) < 60
							THEN CONCAT(TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()), ' ', 'Seconds ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) BETWEEN 60 AND 60 * 60 - 1
							THEN CONCAT(TIMESTAMPDIFF(MINUTE, s.ts_updated, NOW()), ' ', 'Minutes ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
							THEN CONCAT(TIMESTAMPDIFF(HOUR, s.ts_updated, NOW()), ' ', 'Hours ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) >= 60 * 60 * 24
							THEN CONCAT(TIMESTAMPDIFF(DAY, s.ts_updated, NOW()), ' ', 'Days ago')
					END AS 'time_updated',
					UNIX_TIMESTAMP(s.ts_updated)		AS 'ts_updated',
					s.publish			AS 'publish'
				FROM story s, member m
				WHERE s.user_id = ?
				AND s.deleted = 0
				AND s.market_place = 1
				AND m.user_id = s.user_id
				ORDER BY s.ts_created DESC
				LIMIT 5";
		return $this->getAll($sql, array($user_id));
	}

	function load_user_projects($user_id, $last_story_id) {
		$sql = "SELECT
					s.story_id		AS 'id',
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
						WHEN TIMESTAMPDIFF(SECOND, s.ts_created, NOW()) >= 60 * 60 * 24
							THEN CONCAT(TIMESTAMPDIFF(DAY, s.ts_created, NOW()), ' ', 'Days ago')
					END AS 'time_created',
					CASE
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) < 60
							THEN CONCAT(TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()), ' ', 'Seconds ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) BETWEEN 60 AND 60 * 60 - 1
							THEN CONCAT(TIMESTAMPDIFF(MINUTE, s.ts_updated, NOW()), ' ', 'Minutes ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
							THEN CONCAT(TIMESTAMPDIFF(HOUR, s.ts_updated, NOW()), ' ', 'Hours ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) >= 60 * 60 * 24
							THEN CONCAT(TIMESTAMPDIFF(DAY, s.ts_updated, NOW()), ' ', 'Days ago')
					END AS 'time_updated',
					UNIX_TIMESTAMP(s.ts_updated)		AS 'ts_updated',
					s.publish			AS 'publish'
				FROM story s, member m
				WHERE s.user_id = ?
				AND s.story_id < ?
				AND s.deleted = 0
				AND s.market_place = 1
				AND m.user_id = s.user_id
				ORDER BY s.ts_created DESC
				LIMIT 5";
		return $this->getAll($sql, array($user_id, $last_story_id));
	}	

	/*	Revised code as on 07-06-2018	*/

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

	function get_other_stories($user_id = false)
	{
	
		$sql = "SELECT
					s.story_id									AS 'id',
					s.slug										AS 'slug',
					m.name										AS 'owner_name',
					s.title										AS 'title'
				FROM story s, member m, vw_story_stats ss
				WHERE s.publish = ?
				AND s.deleted = 0
				AND m.user_id = s.user_id
				AND ss.story_id = s.story_id
				ORDER BY ss.no_votes + ss.no_views + ss.no_comments DESC
				LIMIT 5";

		return $this->getAll($sql, array(PUBLISH));
	
	}

	function load_more_stories($user_id = false, $last_story_id, $filter = false)
	{
		$data = array($last_story_id, PUBLISH);

		$sql = "SELECT
					s.story_id		AS 'id',
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
				WHERE s.story_id < ?
				AND s.publish = ?
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

	function get_user_stories($user_id, $filter = false)
	{

		$data = array($user_id);

		$sql = "SELECT
					s.story_id		AS 'id',
					s.slug			AS 'slug',
					m.user_id		AS 'owner_id',
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
					UNIX_TIMESTAMP(s.ts_updated)	AS 'ts_updated',
					s.publish						AS 'publish'
				FROM story s, member m
				WHERE s.user_id = ?
				AND s.deleted = 0
				AND m.user_id = s.user_id ";

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
			
			default:
				$sql .= "ORDER BY s.ts_created DESC";
				break;
		}

		$sql .= " LIMIT 5";

		return $this->getAll($sql, $data);
	}

	function apply_check($story_id, $owner_id)
	{

		$sql = "SELECT 1 FROM story_collab_apply WHERE story_id = ? AND owner_id = ? AND status = ?";

		return $this->getRow($sql, array($story_id, $owner_id, REQUEST_PENDING))[1];

	}

	function load_user_stories($user_id, $last_story_id, $filter = false)
	{

		$data = array($user_id, $last_story_id);

		$sql = "SELECT
					s.story_id		AS 'id',
					m.user_id		AS 'owner_id',
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
					UNIX_TIMESTAMP(s.ts_updated)	AS 'ts_updated',
					s.publish						AS 'publish'
				FROM story s, member m
				WHERE s.user_id = ?
				AND s.story_id < ?
				AND s.deleted = 0
				AND m.user_id = s.user_id ";

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
			
			default:
				$sql .= "ORDER BY s.ts_created DESC";
				break;
		}

		$sql .=	" LIMIT 5";
		
		return $this->getAll($sql, $data);
	
	}

	function get_apply_request($story_id, $owner_id)
	{

		$sql = "SELECT
				m.user_id		AS 'id',
				m.name			AS 'name',
				m.image			AS 'image'
			FROM story_collab_apply sca, member m
			WHERE sca.story_id = ?
			AND sca.owner_id = ?
			AND sca.status = ?
			AND m.user_id = sca.collab_id";
		
		return $this->getAll($sql, array($story_id, $owner_id, REQUEST_PENDING));
	
	}

	function user_collab_stories($user_id, $filter = false)
	{

		$data = array($user_id, ROLE_READER, ROLE_OWNER);

		$sql = "SELECT
				sc.story_id		AS 'id',
				s.user_id		AS 'owner_id',
				m.name			AS 'owner_name',
				m.image			AS 'owner_image',
				s.title			AS 'title',
				s.type			AS 'type',
				s.image			AS 'image',
				s.slug			AS 'slug',
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
				UNIX_TIMESTAMP(s.ts_updated)		AS 'ts_updated',
				s.publish							AS 'publish'
			FROM story_collab sc, member m, story s
			WHERE sc.user_id = ?
			AND sc.role > ?
			AND sc.role < ?
			AND s.story_id = sc.story_id
			AND m.user_id = s.user_id ";
			
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
			
			default:
				$sql .= "ORDER BY s.ts_created DESC";
				break;
		}

		$sql .=	" LIMIT 5";
		
		return $this->getAll($sql, $data);
	
	}

	function load_collab_stories($user_id, $last_story_id, $filter = false)
	{

		$data = array($user_id, ROLE_CONTRIBUTOR, $last_story_id);

		$sql = "SELECT
					sc.story_id		AS 'id',
					s.user_id		AS 'owner_id',
					m.name			AS 'owner_name',
					m.image			AS 'owner_image',
					s.title			AS 'title',
					s.type			AS 'type',
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
					UNIX_TIMESTAMP(s.ts_updated)		AS 'ts_updated',
					s.publish							AS 'publish'
				FROM story_collab sc, member m, story s
				WHERE sc.user_id = ?
				AND sc.role = ?
				AND s.story_id = sc.story_id
				AND s.story_id < ?
				AND m.user_id = s.user_id ";

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
			
			default:
				$sql .= "ORDER BY s.ts_created DESC";
				break;
		}

		$sql .=	" LIMIT 5";
		
		return $this->getAll($sql, $data);
				
	}

	function get_stories_invite($collab_id)
	{

		$sql = "SELECT
				sci.story_id		AS 'id',
				m.user_id			AS 'owner_id',
				m.name				AS 'owner_name',
				m.image				AS 'owner_image',
				s.title				AS 'title',
				s.type				AS 'type',
				s.image				AS 'image',
				s.slug				AS 'slug',
				CASE
					WHEN TIMESTAMPDIFF(SECOND, sci.ts_requested, NOW()) < 60
						THEN CONCAT(TIMESTAMPDIFF(SECOND, sci.ts_requested, NOW()), ' ', 'Seconds ago')
					WHEN TIMESTAMPDIFF(SECOND, sci.ts_requested, NOW()) BETWEEN 60 AND 60 * 60 - 1
						THEN CONCAT(TIMESTAMPDIFF(MINUTE, sci.ts_requested, NOW()), ' ', 'Minutes ago')
					WHEN TIMESTAMPDIFF(SECOND, sci.ts_requested, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
						THEN CONCAT(TIMESTAMPDIFF(HOUR, sci.ts_requested, NOW()), ' ', 'Hours ago')
					WHEN TIMESTAMPDIFF(SECOND, sci.ts_requested, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
						THEN CONCAT(TIMESTAMPDIFF(DAY, sci.ts_requested, NOW()), ' ', 'Days ago')
					WHEN TIMESTAMPDIFF(SECOND, sci.ts_requested, NOW()) BETWEEN 60 * 60 * 24 * 7 AND 60 * 60 * 24 * 7 * 30 - 1
						THEN CONCAT(TIMESTAMPDIFF(WEEK, sci.ts_requested, NOW()), ' ', 'Weeks ago')
					WHEN TIMESTAMPDIFF(SECOND, sci.ts_requested, NOW()) >= 60 * 60 * 24 * 7 * 30
						THEN CONCAT(TIMESTAMPDIFF(MONTH, sci.ts_requested, NOW()), ' ', 'Months ago')
				END AS 'time_requested'
			FROM story_collab_invite sci, member m, story s
			WHERE sci.collab_id = ?
			AND sci.status = ?
			AND s.story_id = sci.story_id
			AND s.deleted = 0
			AND m.user_id = s.user_id
			ORDER BY sci.ts_requested DESC
			LIMIT 5";
		
		$contribution = $this->getAll($sql, array($collab_id, REQUEST_PENDING));

		$sql = "SELECT
				sci.story_id		AS 'id',
				m.user_id			AS 'owner_id',
				m.name				AS 'owner_name',
				m.image				AS 'owner_image',
				s.title				AS 'title',
				s.type				AS 'type',
				s.image				AS 'image',
				s.slug				AS 'slug',
				CASE
					WHEN TIMESTAMPDIFF(SECOND, sci.ts_requested, NOW()) < 60
						THEN CONCAT(TIMESTAMPDIFF(SECOND, sci.ts_requested, NOW()), ' ', 'Seconds ago')
					WHEN TIMESTAMPDIFF(SECOND, sci.ts_requested, NOW()) BETWEEN 60 AND 60 * 60 - 1
						THEN CONCAT(TIMESTAMPDIFF(MINUTE, sci.ts_requested, NOW()), ' ', 'Minutes ago')
					WHEN TIMESTAMPDIFF(SECOND, sci.ts_requested, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
						THEN CONCAT(TIMESTAMPDIFF(HOUR, sci.ts_requested, NOW()), ' ', 'Hours ago')
					WHEN TIMESTAMPDIFF(SECOND, sci.ts_requested, NOW()) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
						THEN CONCAT(TIMESTAMPDIFF(DAY, sci.ts_requested, NOW()), ' ', 'Days ago')
					WHEN TIMESTAMPDIFF(SECOND, sci.ts_requested, NOW()) BETWEEN 60 * 60 * 24 * 7 AND 60 * 60 * 24 * 7 * 30 - 1
						THEN CONCAT(TIMESTAMPDIFF(WEEK, sci.ts_requested, NOW()), ' ', 'Weeks ago')
					WHEN TIMESTAMPDIFF(SECOND, sci.ts_requested, NOW()) >= 60 * 60 * 24 * 7 * 30
						THEN CONCAT(TIMESTAMPDIFF(MONTH, sci.ts_requested, NOW()), ' ', 'Months ago')
				END AS 'time_requested'
			FROM story_collab_read_invite sci, member m, story s
			WHERE sci.collab_id = ?
			AND sci.status = ?
			AND s.story_id = sci.story_id
			AND s.deleted = 0
			AND m.user_id = s.user_id
			ORDER BY sci.ts_requested DESC
			LIMIT 5";

			$read = $this->getAll($sql, array($collab_id, REQUEST_PENDING));

			return array( 'contribution' => $contribution, 'contribution_read' => $read);
	
	}

	function accept_invite($invite_id, $collab_id, $story_id, $room_id)
	{

		$this->beginTransaction();
		
		$sql = "UPDATE story_collab_invite SET status = ? WHERE invite_id = ?";
		
		if ($this->update($sql, array(REQUEST_ACCEPTED, $invite_id))) {

			$sql = "SELECT * FROM story_collab WHERE story_id = ? AND user_id = ? AND role = ?";

			if($sc = $this->getRow($sql, array($story_id, $collab_id, ROLE_CONTRIBUTOR_READER))) {

				$sql = "UPDATE story_collab SET role = ? WHERE id = ?";

				$this->endTransaction();

				return $this->update($sql, array(ROLE_CONTRIBUTOR, $sc["id"]));

			} else {

				$sql = "INSERT INTO story_collab ( story_id, user_id, role ) VALUES ( ?,?,? )";
			
				$this->create($sql, array($story_id, $collab_id, ROLE_CONTRIBUTOR));
				
				$sql = "INSERT INTO chat_room_member ( room_id, member_id, ts_registered) VALUES ( ?,?, NOW())";
				
				$this->endTransaction();
			
				return $this->create($sql, array($room_id, $collab_id));
			}
		
		}
		
		$this->endTransaction();
		
		return false;
	
	}

	function decline_invite($invite_id)
	{

		$sql = "UPDATE story_collab_invite SET status = ? WHERE invite_id = ?";
		
		return $this->update($sql, array(REQUEST_DECLINED, $invite_id));
	
	}

	function accept_apply($apply_id, $collab_id, $story_id, $room_id)
	{

		$this->beginTransaction();
		
		$sql = "UPDATE story_collab_apply SET status = ? WHERE apply_id = ?";
		
		if ($this->update($sql, array(REQUEST_ACCEPTED, $apply_id))) {
			
			$sql = "SELECT * FROM story_collab WHERE story_id = ? AND user_id = ? AND role = ?";

			if($sc = $this->getRow($sql, array($story_id, $collab_id, ROLE_CONTRIBUTOR_READER))) {

				$sql = "UPDATE story_collab SET role = ? WHERE id = ?";

				$this->endTransaction();

				return $this->update($sql, array(ROLE_CONTRIBUTOR, $sc["id"]));

			} else {

				$sql = "INSERT INTO story_collab ( story_id, user_id, role ) VALUES ( ?,?,? )";
			
				$this->create($sql, array($story_id, $collab_id, ROLE_CONTRIBUTOR));
				
				$sql = "INSERT INTO chat_room_member ( room_id, member_id, ts_registered) VALUES ( ?,?, NOW())";
				
				$this->endTransaction();
			
				return $this->create($sql, array($room_id, $collab_id));
			}
		
		}
		
		$this->endTransaction();
		
		return false;
	
	}

	function decline_apply($apply_id)
	{

		$sql = "UPDATE story_collab_apply SET status = ? WHERE apply_id = ?";
		
		return $this->update($sql, array(REQUEST_DECLINED, $apply_id));
	
	}

	function load_member_stories($member_id, $last_story_id, $filter = false)
	{

		$data = array($last_story_id, $member_id, PUBLISH);

		$sql = "SELECT
					s.story_id		AS 'id',
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
				FROM story s, member m
				WHERE s.story_id < ?
				AND s.user_id = ?
				AND s.publish = ?
				AND s.deleted = 0
				AND m.user_id = s.user_id ";
		
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
			
			default:
				$sql .= "ORDER BY s.ts_created DESC";
				break;
		}

		$sql .=	" LIMIT 5";
		
		return $this->getAll($sql, $data);

	}

	function get_stories_search($string)
	{

		$search = "%".$string."%";
		
		$sql = "SELECT
					s.story_id		AS 'id',
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
						WHEN TIMESTAMPDIFF(SECOND, s.ts_created, NOW()) >= 60 * 60 * 24
							THEN CONCAT(TIMESTAMPDIFF(DAY, s.ts_created, NOW()), ' ', 'Days ago')
					END AS 'time_created',
					CASE
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) < 60
							THEN CONCAT(TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()), ' ', 'Seconds ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) BETWEEN 60 AND 60 * 60 - 1
							THEN CONCAT(TIMESTAMPDIFF(MINUTE, s.ts_updated, NOW()), ' ', 'Minutes ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
							THEN CONCAT(TIMESTAMPDIFF(HOUR, s.ts_updated, NOW()), ' ', 'Hours ago')
						WHEN TIMESTAMPDIFF(SECOND, s.ts_updated, NOW()) >= 60 * 60 * 24
							THEN CONCAT(TIMESTAMPDIFF(DAY, s.ts_updated, NOW()), ' ', 'Days ago')
					END AS 'time_updated',
					UNIX_TIMESTAMP(s.ts_updated)		AS 'ts_updated',
					s.publish			AS 'publish'
				FROM story s, member m
				WHERE s.title LIKE ?
				AND s.publish = ?
				AND s.deleted = 0
				AND m.user_id = s.user_id
				ORDER BY s.ts_created DESC";
	
		return $this->getAll($sql, array($search, PUBLISH));
	
	}

	function accept_invite_read($invite_id, $collab_id, $story_id, $room_id)
	{

		$this->beginTransaction();
		
		$sql = "UPDATE story_collab_read_invite SET status = ? WHERE invite_id = ?";
		
		if ($this->update($sql, array(REQUEST_ACCEPTED, $invite_id))) {
		
			$sql = "INSERT INTO story_collab ( story_id, user_id, role ) VALUES ( ?,?,? )";
		
			$this->create($sql, array($story_id, $collab_id, ROLE_CONTRIBUTOR_READER));

			$sql = "INSERT INTO chat_room_member ( room_id, member_id, ts_registered) VALUES ( ?,?, NOW())";
		
			$this->endTransaction();
		
			return $this->create($sql, array($room_id, $collab_id));
		
		}
		
		$this->endTransaction();
		
		return false;
	
	}

	function decline_invite_read($invite_id)
	{

		$sql = "UPDATE story_collab_read_invite SET status = ? WHERE invite_id = ?";
		
		return $this->update($sql, array(REQUEST_DECLINED, $invite_id));
	
	}

}