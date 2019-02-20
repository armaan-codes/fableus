<?php

require("vendor/autoload.php");

use Carbon\Carbon;

class StoriesController extends BaseController
{
	protected $models = array('stories','story', 'user');

	/*	Revised as on 07-06-2018	*/

	function load_more_stories()
	{
		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate('story');
		
		$stories_dlgt = $this->getDelegate("stories");

		if (isset($_POST["last_id"], $_POST["filter"]) && !empty($_POST["last_id"])) {
			
			$last_id = trim($_POST["last_id"]);

			$filter = $_POST["filter"] ? trim($_POST["filter"]) : null;
			
			if ($stories = $stories_dlgt->load_more_stories($user["user_id"], $last_id, $filter)) {

				$response["status"] = "Success";

				$response["data"] = "";

				foreach ($stories as &$story) {

					$story["stats"] = $story_dlgt->story_stats($story["id"]);
					
					$story["user_role"] = $story_dlgt->get_user_role($story["id"], $user["user_id"]);
					
					$story["50_words"] = trim($story_dlgt->get_story($story["id"])["def_chapter"]["50_words"]);

					$response["data"] .= '<div class="card-story" data-id="'.$story["id"].'"><h2><a href="/story/view/'.$story["id"].'">'.$story["title"].'</a></h2><div class="media media-card"><div class="media-left"><img src="/resource/img/user/'.$story["owner_image"].'" class="media-object"></div><div class="media-body"><ul class="list-story"><li><a>';

					if ($story["type"] == NOVEL) {
						$response["data"].= 'Novel';
					} elseif ($story["type"] == SCREENPLAY) {
						$response["data"].= 'Screenplay';
					} elseif ($story["type"] == SHORT_STORY) {
						$response["data"].= 'Short Story';
					} elseif ($story["type"] == STORY) {
						$response["data"].= 'Story';
					}

					$response["data"] .='</a></li><span class="list-dotted"></span><li>by ';

					if(!empty($user["user_id"]))
						$response["data"] .= '<a href="/user/view/'.$story["owner_id"].'">';

					$response["data"] .= $story["owner_name"];

					if(!empty($user["user_id"]))
						$response["data"] .= '</a>';

					$response["data"] .= '</li><span class="list-dotted"></span><li class="mobile-block">Last Edited '.$story["time_updated"].'</li>';

					$response["data"] .= '</ul></div></div>';

					if(!empty($story["image"]))
						$response["data"] .= '<img src="/resource/img/story/'.$story["image"].'" class="image-preview" style="border-radius: 0px; width: 100%;">';

					$response["data"] .= '<hr><div class="card-story-footer"><ul class="list-inline list-unstyled pull-left mb0">';

					if($story["user_role"] >= ROLE_CONTRIBUTOR) {
						$response["data"] .= '<li><a href="/story/edit/'.$story["id"].'"><i class="fa fa-pencil"></i> Edit Story</a></li>';
					}

					if($story["user_role"] == ROLE_OWNER) {
						$response["data"] .= '<li><a class="text-danger"><i class="fa fa-minus-square"></i> Delete Story</a></li>';
					}
					
					$response["data"] .= '</ul><ul class="list-story-social hidden-xs"><li><i class="fa fa-users"></i> <a>'.$story["stats"]["no_contributors"].'</a></li><li><i class="fa fa-eye"></i> <a>'.$story["stats"]["no_views"].'</a></li><li><i class="fa fa-pencil"></i> <a>'.$story["stats"]["no_edits"].'</a></li><li><i class="fa fa-comment"></i> <a>'.$story["stats"]["no_comments"].'</a></li><li><i class="fa fa-star"></i> <a>'.$story["stats"]["rating"].'</a></li></ul>';

					if (!empty($story["50_words"]))
						$response["data"] .= '<div class="story-50-words">'.$story["50_words"].'...</div>';

					// if (!empty($story['collabs'])) {
					// 	$response["data"] .= '<p class="line-behind">Collaborators</p><ul class="list-contributor">';
					// 	foreach ($story["collabs"] as $collab) {
					// 		$response["data"] .= '<li><a href="/user/view/'.$collab["id"].'"><img src="/resource/img/user/'.$collab["image"].'" class="contributor-pic">'.$collab["name"].'</a></li>';
					// 	}

					// 	$response["data"] .= '</ul>';
					// }

					$response["data"] .= '</div></div>';

					$response["last_story_id"] = $story["id"];
				}

				echo json_encode( utf8ize( $response ) );

				return;

			}

			$response["status"] = "Null";

			$response["data"] = "No more stories";

			echo json_encode( utf8ize( $response ) );

			return;

		}

		$response["status"] = "Failure";
		
		echo json_encode( utf8ize( $response ) );
		
		return;

	}

