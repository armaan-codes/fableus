<?php
class UserModel extends BaseModel
{

	function no_user_projects($user_id) {
		$sql = "SELECT
					COUNT(*) AS 'no_projects'
				FROM market_place
				WHERE user_id = ?
				AND status <= ?";
		return $this->getRow($sql, array($user_id, PROJECT_AWARD));
	}

	function get_user_plan($user_id) {
		$sql = "SELECT
					mp.plan			AS 'plan',
					mp.user_id		AS 'user_id',
					CASE
						WHEN TIMESTAMPDIFF(SECOND, NOW(), mp.ts_expire) < 60
							THEN CONCAT(TIMESTAMPDIFF(SECOND, NOW(), mp.ts_expire), ' ', 'Seconds remaining')
						WHEN TIMESTAMPDIFF(SECOND, NOW(), mp.ts_expire) BETWEEN 60 AND 60 * 60 - 1
							THEN CONCAT(TIMESTAMPDIFF(MINUTE, NOW(), mp.ts_expire), ' ', 'Minutes remaining')
						WHEN TIMESTAMPDIFF(SECOND, NOW(), mp.ts_expire) BETWEEN 60 * 60 AND 60 * 60 * 24 - 1
							THEN CONCAT(TIMESTAMPDIFF(HOUR, NOW(), mp.ts_expire), ' ', 'Hours remaining')
						WHEN TIMESTAMPDIFF(SECOND, NOW(), mp.ts_expire) BETWEEN 60 * 60 * 24 AND 60 * 60 * 24 * 7 - 1
							THEN CONCAT(TIMESTAMPDIFF(DAY, NOW(), mp.ts_expire), ' ', 'Days remaining')
						WHEN TIMESTAMPDIFF(SECOND, NOW(), mp.ts_expire) BETWEEN 60 * 60 * 24 * 7 AND 60 * 60 * 24 * 7 * 30 - 1
							THEN CONCAT(TIMESTAMPDIFF(WEEK, NOW(), mp.ts_expire), ' ', 'Weeks remaining')
						WHEN TIMESTAMPDIFF(SECOND, NOW(), mp.ts_expire) >= 60 * 60 * 24 * 7 * 30
							THEN CONCAT(TIMESTAMPDIFF(MONTH, NOW(), mp.ts_expire), ' ', 'Months remaining')
					END AS 'ts_remaining'
					FROM member_plan mp, member m
					WHERE m.user_id = ?
					AND mp.user_id = m.user_id
					AND mp.plan = m.plan
					AND mp.status = ?";
		return $this->getRow($sql, array($user_id, ACTIVE_PLAN));
	}

	function initialize_plan($user_id, $plan, $price, $period) {
		$sql = "SELECT * FROM member_plan WHERE user_id = ? AND status = ?";
		if (!$this->getRow($sql, array($user_id, WAITING_CONFIRMATION))) {
			$sql = "INSERT INTO member_plan ( user_id, plan, amount, ts_start, ts_expire, status ) VALUES ( ?,?,?, NOW(), DATE_ADD(NOW(), INTERVAL 1 ".$period."),? )";
			return $this->create($sql, array($user_id, $plan, $price, WAITING_CONFIRMATION));
		}
		return false;
	}

	function initialize_free_plan($user_id) {
		$sql = "SELECT * FROM member_plan WHERE user_id = ? AND status = ?";

		if($prev_plan = $this->getRow($sql, array($user_id, ACTIVE_PLAN))) {

			$sql = "UPDATE member_plan SET status = ? AND ts_expire = NOW() WHERE plan_id = ?";

			$this->update($sql, array(DELETED_PLAN, $prev_plan["plan_id"]));

		}

		$sql = "UPDATE member SET plan = ? WHERE user_id = ?";

		return $this->update($sql, array(MEMBER_BASIC, $user_id));
	}
#################################################################################################

	function login_update($user_id) {
		$sql = "SELECT * FROM member WHERE user_id = ? AND active = 1";
		$user = $this->getRow($sql, array($user_id));

		if ($user["login"] == 0) {
			$sql = "UPDATE member SET login = 1 WHERE user_id = ?";
			return $this->update($sql, array($user_id));
		}

		return $user;
	}

	function pending_payment($user_id) {
		$sql = "SELECT COUNT(*)	AS 'plan' FROM member_plan WHERE user_id = ? AND status = ?";
		return $this->getRow($sql, array($user_id, WAITING_CONFIRMATION));
	}

	function get_pending_plan($user_id) {
		$sql = "SELECT
					plan_id,
					user_id,
					plan,
					amount,
					CASE
						WHEN TIMESTAMPDIFF(SECOND, ts_start, ts_expire) <= 60 * 60 * 24 * 7 * 30
							THEN 'month'
						ELSE 'year'
					END AS 'period'
		 FROM member_plan WHERE user_id = ? AND status = ?";
		return $this->getRow($sql, array($user_id, WAITING_CONFIRMATION));
	}

	function delete_pending_plan($plan_id) {
		$sql = "UPDATE member_plan SET status = ? WHERE plan_id = ?";
		return $this->update($sql, array(DELETED_PLAN, $plan_id));
	}

	/*	Revised Code as on 07/06/2018	*/

	function get_user_universal($user_id)
	{
	
		$sql = "SELECT
					user_id,
					plan,
					name,
					email,
					password,
					login,
					image
				FROM member
				WHERE user_id = ?";
	
		return $this->getRow($sql, array($user_id));
	
	}

	function get_user_by_email($email)
	{

		$sql = "SELECT 
					user_id,
					plan,
					name,
					email,
					password,
					login,
					image,
					active
				FROM member
				WHERE email = ?";

		return $this->getRow($sql, array($email));
	
	}

