<div class="modal fade" id="register-modal" tabindex="-1">
	<button type="button" class="close" data-dismiss="modal">
		<i class="fa fa-times"></i>
	</button>
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class="row">
					<div class="col-sm-3">
						<img src="/resource/img/elements/book-icons.png" style="width: 50%">
					</div>
					<div class="col-sm-6">
						<h2>Sign Up</h2>
					</div>
				</div>
			</div>
			<div class="modal-body" id="signup-body">
				<form id="signup-form" method="POST" action="/auth/register/">
					<p class="line-behind">Enter your Basic info</p>
					<div class="signup-group">
						<div class="row">
							<div class="col-sm-6">
								<input type="text" name="rg_name" class="form-control input-mutted input-lg input-italic" placeholder="Name" required>
							</div>
							<div class="col-sm-6">
								<input type="email" name="rg_email" class="form-control input-mutted input-lg input-italic" placeholder="Email" required>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<input type="password" name="rg_pass" class="form-control input-lg input-mutted input-italic" placeholder="Password" required>
							</div>
							<div class="col-sm-6">
								<input type="password" name="rg_pass_confirm" class="form-control input-lg input-mutted input-italic" placeholder="Confirm Password" required>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-5 pull-right">
								<button type="submit" class="btn btn-info btn-block btn-lg text-uppercase">Sign Up</button>
							</div>
						</div>
					</div>
				</form>
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2" id="rg-error"></div>
				</div>
				<p class="line-behind">OR</p>
				<div class="row">
					<div class="col-sm-5 col-sm-offset-1" style="margin-top: 5px">
						<a href="/auth/facebook/" class="btn btn-info btn-block btn-lg text-uppercase">
							<i class="fa fa-facebook-square"></i> Sign up with Facebook
						</a>
					</div>
					<div class="col-sm-5" style="margin-top: 5px">
						<a href="/auth/twitter/" class="btn btn-info btn-block btn-lg text-uppercase">
							<i class="fa fa-twitter-square"></i> Sign up with Twitter
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('input[name="rg_pass"]').password();
	});
</script>