	function index($args = array())
	{
		$user = $this->getSessionUser();

		if (!$user)
			$this->redirect("/");

		$story_dlgt = $this->getDelegate('story');
		
		$stories_dlgt = $this->getDelegate('stories');

		$filter = $_GET["filter"] ? trim($_GET["filter"]) : null;

		$types = $story_dlgt->get_story_types();
		
		$stories = $stories_dlgt->get_user_stories($user["user_id"], $filter);

		foreach ($stories as &$story) {

			$story["stats"] = $story_dlgt->story_stats($story["id"]);
			
			$story["user_role"] = $story_dlgt->get_user_role($story["id"], $user["user_id"]);
			
			$story["story_apply"] = $stories_dlgt->apply_check($story["id"], $user["user_id"]);
			
			$story["50_words"] = trim($story_dlgt->get_story($story["id"])["def_chapter"]["50_words"]);
			
			$last_story_id = $story["id"];
		
		}

		$other_stories = $stories_dlgt->get_other_stories($user["user_id"]);

		$now = Carbon::now();

		$best_story_check = $story_dlgt->check_best_story_of_month($now->format('m'), $now->format('Y'));

		if($best_story_check) {

			$best_story = $story_dlgt->best_stories($best_story_check['id']);

			$this->tpl->assign("best_story", $best_story[0]);
		
		}

		$this->tpl->assign("user", $user);
	
		$this->tpl->assign("filter", $filter);

		$this->tpl->assign("types", $types);

		$this->tpl->assign('stories', $stories);

		$this->tpl->assign('other_stories', $other_stories);
	
		$this->tpl->assign('last_story_id', $last_story_id);
	
		$this->tpl->assign('tab', array('stories' => true));
	
		$this->render('stories');
	
	}