	function activate_user($user)
	{
	
		$sql = "UPDATE member SET active = 1 WHERE user_id = ?";
	
		return $this->update($sql, array($user['id']));
	
	}

	function get_user($user_id)
	{
		
		$sql = "SELECT
					user_id,
					plan,
					name,
					first_name,
					last_name,
					bio,
					email,
					password,
					login,
					image
				FROM member
				WHERE user_id = ?
				AND active = 1";
		
		return $this->getRow($sql, array($user_id));
	
	}

	function get_teams($user_id)
	{

		$sql = "SELECT team_id, owner_id, name, description, ts_updated, image FROM team WHERE owner_id = ? AND deleted = 0";
		
		return $this->getAll($sql, array($user_id));
	
	}

	function get_team($user_id, $team_id)
	{

		$sql = "SELECT team_id, owner_id, name, description, ts_updated, image FROM team WHERE owner_id = ? AND team_id = ? AND deleted = 0";
		
		return $this->getRow($sql, array($user_id, $team_id));
	
	}

	function add_team ($user_id, $name, $description = false, $image)
	{

		$sql = "INSERT INTO team ( owner_id, name, description, ts_created, ts_updated, image ) VALUES ( ?,?,?, NOW(), NOW(), ? )";
		
		return $this->create($sql, array($user_id, $name, $description, $image));
	
	}

	function get_members($team_id)
	{

		$sql = "SELECT
				m.user_id	AS 'id',
				m.name		AS 'name',
				m.email		AS 'email',
				m.image		AS 'image'
			FROM team_member t, member m
			WHERE t.team_id = ?
			AND m.user_id = t.member_id
			AND t.deleted = 0";

		return $this->getAll($sql, array($team_id));

	}

	function update_team($team_id, $team_name, $team_desc, $team_image = false)
	{

		$sql = "UPDATE 
					team 
				SET name = ?,
					description = ?,
					ts_updated = NOW() ";

		if ($team_image) {
		
			$sql .= ", image = '".$team_image."' ";
		
		}
		
		$sql .= "WHERE team_id = ? AND deleted = 0";
		
		return $this->update($sql, array($team_name, $team_desc, $team_id));
	
	}

	function add_member($team_id, $member_id)
	{

		$sql = "SELECT * FROM team_member WHERE team_id = ? AND member_id = ? AND deleted = 0";
		
		if (!$this->getRow($sql, array($team_id, $member_id))) {
		
			$sql = "INSERT INTO team_member ( team_id, member_id ) VALUES ( ?,? )";
		
			if($this->create($sql, array($team_id, $member_id))){
		
				$sql = "UPDATE team SET ts_updated = NOW() WHERE team_id = ? AND deleted = 0";
		
				return $this->update($sql, array($team_id));
		
			}
		
		}
		
		return false;
	
	}

	function delete_member($team_id, $member_id)
	{

		$sql = "UPDATE team_member SET deleted = 1 WHERE team_id = ? AND member_id = ?";
		
		if($this->update($sql, array($team_id, $member_id))) {
		
			$sql = "UPDATE team SET ts_updated = NOW() WHERE team_id = ? AND deleted = 0";
		
			return $this->update($sql, array($team_id));
		
		}
	
	}

	function delete_team($user_id, $team_id)
	{

		$sql = "UPDATE team SET deleted = 1 WHERE owner_id = ? AND team_id = ?";
		
		return $this->update($sql, array($user_id, $team_id));
	
	}

	function no_user_stories($user_id)
	{
	
		$sql = "SELECT
				COUNT(*) AS 'no_stories'
			FROM story
			WHERE user_id = ?
			AND publish = ?
			AND deleted = 0";
	
		return $this->getRow($sql, array($user_id, PUBLISH));
	
	}

	function no_user_contributions($user_id)
	{

		$sql = "SELECT
				COUNT(*) AS 'no_contributions'
			FROM story_collab
			WHERE user_id = ?
			AND role = ?
			AND active = 1";
		
		return $this->getRow($sql, array($user_id, ROLE_CONTRIBUTOR));
	
	}

	function upload_user_image($user_id, $image)
	{

		$sql = "UPDATE member SET image = ? WHERE user_id = ?";
		
		return $this->update($sql, array($image, $user_id));
	
	}

	function update_password($user_id, $password)
	{

		$sql = "UPDATE member SET password = ? WHERE user_id = ? AND active = 1";
		
		return $this->update($sql, array(md5($password), $user_id));
	
	}

	function update_user_info($user_id, $name, $first_name, $last_name = false, $bio = false)
	{
		
		$sql = "UPDATE member SET name = ?, first_name = ?, last_name = ?, bio = ? WHERE user_id = ?";
		
		return $this->update($sql, array($name, $first_name, $last_name, $bio, $user_id));
	
	}

	function get_followers($user_id)
	{
		$sql = "SELECT member_id, member_follower_id FROM member_followers WHERE member_id = ?";

		return $this->getAll($sql, array($user_id));
	}

	function user_followers($user_id)
	{
		$sql = "SELECT * FROM member_followers WHERE member_id = ?";

		return $this->getAll($sql, array($user_id));
	}

	function check_follow($user_id, $member_id)
	{
		$sql = "SELECT * FROM member_followers WHERE member_id = ? AND member_follower_id = ?";

		return $this->getRow($sql, array($member_id, $user_id));
	}

	function follow_member($user_id, $member_id)
	{
		$sql = "INSERT INTO member_followers (member_id, member_follower_id) VALUES (?, ?)";

		return $this->create($sql, array($member_id, $user_id));
	}

	function unfollow_member($user_id, $member_id)
	{
		$sql = "DELETE FROM member_followers WHERE member_id = ? AND member_follower_id = ?";

		return $this->delete($sql, array($member_id, $user_id));
	}
}
?>