<?php

require("resource/vendor/autoload.php");

require("vendor/autoload.php");

use Carbon\Carbon;

class StoryController extends BaseController
{
	protected $models = array('story');

	/*	Revised Code as on 08/06/2018	*/

	function view($story_id, $title_part_id = 0, $parent_part_id = 0)
	{
	
		$user = $this->getSessionUser();

		$story_dlgt = $this->getDelegate("story");

		$story = $story_dlgt->story_check($story_id);

		if (!$story)
			$this->redirect("/");

		$user_dlgt = $this->getDelegate("user");
		
		$story = $story_dlgt->get_story($story["story_id"], $title_part_id, true);

		$story["owner_followers"] = $user_dlgt->get_followers($story["owner_id"]);
		
		$user["role"] = $story_dlgt->get_user_role($story["id"], $user["user_id"]);

		if ($story["publish"] == 0 && $user["role"] < ROLE_CONTRIBUTOR_READER)
			$this->redirect("/");

		if($user["role"] < ROLE_OWNER)
			$user["rating"] = $story_dlgt->check_user_rating($story["id"], $user["user_id"]);

		if(!$story["def_chapter"]["publish"])
			$this->redirect("/story/view/" . $story["slug"]);

		$comments = $story_dlgt->story_comments($story["id"]);
		
		$collabs = $story_dlgt->story_active_contributors($story["id"]);
		
		$stats = $story_dlgt->story_stats($story["id"]);

		if ($user["user_id"] != $story["owner_id"]) {

			$vote = $story_dlgt->get_user_vote($user["user_id"], $story["id"]);
			
			$this->tpl->assign("vote", $vote);
		
		}

		if ($user["user_id"] == $story["owner_id"]) {
		
			$team_dlgt = $this->getDelegate("team");

			$teams = $team_dlgt->get_teams($user["user_id"]);
		
			$analysis = $story_dlgt->get_analysis($story["id"]);

			$this->tpl->assign("teams", $teams);
		
			$this->tpl->assign("analysis", $analysis);
		
		}

		$story_dlgt->add_view($story["id"]);

		if (!$title_part_id && !$parent_part_id) {
		
			$title_part_id = $story["def_chapter"]["title_part_id"];
		
			$parent_part_id = $story["id"];
		
		}

		$story["last_message_id"] = $story_dlgt->last_message_id($story["room_id"]) ? $story_dlgt->last_message_id($story["room_id"]) : 0;

		$follow_check = array('member_id' => $story["owner_id"], 'member_follower_id' => $user['user_id']);

		$this->tpl->assign("user", $user);
		
		$this->tpl->assign("stats", $stats);
		
		$this->tpl->assign("story", $story);

		$this->tpl->assign('follow_check', $follow_check);
		
		$this->tpl->assign("collabs", $collabs);
		
		$this->tpl->assign("comments", $comments);

		$this->tpl->assign("title_part_id", $title_part_id);
		
		$this->tpl->assign("parent_part_id", $parent_part_id);
		
		$this->render("story_view");
	
	}

	function comment($story_id)
	{
		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate("story");

		if (isset($_POST["comment-message"]) && !empty($_POST["comment-message"]) && !empty($user["user_id"]) && $story = $story_dlgt->story_check($story_id)) {
			
			$message = trim($_POST["comment-message"]);
			
			$story_dlgt->add_comment($story["story_id"], $user["user_id"], $message);
		
		}

		$this->redirect($_SERVER["HTTP_REFERER"]);
	
	}

	function edit_comment($comment_id)
	{

		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate("story");

		if (empty($user) && !isset($_POST['message']))
			$this->redirect("/");

		$message = trim($_POST['message']);
		
		$comment = $story_dlgt->get_comment($user["user_id"], $comment_id);
		
		if($user["user_id"] == $comment["user_id"]) {

			$story_dlgt->update_comment($comment["id"], $message);
		
		}

		$this->redirect('/story/view/'.$comment["story_id"]);

	}