	function load_user_stories()
	{

		$user = $this->getSessionUser();
		
		$stories_dlgt = $this->getDelegate("stories");
		
		$story_dlgt = $this->getDelegate('story');

		if (empty($user)) {
		
			$response["status"] = "Failure";
		
			echo json_encode($response);
		
			return;
		}

		if (isset($_POST["last_id"], $_POST["filter"]) && !empty($_POST["last_id"])) {
		
			$last_id = trim($_POST["last_id"]);

			$filter = $_POST["filter"] ? trim($_POST["filter"]) : null;

			if($stories = $stories_dlgt->load_user_stories($user["user_id"], $last_id, $filter)) {

				$response["status"] = "Success";

				$response["data"] = '';
				
				foreach ($stories as &$story) {
				
					$story["stats"] = $story_dlgt->story_stats($story["id"]);
				
					$story["user_role"] = $story_dlgt->get_user_role($story["id"], $user["user_id"]);
				
					$story["story_apply"] = $stories_dlgt->apply_check($story["id"], $user["user_id"]);
				
					$story["50_words"] = trim($story_dlgt->get_story($story["id"])["def_chapter"]["50_words"]);

					$response["data"] .= '<div class="card-story" id="'.$story["id"].'"><h2><a href="/story/view/'.$story["id"].'">'.$story["title"].'</a></h2><div class="media media-card"><div class="media-left"><img src="/resource/img/user/'.$story["owner_image"].'" class="media-object"></div><div class="media-body"><ul class="list-story"><li><a>';

					if ($story["type"] == NOVEL) {
						$response["data"].= 'Novel';
					} elseif ($story["type"] == SCREENPLAY) {
						$response["data"].= 'Screenplay';
					} elseif ($story["type"] == SHORT_STORY) {
						$response["data"].= 'Short Story';
					} elseif ($story["type"] == STORY) {
						$response["data"].= 'Story';
					}

					$response["data"] .='</a></li><span class="list-dotted"></span><li>by <a href="/user/view/'.$story["owner_id"].'">'.$story["owner_name"].'</a></li><span class="list-dotted"></span><li class="mobile-block">Last Edited '.$story["time_updated"].'</li>';

					if ($story["publish"] == DEVELOPMENT) {
						$response["data"] .= '<span class="list-dotted"></span><li class="mobile-block"><span style="color: red; font-weight: 600;">Development</span></li>';
					}

					$response["data"] .= '</ul></div></div>';

					if(!empty($story["image"]))
						$response["data"] .= '<img src="/resource/img/story/'.$story["image"].'" class="image-preview" style="border-radius: 0px; width: 100%;">';

					$response["data"] .= '<hr><div class="card-story-footer"><ul class="list-inline list-unstyled pull-left mb0">';


					if($story["user_role"] == ROLE_OWNER) {
						
						$response["data"] .= '<li><a href="/story/edit/'.$story["id"].'"><i class="fa fa-pencil"></i> Edit Story</a></li><li><a class="text-danger"><i class="fa fa-minus-square"></i> Delete Story</a></li>';
						
						if (!empty($story["story_apply"]))
							$response["data"] .= '<li><button class="story-apply-request"><i class="fa fa-envelope-o" aria-hidden="true"></i> Requests</button></li>';
					
					}
					
					$response["data"] .= '</ul><ul class="list-story-social hidden-xs"><li><i class="fa fa-users"></i> <a>'.$story["stats"]["no_contributors"].'</a></li><li><i class="fa fa-eye"></i> <a>'.$story["stats"]["no_views"].'</a></li><li><i class="fa fa-pencil"></i> <a>'.$story["stats"]["no_edits"].'</a></li><li><i class="fa fa-comment"></i> <a>'.$story["stats"]["no_comments"].'</a></li><li><i class="fa fa-star"></i> <a>'.$story["stats"]["rating"].'</a></li></ul>';

					if (!empty($story["50_words"]))
						$response["data"] .= '<br><div class="story-50-words">'.$story["50_words"].'...</div>';

					// if (!empty($story['collabs'])) {
					
					// 	$response["data"] .= '<p class="line-behind">Collaborators</p><ul class="list-contributor">';
					
					// 	foreach ($story["collabs"] as $collab) {
					
					// 		$response["data"] .= '<li><a href="/user/view/'.$collab["id"].'"><img src="/resource/img/user/'.$collab["image"].'" class="contributor-pic">'.$collab["name"].'</a></li>';
					
					// 	}

					// 	$response["data"] .= '</ul>';
					
					// }

					$response["data"] .= '</div></div>';

					$response["last_story_id"] = $story["id"];
				
				}

				echo json_encode( utf8ize( $response ) );
				
				return;
			
			}

			$response["status"] = "New";
			
			$response["data"] = "Write your next story!";
			
			echo json_encode( utf8ize( $response ) );
			
			return;
		
		}

		$response["status"] = "Failure";
		
		echo json_encode( utf8ize( $response ) );
		
		return;
	
	}

	function get_apply_request($story_id)
	{

		$user = $this->getSessionUser();
	
		$stories_dlgt = $this->getDelegate("stories");

		if (empty($user)) {
	
			return;
	
		}
		
		$response = "";

		if ($request = $stories_dlgt->get_apply_request($story_id, $user["user_id"])) {
		
			foreach ($request as $user) {
		
				$response .= '<div class="row">
								<div class="col-sm-6">
									<img src="/resource/img/user/'.$user["image"].'" class="media-request">
									<span class="list-dotted"></span>
									<span class="media-request-text">
										by <a href="/user/view/'.$user["id"].'">'.$user["name"].'</a>
									</span>
								</div>
								<div class="col-sm-3 request-action">
									<a href="/stories/accept_apply/'.$story_id.'/'.$user["id"].'" class="btn btn-info btn-block text-uppercase">
										Accept
									</a>
								</div>
								<div class="col-sm-3 request-action">
									<a href="/stories/decline_apply/'.$story_id.'/'.$user["id"].'" class="btn btn-info btn-block text-uppercase">
										Decline
									</a>
								</div>
							</div>
							<hr>';
			}

			echo $response;
			
			return;
		
		}

		$response .= '<div class="row"><div class="col-sm-6 col-sm-offset-3"><p align="center">No Request Found.</p></div></div>';

		echo $response;

		return;

	}

