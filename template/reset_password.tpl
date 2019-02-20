<div class="modal-dialog modal-lg" style="height: 470px;">
	<div class="modal-content">
		<div class="modal-header" align="center">
			<h2>Reset Password</h2>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					<form action="/auth/new_password/" id="new-pass-form" method="post">
						<p class="line-behind">Enter your new password</p>
						<div class="row">
							<div class="col-sm-6 push-down">
								<input type="password" name="password" class="form-control input-lg input-mutted input-italic" placeholder="Password" required style="margin-bottom: 5px;">
							</div>
							<div class="col-sm-6">
								<input type="password" name="confirm_password" class="form-control input-lg input-mutted input-italic" placeholder="Confirm Password" required>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-sm-5 pull-right">
								<button type="submit" class="btn btn-info btn-block btn-lg text-uppercase">Reset</button>
							</div><br>
						</div>
					</form>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2" id="reset-error"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('input[name="password"]').password();
	});
</script>