	function pdf_export($story_id)
	{

		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate("story");

		$story = $story_dlgt->story_check($story_id);

		if (!$story)
			$this->redirect("/");

		$user["role"] = $story_dlgt->get_user_role($story["story_id"], $user["user_id"]);

		if ($user["role"]  != ROLE_OWNER)
			$this->redirect("/");


		$story = $story_dlgt->get_story($story["story_id"]);

		$story["active_collab"] = $story_dlgt->story_active_contributors($story["sid"]);

		$mpdf = new \Mpdf\Mpdf();

		$html = '<div style="position: fixed;top: 20%; width: 100%; text-align:center;"><h1 align="center">'.$story['title'].'</h1><br><br>';

		if($story["image"])
			$html .= '<img src="/resource/img/story/'.$story["image"].'" width="'.$story["image_width"].'" height="'.$story["image_height"].'" style="margin: 25px auto;">';

		$html .= '</div>';

		$mpdf->WriteHTML($html);
		
		$mpdf->AddPage();	
		
		$mpdf->TOCpagebreak();

		$mpdf->DeletePages($mpdf->page);

		foreach ($story['toc'] as $chapter) { 
		
			$this->chapter_page_pdf($mpdf, $chapter, 0);
		
		}

		$mpdf->AddPage();

		$html = '<h2 align="center">About the Author(s)</h2>';

		$html .= '<div style="padding: 25px;">';

		foreach ($story["active_collab"] as $collab) {

			$html .= '<div style="width: 100%; margin: 25px auto;">';

			$html .= '<div style="width: 25%; float:left; text-align: center;">';

			$html .= '<img src="/resource/img/user/' . $collab["image"] . '" />';
			
			$html .= '</div>';

			$html .= '<div style="width: 70%; float:right; text-align: left;">';

			$html .= '<b>';

			if(empty($collab["first_name"]) && empty($collab["first_name"])) {

				$html .= $collab["name"];

			} else {

				$html .= $collab["first_name"] . ' ' . $collab["last_name"];

			}

			$html .= '.</b> ' . $collab["bio"];
			
			$html .= '</div>';

			$html .= '</div>';
		}

		$html .= '</div>';

		$mpdf->WriteHTML($html);

		$mpdf->AddPage();

		$html = '<div style="text-align:center; position: fixed; bottom: 0; right:0; width:75%"><img src="/resource/img/elements/book-icons.png" width="150"><br><p style="color:black; font-size:14px;font-weight:400"><i>This story was plotted, collaborated, and developed on the fableus.com, a story writing platform using a number of tools that the platform provides.<br><br>This story reflects only the authors view and fableus.com does not influence in any way.</i></p></div>';

		$mpdf->WriteHTML($html);

		$mpdf->Output($story['title'].'.pdf', \Mpdf\Output\Destination::INLINE);

	}


	function chapter_page_pdf($pdf, $chapter, $child = 0)
	{
	
		$story_dlgt = $this->getDelegate("story");
	
		$story = $story_dlgt->get_story($chapter[TOC_DATA_KEY]['story_id'], $chapter[TOC_DATA_KEY]['part_id']);

		$pdf->setFooter("Generated By Fableus.com| |{PAGENO} of {nb}");
		
		$pdf->AddPage();

		$pdf->TOC_Entry( $story['def_chapter']['title'], $child);

		$html = '';

		$html .= '<h2 align="center">'.$story['def_chapter']['title'].'</h2>';

		$html .= $story['def_chapter']['body'];

		$pdf->WriteHTML($html);

		if(!empty($chapter[TOC_CHILDREN_KEY])) {
			
			$child++;
	
			foreach ($chapter[TOC_CHILDREN_KEY] as $child_chap) {

				$this->chapter_page_pdf($pdf, $child_chap, $child);
	
			}
	
		}

	}

	function publish_story($story_id)
	{

		$user = $this->getSessionUser();
		
		$user_dlgt = $this->getDelegate("user");
		
		$story_dlgt = $this->getDelegate("story");

		$story = $story_dlgt->story_check($story_id);
		
		$user["role"] = $story_dlgt->get_user_role($story["story_id"], $user["user_id"]);

		if (empty($user) || !$story || $user["role"] != ROLE_OWNER) {
		
			$response["status"] = "Failure";
		
			$response["data"] = 'Not Authorised';
		
			echo json_encode($response);
		
			return;
		
		}

		if ($story_dlgt->publish_story($story["story_id"])) {
		
			$response["status"] = "Success";
		
			$response["data"] = "Story Published.";
		
			echo json_encode($response);
		
			return;
		
		}

		$response["status"] = "Failure";
		
		$response["data"] = 'Something Went Wrong.';
		
		echo json_encode($response);
		
		return;
	
	}

