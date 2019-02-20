<?php
class ProjectsController extends BaseController
{
	protected $models = array('projects', 'market', 'story');

	function index($args = array()) {
		$user = $this->getSessionUser();
		$project_dlgt = $this->getDelegate('projects');

		if (empty($user) || $user['plan'] == MEMBER_BASIC || empty($user['plan'])) {
			$this->redirect('/');
		}

		$projects = $project_dlgt->my_projects($user["user_id"]);

		$this->tpl->assign('user', $user);
		$this->tpl->assign('projects', $projects);
		$this->tpl->assign('tab', array('projects' => true));
		$this->render('projects');
	}

	function search() {
		$user = $this->getSessionUser();
		$project_dlgt = $this->getDelegate('projects');

		if (empty($user) || $user['plan'] == MEMBER_BASIC || empty($user['plan'])) {
			$response["status"] = "Failure";
			echo json_encode($response);
			return;
		}

		if (isset($_POST["search"]) && !empty($_POST["search"])) {
			$string = "%".trim($_POST["search"])."%";
			if ($projects = $project_dlgt->search_project($user["user_id"],  $string)) {
				$response["status"] = "Success";
				$response["data"] = "";
				
				foreach ($projects as $project) {
					$response["data"] .= '<div class="card-story"><h2><a href="/projects/view/'.$project["id"].'">'.$project["title"].'</a><span class="text-muted" style="float:right; font-size:18px;">$'.$project["range_from"].' - $'.$project["range_to"].'</span></h2><div class="media media-card"><div class="media-left"><img src="/resource/img/user/'.$project["owner_image"].'" class="media-object"></div><div class="media-body"><ul class="list-story"><span class="list-dotted"></span><li>by <a href="/user/view/'.$project["owner_id"].'">'.$project["owner_name"].'</a></li><span class="list-dotted"></span><li class="mobile-block">Created '.$project["time"].'</li><span class="list-dotted"></span><li><i class="fa fa-gavel" aria-hidden="true"></i> <a>'.$project["stats"]["bids"].'</a></li>';
					
					if ($project["status"] == PROJECT_AWARD) {
						$response["data"] .= '<span class="list-dotted"></span><li class="mobile-block" style="color: red"><strong>Awarded</strong></li>';
					}
					
					$response["data"] .= '</ul><hr>'.$project["50_words"].'</div></div></div>';
				}

				echo json_encode($response);
				return;
			} else {
				$response["status"] = "Success";
				$response["data"] = '<h3 class="text-muted" align="center">Nothing Found</h3>';
				echo json_encode($response);
				return;
			}
		}

		$response["status"] = "Failure";
		echo json_encode($response);
		return;
	}

	function load_open_projects() {
		$user = $this->getSessionUser();
		$project_dlgt = $this->getDelegate('projects');

		if (empty($user) || $user['plan'] == MEMBER_BASIC) {
			$response["status"] = "Failure";
			echo json_encode($response);
			return;
		}

		if (isset($_POST["last_id"]) && !empty($_POST["last_id"])) {
			$last_id = trim($_POST["last_id"]);
			
			if($projects = $project_dlgt->load_open_projects($user["user_id"], $last_id)) {
				$response["status"] = "Success";
				$response["data"] = "";
				foreach ($projects as $project) {
					$response["data"] .= '<div class="card-story"><h2><a href="/market/view/'.$project["id"].'">'.$project["title"].'</a><span class="text-muted" style="float:right; font-size:18px;">$'.$project["range_from"].' - $'.$project["range_to"].'</span></h2><div class="media media-card"><div class="media-left"><img src="/resource/img/user/'.$project["owner_image"].'" class="media-object"></div><div class="media-body"><ul class="list-story"><span class="list-dotted"></span><li> by <a href="/user/view/'.$project["owner_id"].'">'.$project["owner_name"].'</a></li><span class="list-dotted"></span><li class="mobile-block">Created '.$project["time"].'</li><span class="list-dotted"></span><li><i class="fa fa-gavel" aria-hidden="true"></i> <a>'.$project["stats"]["bids"].'</a></li></ul><hr>'.$project["50_words"].'</div></div></div>';
					$response["last_id"] = $project["id"];
				}
				echo json_encode($response);
				return;
			}

			$response["status"] = "Null";
			echo json_encode($response);
			return;
		}
		$response["status"] = "Failure";
		echo json_encode($response);
		return;
	}

