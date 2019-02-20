<div class="modal fade" id="invite-modal" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal">
				<i class="fa fa-times"></i>
			</button>
			<div class="modal-header">
				<div class="modal-logo" style="text-align: center">
					<img src="/resource/img/elements/book-icons.png" alt="" style="width: 50%">
				</div>
				<h2 align="center">Invite Collaborator</h2>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs">
					<li class="col-sm-4 col-sm-offset-2 active" style="text-align: center;">
						<a data-toggle="tab" href="#viamail" style="font-size: 17px;">By Email</a>
					</li>
					<li class="col-sm-4" style="text-align: center;">
						<a data-toggle="tab" href="#viateam" style="font-size: 17px;">By Team</a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="viamail" class="tab-pane fade in active">
						<div class="form-group form-group-lg">
							<div class="row">
								<form action="/story/invite_collab/{$story.id}" id="invite-collab-email" method="POST">
									<div class="col-xs-3" style="margin-top: 10px;">
										<h3 class="text-muted" align="right" style="padding: 10px;">Search: </h3>
									</div>
									<div class="col-xs-6" style="margin-top: 10px;">
										<input type="email" name="collab_mail" class="form-control input-lg input-mutted input-italic" placeholder="Enter e-mail of contributor..." required>
									</div>
									<div class="col-xs-3" style="margin-top: 10px;">
										<button type="submit" class="btn btn-info btn-block btn-lg text-uppercase">Send Invite</button>
									</div>
								</form>
							</div>
							<div class="row" style="margin-top: 10px;">
								<div class="col-xs-8 col-xs-offset-2">
									<div id="invite-collab-email-message"></div>
								</div>
							</div>
						</div>
					</div>
					<div id="viateam" class="tab-pane fade">
						<div class="form-group form-group-lg">
							<div class="row">
								<form action="/story/invite_team/{$story.id}" method="POST" id="invite-collab-team">
									<div class="col-xs-6 col-xs-offset-2" style="margin-top: 10px;">
										<select class="form-control" style="line-height: 25px;" name="team_id" required>
											<option value="">Select team</option>
											{foreach from=$teams item=team}
												<option value="{$team.team_id}">{$team.name}</option>
											{/foreach}
										</select>
									</div>
									<div class="col-xs-3" style="margin-top: 10px;">
										<button type="submit" class="btn btn-info btn-block btn-lg text-uppercase">Send Invite</button>
									</div>
								</form>
							</div>
							<div class="row" style="margin-top: 10px;">
								<div class="col-xs-8 col-xs-offset-2">
									<div id="invite-collab-team-message"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>