<?php
class ProjectsDelegate extends BaseDelegate
{
	function my_projects($user_id){
		$open_projects = $this->projects->my_open_projects($user_id);
		$awarded_projects = $this->projects->my_award_projects($user_id);


		foreach ($open_projects as &$project) {
			$project["50_words"] = $this->market->project_50_words($project["id"]);
			$project["stats"]["bids"] = $this->market->project_bids_count($project["id"]);
			$open_last_id = $project["id"];
		}

		foreach ($awarded_projects as &$project) {
			$project["50_words"] = $this->market->project_50_words($project["id"]);
			$project["stats"]["bids"] = $this->market->project_bids_count($project["id"]);
			$awarded_last_id = $project["id"];
		}

		return array( "open" => array("projects" => $open_projects, "last_project_id" => $open_last_id), "award" => array("projects" => $awarded_projects, "last_project_id" => $awarded_last_id));
	}

	function search_project($user_id, $string) {
		$projects = $this->projects->search_project($user_id, $string);
		foreach ($projects as &$project) {
			$project["50_words"] = $this->market->project_50_words($project["id"]);
			$project["stats"]["bids"] = $this->market->project_bids_count($project["id"]);
		}
		return $projects;
	}

	function load_open_projects($user_id, $last_id) {
		$projects = $this->projects->load_open_projects($user_id, $last_id);
		foreach ($projects as &$project) {
			$project["50_words"] = $this->market->project_50_words($project["id"]);
			$project["stats"]["bids"] = $this->market->project_bids_count($project["id"]);
		}
		return $projects;
	}

	function load_award_projects($user_id, $last_id) {
		$projects = $this->projects->load_award_projects($user_id, $last_id);
		foreach ($projects as &$project) {
			$project["50_words"] = $this->market->project_50_words($project["id"]);
			$project["stats"]["bids"] = $this->market->project_bids_count($project["id"]);
		}
		return $projects;
	}

	function edit($user_id, $project_id, $category, $range_from, $range_to, $description) {
		return $this->projects->edit($user_id, $project_id, $category, $range_from, $range_to, $description);
	}

	function award_project($user_id, $project_id, $data) {
		$award = $this->projects->award_project($user_id, $project_id, $data);
		
		$project = $this->market->get_project($project_id);
		$project["collab"] = $this->projects->project_collab($project_id);
		$story_id = $this->story->create_story($project["owner_id"], $project["category"], $project["title"]);
		$story = $this->story->get_story($story_id);

		foreach ($project["collab"] as $collab) {
			$this->projects->add_project_collab($story_id, $collab["id"], $story["room_id"]);
		}

		$this->projects->award_confirm($story_id, $project_id, $user_id);
		return $story_id;
	}
}