	function collabs()
	{
		
		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate('story');
		
		$stories_dlgt = $this->getDelegate('stories');

		if (!$user)
			$this->redirect("/");

		$filter = $_GET["filter"] ? trim($_GET["filter"]) : null;

		$types = $story_dlgt->get_story_types();

		$stories = $stories_dlgt->user_collab_stories($user["user_id"], $filter);
		
		foreach ($stories as &$story) {
		
			$story["stats"] = $story_dlgt->story_stats($story["id"]);
		
			$story["user_role"] = $story_dlgt->get_user_role($story["id"], $user["user_id"]);
		
			$story["50_words"] = trim($story_dlgt->get_story($story["id"])["def_chapter"]["50_words"]);
		
			$last_story_id = $story["id"];
		
		}

		$invites = $stories_dlgt->get_stories_invite($user["user_id"]);
		
		foreach ($invites["contribution"] as &$story) {
		
			$story["stats"] = $story_dlgt->story_stats($story["id"]);
		
			$story["50_words"] = trim($story_dlgt->get_story($story["id"])["def_chapter"]["50_words"]);
		
		}

		foreach ($invites["contribution_read"] as &$story) {
		
			$story["stats"] = $story_dlgt->story_stats($story["id"]);
		
			$story["50_words"] = trim($story_dlgt->get_story($story["id"])["def_chapter"]["50_words"]);
		
		}

		$this->tpl->assign("user", $user);
		
		$this->tpl->assign("invites", $invites);
		
		$this->tpl->assign("stories", $stories);

		$this->tpl->assign("filter", $filter);

		$this->tpl->assign("types", $types);
		
		$this->tpl->assign("last_story_id", $last_story_id);
		
		$this->tpl->assign('tab', array('collabs' => true));
		
		$this->render("stories_collab");
	
	}

