<style type="text/css">
.btn-group, .btn-group-vertical {
	position: relative;
	display: inline-block;
	vertical-align: middle;
}
</style>
<main>
	<section class="team-view">
		<div class="container">
			<div class="row">
				<div class="col-md-7 team-detail">
					<p class="text-muted line-behind" align="center">Team Description</p>
					<div class="row">
						<p align="center">
							<img src="/resource/img/team/{$team.image}" class="team-image image-preview" style="width: 100px;">
						</p>
					</div>
					<form action="/team/update_team/{$team.team_id}" id="update-team-form" method="post" enctype="multipart/form-data">
						<input type="file" name="team_img" accept="image/*">
						<div class="row">
							<div class="form-group">
								<div class="col-xs-2" style="padding-top: 5px;">
									<h4 align="right" class="text-muted">Name</h4>
								</div>
								<div class="col-xs-10">
									<input type="text" name="team_name" class="form-control" value="{$team.name}">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<div class="col-xs-2" style="padding-top: 5px;">
									<h4 align="right" class="text-muted">Description</h4>
								</div>
								<div class="col-xs-10">
									<textarea name="team_desc" id="update-team-description" class="form-control" style="min-height: 100px;">{$team.description}</textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<div class="col-sm-3 col-sm-offset-6" style="margin-top: 5px">
									<button class="btn btn-info btn-block text-uppercase" type="submit">Update</button>
								</div>
								<div class="col-sm-3" style="margin-top: 5px">
									<a href="/team/delete_team/{$team.team_id}" class="team-delete btn btn-info btn-block text-uppercase">Delete</a>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="col-sm-5 team-members">
					<p class="text-muted line-behind" align="center">Members</p>
					<div class="members-container row">
						{foreach from=$team["members"] item=member}
						<div class="col-sm-4 members-icon">
							<div class="image-holder">
								<p align="center">
									<img class="member-image" src="/resource/img/user/{$member.image}">
								</p>
							</div>
							<div class="member-info">
								<p class="text-muted" align="center"><a href="/user/view/{$member.id}">{$member.name}</a></p>
							</div>
							<div class="member-action">
								<div class="row">
									<p align="center">
										<a href="/team/delete_member/{$team.team_id}/{$member.id}" class="member-delete" style="color: red !important">
											Delete
										</a>
									</p>
								</div>
							</div>
						</div>
						{/foreach}
					</div>
					<form action="/team/add_member/{$team.team_id}" id="add-member-form" method="POST">
						<div class="row">
							<div class="col-sm-8" style="margin-top: 5px">
								<input type="email" class="form-control" name="member_email" placeholder="Enter email..." required>
							</div>
							<div class="col-sm-4" style="margin-top: 5px">
								<button class="btn btn-info btn-block text-uppercase" type="submit">Add</button>
							</div>
						</div>
					</form>
					<div class="team-view-message"></div>
				</div>
			</div>
		</div>
	</section>
</main>

<script type="text/javascript">
	$(document).ready(function() {

		$(".image-preview").on("click", function(){
		
			$("input[name='team_img']").click();
		
		});
	
	});
</script>