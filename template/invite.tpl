<div class="modal fade" id="invite-friend" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal">
				<i class="fa fa-times"></i>
			</button>
			<div class="modal-header">
				<div class="modal-logo" style="text-align: center">
					<img src="/resource/img/elements/book-icons.png" alt="" style="width: 50%">
				</div>
				<h2 align="center">Tell a Friend</h2>
			</div>
			<div class="modal-body">
				<div class="form-group form-group-lg">
					<div class="row">
						<form action="/story/invite/" id="invite" method="POST">
							<div class="col-xs-5 col-xs-offset-2" style="margin-top: 10px;">
								<input type="email" name="invite_email" class="form-control input-lg input-mutted input-italic" placeholder="Email..." required>
							</div>
							<div class="col-xs-3" style="margin-top: 10px;">
								<button type="submit" class="btn btn-info btn-block btn-lg text-uppercase">Send Invite</button>
							</div>
						</form>
					</div>
					<div class="row" style="margin-top: 10px;">
						<div class="col-xs-8 col-xs-offset-2">
							<div id="invite-email-message"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>