	function load_collab_stories()
	{

		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate('story');
		
		$stories_dlgt = $this->getDelegate("stories");

		if (empty($user)) {
		
			$response["status"] = "Failure";
		
			echo json_encode($response);
		
			return;
		
		}

		if (isset($_POST["last_id"], $_POST["filter"]) && !empty($_POST["last_id"])) {
		
			$last_id = $_POST["last_id"];

			$filter = $_POST["filter"] ? trim($_POST["filter"]) : null;

			if ($stories = $stories_dlgt->load_collab_stories($user["user_id"], $last_id, $filter)) {

				$response["status"] = "Success";
				
				$response["data"] = '';

				foreach ($stories as &$story) {
				
					$story["stats"] = $story_dlgt->story_stats($story["id"]);
				
					$story["user_role"] = $story_dlgt->get_user_role($story["id"], $user["user_id"]);
				
					$story["50_words"] = trim($story_dlgt->get_story($story["id"])["def_chapter"]["50_words"]);
				
					$response["data"] .= '<div class="card-story" id="'.$story["id"].'"><h2><a href="/story/view/'.$story["id"].'">'.$story["title"].'</a></h2><div class="media media-card"><div class="media-left"><img src="/resource/img/user/'.$story["owner_image"].'" class="media-object"></div><div class="media-body"><ul class="list-story"><li><a>';

					if ($story["type"] == NOVEL) {
						$response["data"].= 'Novel';
					} elseif ($story["type"] == SCREENPLAY) {
						$response["data"].= 'Screenplay';
					} elseif ($story["type"] == SHORT_STORY) {
						$response["data"].= 'Short Story';
					} elseif ($story["type"] == STORY) {
						$response["data"].= 'Story';
					}

					$response["data"] .='</a></li><span class="list-dotted"></span><li>by <a href="/user/view/'.$story["owner_id"].'">'.$story["owner_name"].'</a></li><span class="list-dotted"></span><li class="mobile-block">Last Edited '.$story["time_updated"].'</li>';

					if ($story["publish"] == DEVELOPMENT) {
						$response["data"] .= '<span class="list-dotted"></span><li class="mobile-block"><span style="color: red; font-weight: 600;">Development</span></li>';
					}

					$response["data"] .= '</ul></div></div>';

					if(!empty($story["image"]))
						$response["data"] .= '<img src="/resource/img/story/'.$story["image"].'" class="image-preview" style="border-radius: 0px; width: 100%;">';

					$response["data"] .= '<hr><div class="card-story-footer"><ul class="list-inline list-unstyled pull-left mb0">';

					if($story["user_role"] == ROLE_CONTRIBUTOR) {
						$response["data"] .= '<li><a href="/story/edit/'.$story["id"].'"><i class="fa fa-pencil"></i> Edit Story</a></li>';
					}
					
					$response["data"] .= '</ul><ul class="list-story-social hidden-xs"><li><i class="fa fa-users"></i> <a>'.$story["stats"]["no_contributors"].'</a></li><li><i class="fa fa-eye"></i> <a>'.$story["stats"]["no_views"].'</a></li><li><i class="fa fa-pencil"></i> <a>'.$story["stats"]["no_edits"].'</a></li><li><i class="fa fa-comment"></i> <a>'.$story["stats"]["no_comments"].'</a></li><li><i class="fa fa-star"></i> <a>'.$story["stats"]["rating"].'</a></li></ul>';

					if (!empty($story["50_words"]))
						$response["data"] .= '<div class="story-50-words">'.$story["50_words"].'...</div>';

					// if (!empty($story['collabs'])) {
					// 	$response["data"] .= '<p class="line-behind">Collaborators</p><ul class="list-contributor">';
					// 	foreach ($story["collabs"] as $collab) {
					// 		$response["data"] .= '<li><a href="/user/view/'.$collab["id"].'"><img src="/resource/img/user/'.$collab["image"].'" class="contributor-pic">'.$collab["name"].'</a></li>';
					// 	}

					// 	$response["data"] .= '</ul>';
					// }

					$response["data"] .= '</div>';

					$response["last_story_id"] = $story["id"];
				}

				echo json_encode( utf8ize( $response ) );
				return;
			}

			$response["status"] = "Null-Collab";
			$response["data"] = "Apply to start your next collaboration!";
			echo json_encode( utf8ize( $response ) );
			return;
		}

		$response["status"] = "Failure";
		echo json_encode( utf8ize( $response ) );
		return;
	}

	function accept($story_id)
	{

		$user = $this->getSessionUser();
		
		$user_dlgt = $this->getDelegate("user");
		
		$story_dlgt = $this->getDelegate("story");
		
		$stories_dlgt = $this->getDelegate("stories");

		$story = $story_dlgt->story_check($story_id);

		$user["role"] = $story_dlgt->get_user_role($story["story_id"], $user["user_id"]);
		
		$check = $story_dlgt->invite_check($story["story_id"], $story["user_id"], $user["user_id"]);

		if (empty($user) || !$story || $user["role"] > ROLE_CONTRIBUTOR_READER || !$check)
			$this->redirect("/");

		$story_owner = $user_dlgt->get_user($story["user_id"]);

		if($stories_dlgt->accept_invite($check["invite_id"], $user["user_id"], $story["story_id"], $story["chat_room"]))
			$this->invite_accept_mail($story["title"], $user["name"], $story_owner["email"], $story_owner["name"]);
		
		$this->redirect("/stories/collabs");
	
	}

	function decline($story_id)
	{

		$user = $this->getSessionUser();

		$user_dlgt = $this->getDelegate("user");
		
		$story_dlgt = $this->getDelegate("story");
		
		$stories_dlgt = $this->getDelegate("stories");

		$story = $story_dlgt->story_check($story_id);

		$user["role"] = $story_dlgt->get_user_role($story["story_id"], $user["user_id"]);
		
		$check = $story_dlgt->invite_check($story["story_id"], $story["user_id"], $user["user_id"]);

		if (empty($user) || !$story || $user["role"] > ROLE_CONTRIBUTOR_READER || !$check)
			$this->redirect("/");

		$story_owner = $user_dlgt->get_user($story["user_id"]);

		if($stories_dlgt->decline_invite($check["invite_id"]))
			$this->invite_decline_mail($story["title"], $user["name"], $story_owner["email"], $story_owner["name"]);
		
		$this->redirect("/stories/collabs");
	
	}

