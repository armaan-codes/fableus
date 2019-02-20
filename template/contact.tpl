<div class="modal fade" id="contact-modal" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal">
				<i class="fa fa-times"></i>
			</button>
			<div class="modal-header">
				<div class="modal-logo">
					<img src="/resource/img/elements/site-logo.svg" alt="logo">
				</div>
				<h2 align="center">
					<i><strong>Contact Us</strong></i>
				</h2>
			</div>
			<div class="modal-body">
				<div class="form-group form-group-lg">
					<div class="row">
						<div class="col-sm-8 col-sm-offset-2" id="contact-error"></div>
					</div>
				</div>
				<form action="/auth/contact/" method="POST" id="contact-form">
					<div class="form-group form-group-lg">
						<div class="row">
							<div class="col-sm-10 col-sm-offset-1">
								<input type="text" class="form-control" name="contact_name" placeholder="Name" required>
							</div>
						</div>
					</div>
					<div class="form-group form-group-lg">
						<div class="row">
							<div class="col-sm-10 col-sm-offset-1">
								<input type="text" class="form-control" name="contact_subject" placeholder="Subject" required>
							</div>
						</div>
					</div>
					<div class="form-group form-group-lg">
						<div class="row">
							<div class="col-sm-10 col-sm-offset-1">
								<input type="email" class="form-control" name="contact_email" placeholder="Email" required>
							</div>
						</div>
					</div>
					<div class="form-group form-group-lg">
						<div class="row">
							<div class="col-sm-10 col-sm-offset-1">
								<textarea class="form-control" placeholder="Message" required name="contact_message"></textarea>
							</div>
						</div>
					</div>
					<div class="form-group form-group-lg">
						<div class="row">
							<div class="col-sm-3 col-sm-offset-8">
								<button type="submit" class="btn btn-info btn-block btn-lg text-uppercase">
									Submit
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>