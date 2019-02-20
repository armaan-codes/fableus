<div class="modal fade" id="sign-modal" tabindex="-1">
	<button type="button" class="close" data-dismiss="modal">
		<i class="fa fa-times"></i>
	</button>
	<div class="modal-dialog modal-lg">
		<div id="signin">
			<div class="modal-content">
				<div class="modal-header">
					<div class="row">
						<div class="col-sm-3">
							<img src="/resource/img/elements/book-icons.png" style="width: 50%">
						</div>
						<div class="col-sm-6">
							<h2>Sign In</h2>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<form action="/auth/signin" id="signin-form" method="post">
						<p class="line-behind">Enter your credentials.</p>
						<div class="signup-group">
							<div class="row">
								<div class="col-sm-6 push-down">
									<input type="email" name="login_email" class="form-control input-lg input-mutted input-italic" placeholder="Email" required>
								</div>
								<div class="col-sm-6">
									<input type="password" name="login_pass" class="form-control input-lg input-mutted input-italic" placeholder="Password" required>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-5 pull-left">
									<a href="#" id="forgot_button">Forgot Password ?</a>
								</div>
								<div class="col-sm-5 pull-right">
									<button type="submit" class="btn btn-info btn-block btn-lg text-uppercase">sign in</button>
								</div>
							</div>
						</div>
					</form>
					<div class="row">
						<div class="col-sm-8 col-sm-offset-2" id="login-error"></div>
					</div>
					<p class="line-behind">OR</p>
					<div class="row">
						<div class="col-sm-5 col-sm-offset-1" style="margin-top: 5px">
							<a href="/auth/facebook/" class="btn btn-info btn-block btn-lg text-uppercase">
								<i class="fa fa-facebook-square"></i> Sign in with Facebook
							</a>
						</div>
						<div class="col-sm-5" style="margin-top: 5px">
							<a href="/auth/twitter/" class="btn btn-info btn-block btn-lg text-uppercase">
								<i class="fa fa-twitter-square"></i> Sign in with Twitter
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="forgot_password" style="display: none;">
			<button type="button" id="back_to_signin" style="font-size: 24px;">
				<i class="fa fa-chevron-left"></i>
			</button>
			<div class="modal-content">
				<div class="modal-header">
					<div class="row">
						<div class="col-sm-3">
							<img src="/resource/img/elements/site-logo.svg">
						</div>
						<div class="col-sm-6">
							<h2>Reset Password</h2>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<form action="/auth/forgot_password" id="forgot-form" method="post">
						<p class="line-behind">Enter your Email</p>
						<div class="signup-group">
							<div class="row">
								<div class="col-sm-8 push-down">
									<input type="email" name="forgot_email" class="form-control input-lg input-mutted input-italic" placeholder="Email" required>
								</div>
								<div class="col-sm-4 pull-right">
									<button type="submit" class="btn btn-info btn-block btn-lg text-uppercase">Submit</button>
								</div>
							</div>
						</div>
					</form>
					<div class="row">
						<div class="col-sm-8 col-sm-offset-2" id="forgot-error"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>