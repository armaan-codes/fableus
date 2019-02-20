<?php
class MarketDelegate extends BaseDelegate
{
	function get_projects($user_id) {
		
		$open_projects = $this->market->get_open_projects($user_id);
		$awarded_projects = $this->market->get_award_projects($user_id);


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

	function get_top_authors($user_id) {
		$authors =  $this->market->get_top_authors($user_id);
		foreach ($authors as &$author) {
			$author["stats"] = $this->market->get_author_stats($author["id"]);
		}
		return $authors;
	}

	function search_project($user_id, $string) {
		$projects = $this->market->search_project($user_id, $string);
		foreach ($projects as &$project) {
			$project["50_words"] = $this->market->project_50_words($project["id"]);
			$project["stats"]["bids"] = $this->market->project_bids_count($project["id"]);
		}
		return $projects;
	}

	function search_authors($user_id, $author) {
		$authors = $this->market->search_authors($user_id, $author);
		foreach ($authors as &$author) {
			$author["stats"] = $this->market->get_author_stats($author["id"]);
		}
		return $authors;
	}

	function create_project($user_id, $title, $category, $description, $range_from, $range_to) {
		return $this->market->create_project($user_id, $title, $category, $description, $range_from, $range_to);
	}

	function load_open_projects($user_id, $last_id) {
		$projects = $this->market->load_open_projects($user_id, $last_id);
		foreach ($projects as &$project) {
			$project["50_words"] = $this->market->project_50_words($project["id"]);
			$project["stats"]["bids"] = $this->market->project_bids_count($project["id"]);
		}
		return $projects;
	}

	function load_award_projects($user_id, $last_id) {
		$projects = $this->market->load_award_projects($user_id, $last_id);
		foreach ($projects as &$project) {
			$project["50_words"] = $this->market->project_50_words($project["id"]);
			$project["stats"]["bids"] = $this->market->project_bids_count($project["id"]);
		}
		return $projects;
	}

	function get_project($project_id) {
		$project = $this->market->get_project($project_id);
		$project["stats"]["bids"] = $this->market->project_bids_count($project["id"]);
		return $project;
	}

	function get_bids($project_id) {
		return $this->market->get_bids($project_id);
	}

	function user_bid($user_id, $project_id) {
		return $this->market->user_bid($user_id, $project_id);
	}

	function bid_project($project_id, $user_id, $amount, $days, $proposal) {
		return $this->market->bid_project($project_id, $user_id, $amount, $days, $proposal);
	}

	function update_proposal($project_id, $user_id, $proposal) {
		return $this->market->update_proposal($project_id, $user_id, $proposal);
	}

	function get_awards($project_id) {
		return $this->market->get_awards($project_id);
	}
}
?>