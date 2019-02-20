<style type="text/css">
.btn-group, .btn-group-vertical {
	position: relative;
	display: inline-block;
	vertical-align: middle;
}
</style>
<main>
	<section class="my-teams">
		<div class="container">
			<div class="row">
				<div class="col-sm-5 teams-container">
					<div class="row">
						{foreach from=$teams item=team}
						<div class="col-sm-4 teams-icon">
							<a href="/team/view/{$team.team_id}">
							<div class="image-holder">
								<p align="center">
									<img class="team-image" src="/resource/img/team/{$team.image}">
								</p>
							</div>
							<div class="team-info">
								<p align="center">{$team.name}</p>
							</div>
							</a>
						</div>
						{/foreach}
					</div>
				</div>
				<div class="col-sm-7">
					<div id="create-team-container">
						<p class="text-muted line-behind" align="center">Add New team</p>
						<form action="/team/create_team" method="post" enctype="multipart/form-data">
							<div class="form-group form-group-lg">
								<div class="row">
									<div class="col-xs-2" style="padding-top: 35px;">
										<h4 class="text-muted" align="right">Icon:</h4>
									</div>
									<div class="col-xs-10">
										<div class="team-icon-upload">
											<input type="file" name="team_img" accept="image/*">
											<img src="/resource/img/image-here.png" width="100" class="image-preview">
										</div>
									</div>
								</div>
							</div>
							<div class="form-group form-group-lg">
								<div class="row">
									<div class="col-xs-2" style="padding: 10px;">
										<h4 class="text-muted" align="right">Name:</h4>
									</div>
									<div class="col-xs-10">
										<input type="text" name="team_name" class="form-control input-mutted input-italic" placeholder="Name" required>
									</div>
								</div>
							</div>
							<div class="form-group form-group-lg">
								<div class="row">
									<div class="col-xs-2" style="padding: 10px;">
										<h4 class="text-muted" align="right">Description:</h4>
									</div>
									<div class="col-xs-10">
										<textarea name="team_desc" id="team-description" class="form-control input-mutted" placeholder="Description" style="min-height: 100px;" required></textarea>
									</div>
								</div>
							</div>
							<div class="form-group form-group-lg">
								<div class="row">
									<div class="col-sm-4 col-sm-offset-8">
										<button type="submit" class="btn btn-info btn-block btn-lg text-uppercase">Add Team</button>
									</div>
								</div>
							</div>
						</form>
					</div>
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