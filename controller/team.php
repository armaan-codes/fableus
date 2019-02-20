<?php
class TeamController extends BaseController
{
	/*	Revised Code as on 08/06/2018	*/

	function index($args = array())
	{

		$user = $this->getSessionUser();
		
		$team_dlgt = $this->getDelegate("team");

		if (!$user)
			$this->redirect("/");
		
		$teams = $team_dlgt->get_teams($user["user_id"]);

		$this->tpl->assign("user", $user);
		
		$this->tpl->assign("teams", $teams);
		
		$this->tpl->assign('tab', array('teams' => true));
		
		$this->render('team');
	
	}

	function create_team()
	{
	
		$user = $this->getSessionUser();
	
		$team_dlgt = $this->getDelegate('team');

		$team_name = $_POST['team_name'];
	
		$team_desc = $_POST['team_desc'];
	
		$team_img = $_FILES['team_img'];

		if (!$user || empty($team_name))
			$this->redirect("/team");

		if ($team_dlgt->create_team($user['user_id'], $team_name, $team_desc, $team_img))
			$this->redirect("/team");
	}

	function view($team_id)
	{

		$user = $this->getSessionUser();
		
		$team_dlgt = $this->getDelegate('team');

		$team = $team_dlgt->get_team($user["user_id"], $team_id);

		if (empty($user) || empty($team))
			$this->redirect("/team");

		$this->tpl->assign("user", $user);
		
		$this->tpl->assign("team", $team);
		
		$this->render("team_view");
	
	}

	function update_team($team_id)
	{

		$user = $this->getSessionUser();
		
		$team_dlgt = $this->getDelegate('team');

		$team = $team_dlgt->get_team($user["user_id"], $team_id);

		if (empty($user) || empty($team))
			$this->redirect("/team");

		if (isset($_POST["team_name"], $_POST["team_desc"], $_FILES["team_img"])) {
		
			$team_name = $_POST["team_name"];
		
			$team_desc = $_POST["team_desc"];
		
			$team_image = $_FILES["team_img"];

			$team_dlgt->update_team($team_id, $team_name, $team_desc, $team_image);
		
		}
	
		$this->redirect("/team/view/".$team_id);
	
	}

	function add_member($team_id)
	{

		$user = $this->getSessionUser();
		
		$team_dlgt = $this->getDelegate('team');
		
		$user_dlgt = $this->getDelegate('user');

		$team = $team_dlgt->get_team($user["user_id"], $team_id);

		if (empty($user) || empty($team)) {
		
			$response["status"] = "Failure";
		
			$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><div class="row"><p align="center"><strong>Warning!</strong> Not authorised.</p></div></div>';
		
			echo json_encode($response);
		
			return;
		
		}

		if (isset($_POST["member_email"]) && !empty($_POST["member_email"])) {
		
			$member_email = trim($_POST["member_email"]);

			if ($member_email == $user["email"]) {
		
				$response["status"] = "Failure";
		
				$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><div class="row"><p align="center">Can not add team owner as team member.</p></div></div>';
		
				echo json_encode($response);
		
				return;
		
			}

			if($member = $user_dlgt->get_user_by_email($member_email)) {
		
				if ($team_dlgt->add_member($team_id, $member["user_id"])) {
		
					$response["status"] = "Success";
		
					$response["data"] = "/team/view/".$team_id;
		
					echo json_encode($response);
		
					return;
		
				}

				$response["status"] = "Failure";
		
				$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><div class="row"><p align="center"><strong>Member</strong> already exists.</p></div></div>';
		
				echo json_encode($response);
		
				return;
		
			}

			$response["status"] = "Failure";
		
			$response["data"] = '<div class="alert alert-info alert-dismissible" role="alert"><button type="button" class="close" 
			data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><div class="row"><p align="center"><strong>User</strong> not a member of story.</p></div><div class="row" style="margin: 10px auto;"><div class="col-sm-6 col-sm-offset-3"><a href="/auth/story_invite?email='.$member_email.'" class="btn btn-info btn-block text-uppercase">Send Invite</a></div></div></div>';
			
			echo json_encode($response);
			
			return;
		}

		$response["status"] = "Failure";

		$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><span style="color: red;">Warning!</span></strong> Something Went Wrong.</div>';
		
		echo json_encode($response);
		
		return;
	}

	function delete_member($team_id, $member_id)
	{

		$user = $this->getSessionUser();
		
		$team_dlgt = $this->getDelegate('team');

		$team = $team_dlgt->get_team($user["user_id"], $team_id);

		if (empty($user) || empty($team))
			$this->redirect("/team");

		if ($team_dlgt->delete_member($team_id, $member_id))
			$this->redirect("/team/view/".$team_id);
		
		$this->redirect("/team");
	
	}

	function delete_team ($team_id)
	{

		$user = $this->getSessionUser();
		
		$team_dlgt = $this->getDelegate('team');

		$team = $team_dlgt->get_team($user["user_id"], $team_id);
		
		if (empty($user) || empty($team))
			$this->redirect("/team");

		$team_dlgt->delete_team($user["user_id"], $team_id);
		
		$this->redirect("/team");
	
	}

}