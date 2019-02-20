<?php
class MarketController extends BaseController
{
	protected $models = array('market', 'story');

	function index($args = array()) {
		$user = $this->getSessionUser();
		$market_dlgt = $this->getDelegate('market');

		if (empty($user) || $user['plan'] == MEMBER_BASIC || empty($user['plan'])) {
			$this->redirect('/');
		}

		$projects = $market_dlgt->get_projects($user["user_id"]);
		$authors = $market_dlgt->get_top_authors($user["user_id"]);

		$this->tpl->assign('user', $user);
		$this->tpl->assign('authors', $authors);
		$this->tpl->assign('projects', $projects);
		$this->render('market_place');
	}

	function search() {
		$user = $this->getSessionUser();
		$market_dlgt = $this->getDelegate('market');

		if (empty($user) || $user['plan'] == MEMBER_BASIC || empty($user['plan'])) {
			$response["status"] = "Failure";
			echo json_encode($response);
			return;
		}

		if (isset($_POST["search"]) && !empty($_POST["search"])) {
			$string = "%".trim($_POST["search"])."%";
			if ($projects = $market_dlgt->search_project($user["user_id"], $string)) {
				$response["status"] = "Success";
				$response["data"] = "";
				
				foreach ($projects as $project) {
					$response["data"] .= '<div class="card-story"><h2><a href="/market/view/'.$project["id"].'">'.$project["title"].'</a><span class="text-muted" style="float:right; font-size:18px;">$'.$project["range_from"].' - $'.$project["range_to"].'</span></h2><div class="media media-card"><div class="media-left"><img src="/resource/img/user/'.$project["owner_image"].'" class="media-object"></div><div class="media-body"><ul class="list-story"><span class="list-dotted"></span><li>by <a href="/user/view/'.$project["owner_id"].'">'.$project["owner_name"].'</a></li><span class="list-dotted"></span><li class="mobile-block">Created '.$project["time"].'</li><span class="list-dotted"></span><li><i class="fa fa-gavel" aria-hidden="true"></i> <a>'.$project["stats"]["bids"].'</a></li>';
					
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

	function search_authors(){
		$user = $this->getSessionUser();
		$market_dlgt = $this->getDelegate('market');

		if (empty($user) || $user['plan'] == MEMBER_BASIC) {
			$response["status"] = "Failure";
			echo json_encode($response);
			return;
		}

		if (isset($_POST["author"]) && !empty($_POST["author"])) {
			$author = "%".trim($_POST["author"])."%";

			if($authors = $market_dlgt->search_authors($user["user_id"], $author)) {
				$response["status"] = "Success";
				$response["data"] = '';
				foreach ($authors as $author) {
					$response["data"] .= '<div class="aside-item" style="padding: 15px;"><div class="media media-card"><div class="media-left"><img src="/resource/img/user/'.$author["image"].'" style="height: 50px;width: 50px;" class="media-object"></div><div class="media-body" style="padding: 5px;"><ul class="list-story pull-left" style="margin: 0px;"><li><a href="/user/view/'.$author["id"].'">'.$author["name"].'</a></li></ul><ul class="list-story pull-right"><li><i class="fa fa-pencil" title="Stories" aria-hidden="true"></i> <a>'.$author["stats"]["stories"].'</a></li><span class="list-dotted"></span><li> <i class="fa fa-users" title="Stories Contributed" aria-hidden="true"></i> <a>'.$author["stats"]["collabs"].'</a></li><span class="list-dotted"></span><li> <i class="fa fa-tasks" title="Projects" aria-hidden="true"></i> <a>'.$author["stats"]["projects"].'</a></li></ul></div></div></div>';
				}
				echo json_encode($response);
				return;
			}

			$response["status"] = "Success";
			$response["data"] = '<div class="aside-item" style="padding: 15px;"><p align="center">No authors Found</div>';
			echo json_encode($response);
			return;
		}

		$response["status"] = "Failure";
		echo json_encode($response);
		return;
	}

	function create() {
		$user = $this->getSessionUser();
		$market_dlgt = $this->getDelegate('market');

		if (empty($user) || $user['plan'] == MEMBER_BASIC || empty($user['plan']))
			$this->redirect('/');

		if (isset($_POST['project-title'], $_POST['description'], $_POST['category'], $_POST['range-from'], $_POST['range-to'])) {
			$title = $_POST['project-title'];
			$description = $_POST['description'];
			$category = $_POST['category'];
			$range_from = $_POST['range-from'];
			$range_to = $_POST['range-to'];

			if ($project_id = $market_dlgt->create_project($user['user_id'], $title, $category, $description, $range_from, $range_to))
				$this->redirect("/market/view/".$project_id);
		}
		
		$this->redirect("/market");
	}

	function load_open_projects() {
		$user = $this->getSessionUser();
		$market_dlgt = $this->getDelegate('market');

		if (empty($user) || $user['plan'] == MEMBER_BASIC) {
			$response["status"] = "Failure";
			echo json_encode($response);
			return;
		}

		if (isset($_POST["last_id"]) && !empty($_POST["last_id"])) {
			$last_id = trim($_POST["last_id"]);
			
			if($projects = $market_dlgt->load_open_projects($user["user_id"], $last_id)) {
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
		$market_dlgt = $this->getDelegate('market');

		if (empty($user) || $user['plan'] == MEMBER_BASIC) {
			$response["status"] = "Failure";
			echo json_encode($response);
			return;
		}

		if (isset($_POST["last_id"]) && !empty($_POST["last_id"])) {
			$last_id = trim($_POST["last_id"]);
			
			if($projects = $market_dlgt->load_award_projects($user["user_id"], $last_id)) {
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

		if ($project["owner_id"] == $user["user_id"]) {
			$this->redirect('/projects/view/'.$project["id"]);
		}

		$user["bid"] = $market_dlgt->user_bid($user["user_id"], $project_id);
		$bids = $market_dlgt->get_bids($project_id);
		$awards = $market_dlgt->get_awards($project_id);

		$this->tpl->assign('user', $user);
		$this->tpl->assign('bids', $bids);
		$this->tpl->assign('awards', $awards);
		$this->tpl->assign('project', $project);
		$this->render('project_view');
	}

	function bid_project($project_id) {
		$user = $this->getSessionUser();
		$market_dlgt = $this->getDelegate('market');

		if (empty($user) || $user['plan'] != MEMBER_AUTHOR || empty($user['plan'])) {
			$response["status"] = "Failure";
			echo json_encode($response);
			return;
		}

		$project = $market_dlgt->get_project($project_id);

		if (isset($_POST['bid_amount'], $_POST['bid_days'], $_POST['bid_proposal']) && !empty($project)) {
			$bid_amount = $_POST['bid_amount'];
			$bid_days = $_POST['bid_days'];
			$bid_proposal = $_POST['bid_proposal'];

			if ($market_dlgt->bid_project($project_id, $user["user_id"], $bid_amount, $bid_days, $bid_proposal)) {
				$response["status"] = "Success";
				$response["data"] = '<div class="row"><div class="col-sm-5 col-sm-offset-3"><img src="/resource/img/tick_green.png" width="300"></div></div><h3 class="text-muted" align="center">Well Done! You have Successfully placed Bid.</h3>';
				echo json_encode($response);
				return;
			}
		}

		$response["status"] = "Failure";
		echo json_encode($response);
		return;
	}

	function bid_update($project_id) {
		$user = $this->getSessionUser();
		$market_dlgt = $this->getDelegate('market');

		if (empty($user) || $user['plan'] != MEMBER_AUTHOR || empty($user['plan'])) {
			$response["status"] = "Failure";
			echo json_encode($response);
			return;
		}

		$project = $market_dlgt->get_project($project_id);

		if (isset($_POST["bid_proposal"]) && !empty($project) && !empty($_POST["bid_proposal"])) {
			$bid_proposal = trim($_POST["bid_proposal"]);

			if ($market_dlgt->update_proposal($project_id, $user["user_id"], $bid_proposal)) {
				$response["status"] = "Success";
				$response["data"] = '<div class="row"><div class="col-sm-5 col-sm-offset-3"><img src="/resource/img/tick_green.png" width="300"></div></div><h3 class="text-muted" align="center">Well Done! You have Successfully submitted your proposal.</h3>';
				echo json_encode($response);
				return;
			}
		}

		$response["status"] = "Failure";
		echo json_encode($response);
		return;
	}
}
?>