	function accept_apply($story_id, $collab_id)
	{

		$user = $this->getSessionUser();

		$user_dlgt = $this->getDelegate("user");
		
		$story_dlgt = $this->getDelegate("story");
		
		$stories_dlgt = $this->getDelegate("stories");

		$user["role"] = $story_dlgt->get_user_role($story_id, $user["user_id"]);

		$collab = $user_dlgt->get_user($collab_id);
		
		$collab["role"] = $story_dlgt->get_user_role($story_id, $collab_id);
		
		$story = $story_dlgt->story_check($story_id);
		
		$check = $story_dlgt->apply_check($story_id, $user["user_id"], $collab_id);

		if (empty($user) || !$story || $collab["role"] > ROLE_CONTRIBUTOR_READER || $user["role"] != ROLE_OWNER || !$check)
			$this->redirect("/");

		if($stories_dlgt->accept_apply($check["apply_id"], $collab_id, $story_id, $story["chat_room"]))
			$this->apply_accept_mail($story["title"], $user["name"], $collab["email"], $collab["name"]);

		$this->redirect("/stories");
	
	}

	function decline_apply($story_id, $collab_id)
	{

		$user = $this->getSessionUser();
		
		$user_dlgt = $this->getDelegate("user");
		
		$story_dlgt = $this->getDelegate("story");
		
		$stories_dlgt = $this->getDelegate("stories");

		$user["role"] = $story_dlgt->get_user_role($story_id, $user["user_id"]);

		$collab = $user_dlgt->get_user($collab_id);
		
		$collab["role"] = $story_dlgt->get_user_role($story_id, $collab_id);
		
		$story = $story_dlgt->story_check($story_id);
		
		$check = $story_dlgt->apply_check($story_id, $user["user_id"], $collab_id);

		if (empty($user) || !$story || $collab["role"] > ROLE_CONTRIBUTOR_READER || $user["role"] != ROLE_OWNER || !$check)
			$this->redirect("/");

		if($stories_dlgt->decline_apply($check["apply_id"]))
			$this->apply_decline_mail($story["title"], $user["name"], $collab["email"], $collab["name"]);
		
		$this->redirect("/stories");
	
	}

