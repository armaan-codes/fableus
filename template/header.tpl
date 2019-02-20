<nav class="navbar navbar-fixed-top">
	<div class="container">
		<a class="navbar-brand" href="/">
			<img src="/resource/img/elements/book-icons.png" alt="">
		</a>
		<a href="#" class="btn-mobile-trigger visible-xs">
			<i class="fa fa-bars"></i>
		</a>
		<div class="menu-holder">
			<div class="menu-header visible-xs">
				<a href="#" class="btn-close-menu pull-right">
					<i class="fa fa-times"></i>
				</a>
				<a class="navbar-brand" href="#">
					<img src="/resource/img/elements/book-icons.png" alt="">
				</a>
			</div>
			<div class="navbar-holder">
				<form class="navbar-form navbar-right" action="/auth/search" method="POST">
					<div class="form-group form-group-search">
						<input name="search" type="text" id="fableus-search" class="form-control" placeholder="Look for stories" required>
						<button type="submit" class="btn-submit"><i class="fa fa-search"></i></button>
					</div>
				</form>
				<ul class="nav navbar-nav navbar-right navbar-unlogin">
					<li class="visible-xs navbar-nav-inline">
						<a href="/user/index">home</a>
					</li>
					<li class="navbar-nav-inline">
						<a href="#" id="tell-a-friend" data-toggle="modal" data-target="#invite-friend" data-backdrop="static">tell a friend</a>
					</li>
					<li class="navbar-nav-inline">
						<a href="#" data-toggle="modal" data-target="#sign-modal" data-backdrop="static">write a story</a>
					</li>
					<li class="push-down">
						<a href="#" class="btn btn-outline btn-outline-info btn-mobile-block" data-toggle="modal" data-target="#register-modal" id="register-btn" data-backdrop="static">sign up</a>
					</li>
					<li class="push-down">
						<a href="#" class="btn btn-outline btn-outline-info btn-mobile-block" data-toggle="modal" data-target="#sign-modal" id="login-btn" data-backdrop="static">sign in</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</nav>