	function load_award_projects() {
		$user = $this->getSessionUser();
		$project_dlgt = $this->getDelegate('projects');

		if (empty($user) || $user['plan'] == MEMBER_BASIC) {
			$response["status"] = "Failure";
			echo json_encode($response);
			return;
		}

		if (isset($_POST["last_id"]) && !empty($_POST["last_id"])) {
			$last_id = trim($_POST["last_id"]);
			
			if($projects = $project_dlgt->load_award_projects($user["user_id"], $last_id)) {
				$response["status"] = "Success";
				$response["data"] = "";
				foreach ($projects as $project) {
					$response["data"] .= '<div class="card-story"><h2><a href="/market/view/'.$project["id"].'">'.$project["title"].'</a><span class="text-muted" style="float:right; font-size:18px;">$'.$project["range_from"].' - $'.$project["range_to"].'</span></h2><div class="media media-card"><div class="media-left"><img src="/resource/img/user/'.$project["owner_image"].'" class="media-object"></div><div class="media-body"><ul class="list-story"><span class="list-dotted"></span><li> by <a href="/user/view/'.$project["owner_id"].'">'.$project["owner_name"].'</a></li><span class="list-dotted"></span><li class="mobile-block">Created '.$project["time"].'</li><span class="list-dotted"></span><li><i class="fa fa-gavel" aria-hidden="true"></i> <a>'.$project["stats"]["bids"].'</a></li><span class="list-dotted"></span><li class="mobile-block" style="color: red"><strong>Awarded</strong></li></ul><hr>'.$project["50_words"].'</div></div></div>';
					$response["last_id"] = $project["id"];
				}
				echo json_encode($response);
				return;
			}

			$response["status"] = "Null";
			echo json_encode($response);
			return;
		}
		$response["status"] = "Failure";
		echo json_encode($response);
		return;
	}

	function view($project_id) {
		$user = $this->getSessionUser();
		$market_dlgt = $this->getDelegate('market');

		if (empty($user) || $user['plan'] == MEMBER_BASIC || empty($user['plan']))
			$this->redirect('/');

		$project = $market_dlgt->get_project($project_id);

		if (empty($project))
			$this->redirect('/market');

		if ($project["owner_id"] != $user["user_id"]) {
			$this->redirect('/market/view/'.$project["id"]);
		}

		$bids = $market_dlgt->get_bids($project_id);
		$awards = $market_dlgt->get_awards($project_id);

		$this->tpl->assign('user', $user);
		$this->tpl->assign('bids', $bids);
		$this->tpl->assign('awards', $awards);
		$this->tpl->assign('project', $project);
		$this->tpl->assign('tab', array('projects' => true));
		$this->render('project_view');
	}

	function edit($project_id) {
		$user = $this->getSessionUser();
		$market_dlgt = $this->getDelegate('market');
		$project_dlgt = $this->getDelegate('projects');

		if (empty($user) || $user['plan'] == MEMBER_BASIC || empty($user['plan'])) {
			$this->redirect('/');
		}

		$project = $market_dlgt->get_project($project_id);

		if (isset($_POST["edit-category"], $_POST["edit-range-from"], $_POST["edit-range-to"], $_POST["edit-description"]) && !empty($project) && $user["user_id"] == $project["owner_id"]) {
			$category = $_POST["edit-category"];
			$range_from = $_POST["edit-range-from"];
			$range_to = $_POST["edit-range-to"];
			$description = $_POST["edit-description"];

			if ($project_dlgt->edit($user["user_id"], $project_id, $category, $range_from, $range_to, $description))
				$this->redirect("/projects/view/".$project_id);
		}

		$this->redirect("/projects");
	}

	function award($project_id) {
		$user = $this->getSessionUser();
		$market_dlgt = $this->getDelegate('market');
		$project_dlgt = $this->getDelegate('projects');

		$project = $market_dlgt->get_project($project_id);

		if (empty($user) || $user['plan'] == MEMBER_BASIC || $user["user_id"] != $project["owner_id"] || $project["status"] != PROJECT_OPEN	) {
			$response["status"] = "Failure";
			echo json_encode($response);
			return;
		}

		$data = array();
		foreach (array_chunk($_POST, 4) as $input) {
			if (empty($data)) {
				$x = $input[1];
				$data[$x]["task_name"] = $input[0];
				$data[$x]["author_id"] = $input[1];
				$data[$x]["task_days"] = $input[2];
				$data[$x]["task_amount"] = $input[3];
			} elseif (!array_key_exists($input[1], $data)) {
				$x = $input[1];
				$data[$x]["task_name"] = $input[0];
				$data[$x]["author_id"] = $input[1];
				$data[$x]["task_days"] = $input[2];
				$data[$x]["task_amount"] = $input[3];
			} else {
				$response["status"] = "Error";
				$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Cannot assign two task to same user.</div>';
				echo json_encode($response);
				return;
			}
			$date = date("Y-m-d");
			if (strtotime($data[$x]["task_days"]) <= strtotime($date)) {
				$response["status"] = "Error";
				$response["data"] = '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> Payment time cannot be less than or equal to current date.</div>';
				echo json_encode($response);
				return;
			}
		}

		if ($story_id = $project_dlgt->award_project($user["user_id"], $project_id, $data)) {
			$response["status"] = "Success";
			$response["data"] = '<div class="row"><div class="col-sm-4 col-sm-offset-4"><img src="/resource/img/tick_green.png" width="300"></div></div><h3 class="text-muted" align="center">Well Done! You have Finalised this project. Hold on! you will be redirected to the story.</h3>';
			$response["url"] = "/story/edit/".$story_id;
			echo json_encode($response);
			return;
		}

		$response["status"] = "Failure";
		echo json_encode($response);
		return;
	}
}
?>