	function unpublish_story($story_id)
	{

		$user = $this->getSessionUser();
		
		$user_dlgt = $this->getDelegate("user");
		
		$story_dlgt = $this->getDelegate("story");

		$story = $story_dlgt->story_check($story_id);
		
		$user["role"] = $story_dlgt->get_user_role($story["story_id"], $user["user_id"]);

		if (empty($user) || !$story || $user["role"] != ROLE_OWNER) {
		
			$response["status"] = "Failure";
		
			$response["data"] = 'Not Authorised';
		
			echo json_encode($response);
		
			return;
		
		}
		

		if ($story_dlgt->unpublish_story($story["story_id"])) {
		
			$response["status"] = "Success";
		
			$response["data"] = "Story Un-Published.";
		
			echo json_encode($response);
		
			return;
		
		}

		$response["status"] = "Failure";
		
		$response["data"] = 'Something Went Wrong.';
		
		echo json_encode($response);
		
		return;
	
	}

	function invite_collab($story_id)
	{

		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate("story");
		
		$user_dlgt = $this->getDelegate("user");
		
		$user["role"] = $story_dlgt->get_user_role($story_id, $user["user_id"]);
		
		$story = $story_dlgt->story_check($story_id);

		if (empty($user) || !$story || $user["role"] != ROLE_OWNER) {
		
			$response["status"] = "Failure";
		
			$response["data"] = 'Not Authorised';
		
			echo json_encode($response);
		
			return;
		
		}

		if (isset($_POST["collab_mail"]) && !empty($_POST["collab_mail"]) && $this->email_verification($_POST["collab_mail"])) {
			
			if ($collab = $user_dlgt->get_user_by_email(trim($_POST["collab_mail"]))) {

				$collab["role"] = $story_dlgt->get_user_role($story_id, $collab["user_id"]);

				if ($collab["role"] == ROLE_CONTRIBUTOR || $collab["role"] == ROLE_OWNER) {
				
					$response["status"] = "Failure";
				
					$response["data"] = 'Already a Contributor';
				
					echo json_encode($response);
				
					return;
				
				}

				if($story_dlgt->invite_check($story_id, $user["user_id"], $collab["user_id"])) {

					$response["status"] = "Failure";
				
					$response["data"] = 'Invite request already exits';
				
					echo json_encode($response);
				
					return;

				}

				if($story_dlgt->apply_check($story_id, $user["user_id"], $collab["user_id"])) {

					$response["status"] = "Failure";
				
					$response["data"] = 'User has already requested to contribute. Kindly review the request';
				
					echo json_encode($response);
				
					return;

				}
				
				$invite_id = $story_dlgt->invite_collab($story_id, $user["user_id"], $collab["user_id"]);
				
				$mail = $this->send_invite_mail($story['title'], $user['email'], $user['name'], $collab['email'], $collab['name']);
				
				$response["status"] = "Success";
				
				$response["data"] = 'Invite Sent';
				
				echo json_encode($response);
				
				return;
			
			}

			$response["status"] = "Success";
			
			$response["data"] = 'User is not registered with story. An email has been sent to user for registering with us';
			
			$this->send_invite_unregistered_user($user['email'], $user['name'], trim($_POST["collab_mail"]));

			$story_dlgt->non_member_invite($story["story_id"], $_POST["collab_mail"], ROLE_CONTRIBUTOR);
			
			echo json_encode($response);
			
			return;
		}

		$response["status"] = "Failure";
		
		$response["data"] = 'Invalid email. Enter a valid email address';
	
		echo json_encode($response);
	
		return;
	
	}