	function load_member_stories($member_id)
	{

		$user = $this->getSessionUser();
		
		$user_dlgt = $this->getDelegate("user");
		
		$story_dlgt = $this->getDelegate('story');
		
		$stories_dlgt = $this->getDelegate("stories");

		$member = $user_dlgt->get_user($member_id);

		if (isset($_POST["last_id"]) && !empty($_POST["last_id"]) && $user && $member) {

			$last_id = $_POST["last_id"];
			
			$filter = $_POST["filter"] ? trim($_POST["filter"]) : null;

			if ($stories = $stories_dlgt->load_member_stories($member["user_id"], $last_id, $filter)) {
				$response["status"] = "Success";
				$response["data"] = '';

				foreach ($stories as &$story) {
					$story["stats"] = $story_dlgt->story_stats($story["id"]);
					$story["user_role"] = $story_dlgt->get_user_role($story["id"], $user["user_id"]);
					$story["50_words"] = trim($story_dlgt->get_story($story["id"])["def_chapter"]["50_words"]);

					$response["data"] .= '<div class="card-story" id="'.$story["id"].'" data-updated="'.$story["ts_updated"].'" data-top="'.$story["stats"]["top"].'"><h2><a href="/story/view/'.$story["id"].'">'.$story["title"].'</a></h2><div class="media media-card"><div class="media-left"><img src="/resource/img/user/'.$story["owner_image"].'" class="media-object"></div><div class="media-body"><ul class="list-story"><li><a>';

					if ($story["type"] == NOVEL) {
						$response["data"].= 'Novel';
					} elseif ($story["type"] == SCREENPLAY) {
						$response["data"].= 'Screenplay';
					} elseif ($story["type"] == SHORT_STORY) {
						$response["data"].= 'Short Story';
					} elseif ($story["type"] == STORY) {
						$response["data"].= 'Story';
					}

					$response["data"] .='</a></li><span class="list-dotted"></span><li>by <a href="/user/view/'.$story["owner_id"].'">'.$story["owner_name"].'</a></li><span class="list-dotted"></span><li class="mobile-block">Last Edited '.$story["time_updated"].'</li>';

					$response["data"] .= '</ul></div></div><hr><div class="card-story-footer"><ul class="list-inline list-unstyled pull-left mb0">';

					if($story["user_role"] == ROLE_CONTRIBUTOR) {
						$response["data"] .= '<li><a href="/story/edit/'.$story["id"].'"><i class="fa fa-pencil"></i> Edit Story</a></li>';
					}
					
					$response["data"] .= '</ul><ul class="list-story-social hidden-xs"><li><i class="fa fa-users"></i> <a>'.$story["stats"]["no_contributors"].'</a></li><li><i class="fa fa-eye"></i> <a>'.$story["stats"]["no_views"].'</a></li><li><i class="fa fa-pencil"></i> <a>'.$story["stats"]["no_edits"].'</a></li><li><i class="fa fa-comment"></i> <a>'.$story["stats"]["no_comments"].'</a></li><li><i class="fa fa-star"></i> <a>'.$story["stats"]["rating"].'</a></li></ul>';

					if (!empty($story["50_words"]))
						$response["data"] .= '<div class="story-50-words">'.$story["50_words"].'...</div>';

					// if (!empty($story['collabs'])) {
					// 	$response["data"] .= '<p class="line-behind">Collaborators</p><ul class="list-contributor">';
					// 	foreach ($story["collabs"] as $collab) {
					// 		$response["data"] .= '<li><a href="/user/view/'.$collab["id"].'"><img src="/resource/img/user/'.$collab["image"].'" class="contributor-pic">'.$collab["name"].'</a></li>';
					// 	}

					// 	$response["data"] .= '</ul>';
					// }

					$response["data"] .= '</div>';

					$response["last_story_id"] = $story["id"];
				}
				echo json_encode( utf8ize( $response ) );
				return;
			}

			$response["status"] = "Null";
			$response["data"] = "No more stories";
			echo json_encode( utf8ize( $response ) );
			return;
		}

		$response["status"] = "Failure";
		echo json_encode( utf8ize( $response ) );
		return;
	}

	function accept_read($story_id)
	{

		$user = $this->getSessionUser();
		
		$user_dlgt = $this->getDelegate("user");
		
		$story_dlgt = $this->getDelegate("story");
		
		$stories_dlgt = $this->getDelegate("stories");

		$story = $story_dlgt->story_check($story_id);

		$user["role"] = $story_dlgt->get_user_role($story["story_id"], $user["user_id"]);
		
		$check = $story_dlgt->invite_read_check($story["story_id"], $story["user_id"], $user["user_id"]);

		if (empty($user) || !$story || $user["role"] != ROLE_READER || !$check)
			$this->redirect("/");

		$story_owner = $user_dlgt->get_user($story["user_id"]);

		if($stories_dlgt->accept_invite_read($check["invite_id"], $user["user_id"], $story["story_id"], $story["chat_room"]))
			$this->invite_read_accept_mail($story["title"], $user["name"], $story_owner["email"], $story_owner["name"]);
		
		$this->redirect("/stories/collabs");
	
	}

	function decline_read($story_id)
	{

		$user = $this->getSessionUser();

		$user_dlgt = $this->getDelegate("user");
		
		$story_dlgt = $this->getDelegate("story");
		
		$stories_dlgt = $this->getDelegate("stories");

		$story = $story_dlgt->story_check($story_id);

		$user["role"] = $story_dlgt->get_user_role($story["story_id"], $user["user_id"]);
		
		$check = $story_dlgt->invite_read_check($story["story_id"], $story["user_id"], $user["user_id"]);

		if (empty($user) || !$story || $user["role"] != ROLE_READER || !$check)
			$this->redirect("/");

		$story_owner = $user_dlgt->get_user($story["user_id"]);

		if($stories_dlgt->decline_invite_read($check["invite_id"]))
			$this->invite_read_decline_mail($story["title"], $user["name"], $story_owner["email"], $story_owner["name"]);
		
		$this->redirect("/stories/collabs");
	
	}

}