<nav class="navbar navbar-fixed-top">
	<div class="container">
		<a class="navbar-brand" href="/">
			<img src="/resource/img/elements/book-icons.png" alt="">
		</a>
		<a href="/" class="btn-mobile-trigger visible-xs">
			<i class="fa fa-bars"></i>
		</a>
		<div class="menu-holder">
			<div class="menu-header visible-xs">
				<a href="#" class="btn-close-menu pull-right">
					<i class="fa fa-times"></i>
				</a>
				<h3 class="pull-left">Navigate</h3>
			</div>
			<div class="navbar-holder">
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown profile-pic-holder" id="profile-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<img src="/resource/img/user/{$smarty.session.user.image}" class="profile-pic" alt="user-image">
							{$smarty.session.user.name} <i class="fa fa-chevron-down"></i>
						</a>
						<ul class="dropdown-menu">
							{if $user.role == 'admin'}
							<li>
								<a href="/story/best_story">
									Best Story
								</a>
							</li>
							{/if}
							<li>
								<a href="/user/profile">
									Profile
								</a>
							</li>
							<li>
								<a href="/auth/logout">
									Log out
								</a>
							</li>
						</ul>
					</li>
				</ul>    	
				<form class="navbar-form navbar-right" action="/auth/search" method="POST">
					<div class="form-group form-group-search">
						<input name="search" type="text" id="fableus-search" class="form-control" placeholder="Look for stories" required>
						<button type="submit" class="btn-submit"><i class="fa fa-search"></i></button>
					</div>
				</form>
				<ul class="nav navbar-nav navbar-right">
					<li class="navbar-nav-inline">
						<a href="#" data-toggle="modal" data-target="#invite-friend" id="tell-a-friend" data-backdrop="static">tell a friend</a>
					</li>
					<li class="navbar-nav-inline">
						<a href="#" data-toggle="modal" data-target="#story-title" id="write-story" data-backdrop="static">
							write a story
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</nav>
<section class="nav-page-holder">
	<div class="container">
		<ul class="nav-page-list">
			<li>
				<a href="/stories" {if isset($tab.stories)}class="active"{/if} id="stories">
					My Stories
				</a>
			</li>
			<li>
				<a href="/stories/collabs" {if isset($tab.collabs)}class="active"{/if} id="collabs">
					My Collaborations
				</a>
			</li>
			<li>
				<a href="/team" {if isset($tab.teams)}class="active"{/if} id="team">
					My Teams
				</a>
			</li>
			<!-- <li>
				<a href="/messages" {if isset($tab.messages)}class="active"{/if}>
					Messages
				</a>
			</li> -->
			{if isset($tab.stories) || isset($tab.collabs) || isset($tab.teams)}
			<li class="pull-right">
				<a href="#" class="learn-more text-muted">
					Learn More
				</a>
			</li>
			{/if}
		</ul>
	</div>
</section>