	function invite_team($story_id)
	{

		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate("story");
		
		$user_dlgt = $this->getDelegate("user");
		
		$team_dlgt = $this->getDelegate("team");
		
		$user["role"] = $story_dlgt->get_user_role($story_id, $user["user_id"]);
		
		$story = $story_dlgt->story_check($story_id);

		if (empty($user) || !$story || $user["role"] != ROLE_OWNER) {
		
			$response["status"] = "Failure";
		
			$response["data"] = 'Not Authorised';
		
			echo json_encode($response);
		
			return;
		
		}

		if (isset($_POST["team_id"]) && !empty($_POST["team_id"])) {

			if($team_members = $team_dlgt->get_team($user["user_id"], $_POST["team_id"])["members"]) {
			
				foreach ($team_members as $member) {
			
					if(!$story_dlgt->invite_check($story_id, $user["user_id"], $member["id"]) && !$story_dlgt->apply_check($story_id, $user["
						user_id"], $member["id"])) {
						
						$invite_id = $story_dlgt->invite_collab($story_id, $user["user_id"], $member["id"]);
						
					}
				
					$mail = $this->send_invite_mail($story['title'], $user['email'], $user['name'], $member['email'], $member['name']);
					
				}

				$response["status"] = "Success";
				
				$response["data"] = 'Successfully invited all team Members';
				
				echo json_encode($response);
				
				return;
			
			}

			$response["status"] = "Failure";
			
			$response["data"] = 'No Members in team';
			
			echo json_encode($response);
			
			return;

		}

		$response["status"] = "Failure";
			
		$response["data"] = 'Something went wrong';
		
		echo json_encode($response);
		
		return;

	}

	function up_vote($story_id)
	{

		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate("story");

		if (empty($user) || !$story_dlgt->story_check($story_id)) {
		
			$response["status"] = "Failure";
		
			$response["data"] = $_SERVER["HTTP_REFERER"];
		
			echo json_encode($response);
		
			return;
		
		}

		if (!$story_dlgt->get_user_vote($user["user_id"], $story_id)) {
		
			$story_dlgt->up_vote($user["user_id"], $story_id);
		
			$response["status"] = "Success";
		
			$response["data"] = "Thanks for Voting!";
		
			echo json_encode($response);
		
			return;
		
		}

		$response["status"] = "Failure";
		
		$response["data"] = $_SERVER["HTTP_REFERER"];
		
		echo json_encode($response);
		
		return;
	
	}

	function edit($story_id, $title_part_id = 0, $parent_part_id = 0)
	{

		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate("story");

		$story = $story_dlgt->story_check($story_id);

		if (empty($user) || !$story)
			$this->redirect("/");

		$user["role"] = $story_dlgt->get_user_role($story["story_id"], $user["user_id"]);

		if ($user["role"] < ROLE_CONTRIBUTOR)
			$this->redirect("/story/view/".$story_id);

		if ($story["editor"] != null && $story["editor"] != $user["user_id"])
			$this->redirect("/story/view/".$story_id);
		else
			$story_dlgt->update_story_editor($story["story_id"], $user["user_id"]);

		$story = $story_dlgt->get_story($story["story_id"], $title_part_id);

		if (!$title_part_id && !$parent_part_id) {

			$title_part_id = $story["def_chapter"]["title_part_id"];

			$parent_part_id = $story["id"];

		}

		if (!$title_part_id) {

			$story["def_chapter"] = array( "title" => "", "body" => "", "title_part_id" => 0);

		}

		$meta = get_story_part_meta($story["toc"], $parent_part_id, $story["id"]);

		$display_order = $meta[1];

		$collabs = $story_dlgt->story_active_contributors($story["id"]);

		$stats = $story_dlgt->story_stats($story["id"]);

		if ($user["user_id"] == $story["owner_id"]) {

			$team_dlgt = $this->getDelegate("team");

			$teams = $team_dlgt->get_teams($user["user_id"]);

			$analysis = $story_dlgt->get_analysis($story["id"]);

			$this->tpl->assign("teams", $teams);

			$this->tpl->assign("analysis", $analysis);

		}
		
		$story["last_message_id"] = $story_dlgt->last_message_id($story["room_id"]);

		$this->tpl->assign("user", $user);

		$this->tpl->assign("story", $story);

		$this->tpl->assign("stats", $stats);

		$this->tpl->assign("collabs", $collabs);

		$this->tpl->assign("title_part_id", $title_part_id);

		$this->tpl->assign("parent_part_id", $parent_part_id);

		$this->tpl->assign("display_order", $display_order);

		$this->render('story_part_edit');

	}

	function upload_image($story_id)
	{

		$user = $this->getSessionUser();
	
		$story_dlgt = $this->getDelegate("story");

		$story = $story_dlgt->story_check($story_id);
	
		$user["role"] = $story_dlgt->get_user_role($story["story_id"], $user["user_id"]);

		if (empty($user) || !$story || $user["role"] < ROLE_CONTRIBUTOR) {
	
			$response["status"] = "Failure";
	
			echo json_encode($response);
	
			return;
	
		}

		if(isset($_FILES["image"], $_POST["width"], $_POST["height"]) && $_FILES["image"]["error"] == 0) {

			$image = $_FILES["image"];
	
			$width = $_POST["width"];
		
			$height = $_POST["height"];

			if ($story_dlgt->story_image_upload($story["story_id"], $user["user_id"], $image, $width, $height)) {
		
				$response["status"] = "Success";
		
				echo json_encode($response);
		
				return;
		
			}

			$response["status"] = "Failure";
			
			echo json_encode($response);
			
			return;

		}

		if(isset($_POST["width"], $_POST["height"])) {
			
			$width = $_POST["width"];
	
			$height = $_POST["height"];

			if($story_dlgt->story_image_resize($story["story_id"], $user["user_id"], $width, $height)) {

				$response["status"] = "Success";
		
				echo json_encode($response);
		
				return;

			}

		}

		$response["status"] = "Failure";
		
		echo json_encode($response);
		
		return;
	}

	function time_record($story_id)
	{

		$user = $this->getSessionUser();

		$story_dlgt = $this->getDelegate("story");

		$story = $story_dlgt->story_check($story_id);
		
		$user["role"] = $story_dlgt->get_user_role($story["story_id"], $user["user_id"]);

		if (isset($_POST["time"]) && $user["role"] >= ROLE_CONTRIBUTOR) {
		
			$time = $_POST["time"];

			$story_dlgt->time_record($story["story_id"], $user["user_id"], $time);
		
		}
	
	}

	function doedit($story_id)
	{
		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate("story");

		$story = $story_dlgt->story_check($story_id);

		if (empty($user) || !$story)
			$this->redirect("/");

		$user["role"] = $story_dlgt->get_user_role($story["story_id"], $user["user_id"]);
		
		if ($user["role"] < ROLE_CONTRIBUTOR)
			$this->redirect("/story/view/".$story_id);

		if (isset($_POST["title_part_id"], $_POST["parent_part_id"], $_POST["display_order"], $_POST["body"])) {
		
			$title = $_POST["title"];
		
			$body = $_POST["body"];
		
			$title_part_id = $_POST["title_part_id"];
		
			$parent_part_id = $_POST["parent_part_id"];
		
			$display_order = $_POST["display_order"];

			if(!$title_part_id) {
		
				$child_part = $story_dlgt->add_story_child_part($user['user_id'], $story["story_id"], $parent_part_id, $display_order, $title, $body);
		
				$title_part_id = $child_part["title_part_id"];
		
			} else {
		
				$story_dlgt->update_story_part($user['user_id'], $story["story_id"], $title_part_id, $title, $body);
		
			}

			$story_dlgt->add_edit($story["story_id"], $user["user_id"]);
		
			$this->redirect("/story/edit/$story_id/$title_part_id/$parent_part_id");
		
		}

		$this->redirect("/story/edit/$story_id");
	
	}

	function delete($story_id)
	{
		
		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate("story");

		$user["role"] = $story_dlgt->get_user_role($story_id, $user["user_id"]);

		if (empty($user) || !$story_dlgt->story_check($story_id) || $user["role"] != ROLE_OWNER) {
		
			$response["status"] = "Failure";
		
			echo json_encode($response);
		
			return;
		
		}

		$story_dlgt->delete_story($story_id);

		$response["status"] = "Success";
		
		echo json_encode($response);
		
		return;

	}

	function create()
	{

		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate("story");

		if (empty($user)) {
		
			$response["status"] = "Failure";
		
			echo json_encode($response);
		
			return;
		
		}

		if (isset($_POST["story_title"], $_POST["story_type"]) && !empty($_POST["story_title"]) && !empty($_POST["story_type"])) {
		
			$title = trim($_POST["story_title"]);
		
			$type = $this->get_story_type($_POST["story_type"]);

			if ($story = $story_dlgt->create_story($user["user_id"], $type, $title)) {
		
				$response["status"] = "Success";
		
				$response["data"] = "/story/edit/".$story["slug"]."?message=article_created";
		
				echo json_encode($response);
		
				return;
		
			}
		
		}

		$response["status"] = "Failure";
		
		echo json_encode($response);
		
		return;
	
	}

	function apply_collab($story_id)
	{

		$user = $this->getSessionUser();

		$user_dlgt = $this->getDelegate("user");
		
		$story_dlgt = $this->getDelegate("story");
		
		$user["role"] = $story_dlgt->get_user_role($story_id, $user["user_id"]);
		
		$story = $story_dlgt->story_check($story_id);

		if (empty($user) || !$story || $user["role"] > ROLE_CONTRIBUTOR_READER) {
		
			$response["status"] = "Failure";
		
			$response["data"] = 'Not Authorised';
		
			echo json_encode($response);
		
			return;
		
		}

		if($story_dlgt->apply_check($story["story_id"], $story["user_id"], $user["user_id"])) {

			$response["status"] = "Failure";
		
			$response["data"] = 'Apply request already exits';
		
			echo json_encode($response);
		
			return;

		}

		if($story_dlgt->invite_check($story["story_id"], $story["user_id"], $user["user_id"])) {

			$response["status"] = "Failure";
		
			$response["data"] = 'Invite request exits.indly review the request.';
		
			echo json_encode($response);
		
			return;

		}
		
		
		$apply_id = $story_dlgt->apply_collab($story["story_id"], $story["user_id"], $user["user_id"]);
	
		$owner = $user_dlgt->get_user($story['user_id']);
	
		$mail = $this->send_apply_mail($story['title'], $user['email'], $user['name'], $owner['email'], $owner['name']);
		
		$response["status"] = "Success";
		
		$response["data"] = 'Request Sent to the Owner of the Story.';
		
		echo json_encode($response);
		
		return;

	}
	
	function image_upload($story_id)
	{

		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate("story");

		$user["role"] = $story_dlgt->get_user_role($story_id, $user["user_id"]);

		if (empty($user) || !$story_dlgt->story_check($story_id) || $user["role"] < ROLE_CONTRIBUTOR)
			return;

		$story = $story_dlgt->get_story($story_id, $title_part_id);

		if (isset($_FILES["image"]) && $file_name = $story_dlgt->story_upload_image($story_id, $user["user_id"], $_FILES["image"])) {

			echo $path = BASE_URL . "/resource/img/story/" . $file_name;

			return;

		}

		return;

	}

	function draw()
	{
		$user = $this->getSessionUser();

		if (empty($user))
			return;

		$this->tpl->assign("user", $user);

		$this->render("story_drawing");

	}

	function drawing()
	{
		$user = $this->getSessionUser();

		if (empty($user))
			return;

		$this->tpl->assign("user", $user);

		$this->render("story_draw");

	}

	function invite()
	{

		if(isset($_POST["invite_email"]) && !empty($_POST["invite_email"])) {

			$email = trim($_POST["invite_email"]);

			$user_dlgt = $this->getDelegate('user');

			if(!$user_dlgt->get_user_by_email($email)) {

				$this->send_invite_unregistered_user("Hello", "Hello", $email);

				$response["status"] = "Success";

				$response["data"] = "Invite Sent Successfully.";

				echo json_encode($response);

				return;
				
			}

			$response["status"] = "Success";

			$response["data"] = "User already registered.";

			echo json_encode($response);

			return;

		}

		$response["status"] = "Failure";

		$response["data"] = "Something went wrong.";

		echo json_encode($response);

		return;
	
	}

	function best_story()
	{
		$user = $this->getSessionUser();

		if(empty($user["user_id"]) || $user["role"] != 'admin')
			$this->redirect("/");

		$story_dlgt = $this->getDelegate('story');

		$stories = $story_dlgt->get_all_stories();

		$best_stories = $story_dlgt->best_stories();

		$this->tpl->assign('stories', $stories);

		$this->tpl->assign('best_stories', $best_stories);

		$this->render("best_story");

	}

	function best_story_of_month()
	{
		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate('story');

		if(empty($user["user_id"]) || $user["role"] != 'admin' || !isset($_POST['story']) || empty($_POST['story']) || !$story_dlgt->story_check($_POST['story']))
			$this->redirect("/");

		$story_id = trim($_POST['story']);

		$now = Carbon::now();

		$best_stories = $story_dlgt->check_best_story_of_month($now->format('m'), $now->format('Y'));

		$rank = count($best_stories) + 1;

		if($rank <= BEST_STORIES) {

			$story_dlgt->best_story_of_month($story_id, $now->format('m'), $now->format('Y'), $now->toDateTimeString(), $user["user_id"], $rank);

		}

		$this->redirect('/story/best_story');

	}

	function rating($story_id)
	{
		$user = $this->getSessionUser();

		$story_dlgt = $this->getDelegate("story");

		$story = $story_dlgt->story_check($story_id);

		$user["role"] = $story_dlgt->get_user_role($story["story_id"], $user["user_id"]);

		if (empty($user) || !$story || $user["role"] > ROLE_CONTRIBUTOR_READER) {
		
			$response["status"] = "Failure";
		
			$response["data"] = 'Not Authorised';
		
			echo json_encode($response);
		
			return;
		
		}

		if(!isset($_POST['rating']) || empty($_POST['rating'])) {
			
			$response["status"] = "Failure";
		
			$response["data"] = 'Invalid Request';
		
			echo json_encode($response);
		
			return;

		}

		$user_rating = trim($_POST['rating']);

		if($rating = $story_dlgt->check_user_rating($story['story_id'], $user['user_id'])) {

			$story_dlgt->update_user_rating($rating['id'], $user_rating);

			$response["status"] = "Success";
		
			$response["data"] = 'Updated. Thanks for Rating!';

			echo json_encode($response);

			return;
		}

		if($rating = $story_dlgt->add_user_rating($story['story_id'], $user['user_id'], $user_rating)) {

			$response["status"] = "Success";
		
			$response["data"] = 'Thanks for Rating!';
		
			echo json_encode($response);
		
			return;

		}

		$response["status"] = "Failure";
		
		$response["data"] = 'Something went wrong.';
	
		echo json_encode($response);
	
		return;

	} 

	function unpublish_chapter($story_id, $title_part_id)
	{

		$user = $this->getSessionUser();

		$story_dlgt = $this->getDelegate("story");

		$story = $story_dlgt->story_check($story_id);

		if (empty($user) || !$story) {
		
			$response["status"] = "Failure";
		
			$response["data"] = 'Not Authorised';
		
			echo json_encode($response);
		
			return;
		
		}

		if($story_dlgt->unpublish_chapter($story["story_id"], $title_part_id)){

			$response["status"] = "Success";
		
			echo json_encode($response);
		
			return;

		}

		$response["status"] = "Failure";
		
		$response["data"] = 'Something Went Wrong';
	
		echo json_encode($response);
	
		return;

	}

	function publish_chapter($story_id, $title_part_id)
	{

		$user = $this->getSessionUser();

		$story_dlgt = $this->getDelegate("story");

		$story = $story_dlgt->story_check($story_id);

		if (empty($user) || !$story) {
		
			$response["status"] = "Failure";
		
			$response["data"] = 'Not Authorised';
		
			echo json_encode($response);
		
			return;
		
		}

		if($story_dlgt->publish_chapter($story["story_id"], $title_part_id)){

			$response["status"] = "Success";
		
			echo json_encode($response);
		
			return;

		}

		$response["status"] = "Failure";
		
		$response["data"] = 'Something Went Wrong';
	
		echo json_encode($response);
	
		return;

	}

	function invite_collab_read($story_id)
	{

		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate("story");
		
		$user_dlgt = $this->getDelegate("user");
		
		$story = $story_dlgt->story_check($story_id);

		$user["role"] = $story_dlgt->get_user_role($story["story_id"], $user["user_id"]);
		

		if (empty($user) || !$story || $user["role"] != ROLE_OWNER) {
		
			$response["status"] = "Failure";
		
			$response["data"] = 'Not Authorised';
		
			echo json_encode($response);
		
			return;
		
		}

		if (isset($_POST["collab_mail"]) && !empty($_POST["collab_mail"]) && $this->email_verification($_POST["collab_mail"])) {
			
			if ($collab = $user_dlgt->get_user_by_email(trim($_POST["collab_mail"]))) {

				$collab["role"] = $story_dlgt->get_user_role($story["story_id"], $collab["user_id"]);

				if ($collab["role"] == ROLE_CONTRIBUTOR_READER || $collab["role"] == ROLE_CONTRIBUTOR || $collab["role"] == ROLE_OWNER) {
				
					$response["status"] = "Failure";
				
					$response["data"] = 'Already a Contributor';
				
					echo json_encode($response);
				
					return;
				
				}

				if($story_dlgt->invite_read_check($story["story_id"], $user["user_id"], $collab["user_id"])) {

					$response["status"] = "Failure";
				
					$response["data"] = 'Invite request already exits';
				
					echo json_encode($response);
				
					return;

				}
				
				$invite_read_id = $story_dlgt->invite_read_collab($story["story_id"], $user["user_id"], $collab["user_id"]);
				
				$mail = $this->send_invite_read_mail($story['title'], $user['email'], $user['name'], $collab['email'], $collab['name']);
				
				$response["status"] = "Success";
				
				$response["data"] = 'Invite Sent';
				
				echo json_encode($response);
				
				return;
			
			}

			$response["status"] = "Success";
			
			$response["data"] = 'User is not registered with story. An email has been sent to user for registering with us';

			$story_dlgt->non_member_invite($story["story_id"], $_POST["collab_mail"], ROLE_CONTRIBUTOR_READER);
			
			$this->send_invite_unregistered_user($user['email'], $user['name'], trim($_POST["collab_mail"]));
			
			echo json_encode($response);
			
			return;
		}

		$response["status"] = "Failure";
		
		$response["data"] = 'Invalid email. Enter a valid email address';
	
		echo json_encode($response);
	
		return;
	
	}

	function invite_team_read($story_id)
	{

		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate("story");
		
		$user_dlgt = $this->getDelegate("user");
		
		$team_dlgt = $this->getDelegate("team");
		
		$story = $story_dlgt->story_check($story_id);

		$user["role"] = $story_dlgt->get_user_role($story["story_id"], $user["user_id"]);
		

		if (empty($user) || !$story || $user["role"] != ROLE_OWNER) {
		
			$response["status"] = "Failure";
		
			$response["data"] = 'Not Authorised';
		
			echo json_encode($response);
		
			return;
		
		}

		if (isset($_POST["team_id"]) && !empty($_POST["team_id"])) {

			if($team_members = $team_dlgt->get_team($user["user_id"], $_POST["team_id"])["members"]) {
			
				foreach ($team_members as $member) {
			
					if(!$story_dlgt->invite_read_check($story["story_id"], $user["user_id"], $member["id"])) {
						
						$invite_id = $story_dlgt->invite_read_collab($story["story_id"], $user["user_id"], $member["id"]);
						
					}
				
					$mail = $this->send_invite_read_mail($story['title'], $user['email'], $user['name'], $member['email'], $member['name']);
					
				}

				$response["status"] = "Success";
				
				$response["data"] = 'Successfully invited all team Members';
				
				echo json_encode($response);
				
				return;
			
			}

			$response["status"] = "Failure";
			
			$response["data"] = 'No Members in team';
			
			echo json_encode($response);
			
			return;

		}

		$response["status"] = "Failure";
			
		$response["data"] = 'Something went wrong';
		
		echo json_encode($response);
		
		return;

	}

	function auto_save($story_id)
	{
		$user = $this->getSessionUser();
		
		$story_dlgt = $this->getDelegate("story");

		$story = $story_dlgt->story_check($story_id);

		if (empty($user) || !$story) {
			
			$response["status"] = "Failure";
			
			$response["data"] = 'Not Authorised';
			
			echo json_encode($response);
			
			return;

		}

		$user["role"] = $story_dlgt->get_user_role($story["story_id"], $user["user_id"]);
		
		if ($user["role"] < ROLE_CONTRIBUTOR) {

			$response["status"] = "Failure";
			
			$response["data"] = 'Not Authorised';
			
			echo json_encode($response);
			
			return;
		}

		if (isset($_POST["title_part_id"], $_POST["parent_part_id"], $_POST["display_order"], $_POST["body"])) {
		
			$title = $_POST["title"];
		
			$body = $_POST["body"];
		
			$title_part_id = $_POST["title_part_id"];
		
			$parent_part_id = $_POST["parent_part_id"];
		
			$display_order = $_POST["display_order"];

			$story_dlgt->update_story_part($user['user_id'], $story["story_id"], $title_part_id, $title, $body);

			$response["status"] = "Success";
			
			$response["data"] = 'Story Saved.';
			
			echo json_encode($response);
			
			return;
		
		}

		$response["status"] = "Failure";
			
		$response["data"] = 'Something Happened Wrong.';
		
		echo json_encode($response);
		
		return;
	
	}

	function clear_editor()
	{

		$user = $this->getSessionUser();

		$story_dlgt = $this->getDelegate("story");

		if(!isset($_POST["story_id"]) || empty($_POST["story_id"]))
			return;

		$story = $story_dlgt->story_check($_POST["story_id"]);

		if (empty($user) || !$story) {
			
			$response["status"] = "Failure";
			
			$response["data"] = 'Not Authorised';
			
			echo json_encode($response);
			
			return;

		}

		if($story["editor"] != null) {

			$story_dlgt->clear_story_editor($story["story_id"]);

		} else {

			$response["status"] = "Failure";
			
			$response["data"] = 'Something went wrong.';
			
			echo json_encode($response);
			
			return;

		}

	}

	function check_editor()
	{

		$user = $this->getSessionUser();

		$story_dlgt = $this->getDelegate("story");

		if(!isset($_POST["story_id"]) || empty($_POST["story_id"])) {

			$response["status"] = "Failure";
			
			$response["data"] = 'Not Authorised';
			
			echo json_encode($response);

			return;
		}

		$story = $story_dlgt->story_check($_POST["story_id"]);

		if (empty($user) || !$story) {
			
			$response["status"] = "Failure";
			
			$response["data"] = 'Not Authorised';
			
			echo json_encode($response);
			
			return;

		}

		if($story["editor"] != null && $story["editor"] != $user["user_id"]) {

			$response["status"] = "Failure";
			
			$response["data"] = 'Story is being edited by other contributor. Please try after some time.';
			
			echo json_encode($response);
			
			return;

		} else {

			$response["status"] = "Success";
			
			echo json_encode($response);
			
			return;

		}

	}

}
