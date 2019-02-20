<main>
	<section class="hero-section relative">
		<div class="hero-carousel">
			<div class="item">
				<img src="/resource/img/temp/hero_image1.jpg" class="full-height" alt="">
				<div class="carousel-content-holder">
					<div class="container">
						<div class="row row-flex">
							<div class="col-sm-5 col-sm-push-7 push-down">
								<img src="/resource/img/elements/book-icons.svg" class="img-responsive book-icons" alt="">
							</div>
							<div class="col-sm-7 col-sm-pull-5">
								<h2 class="h1 text-uppercase text-white carousel-title">We all have a story to tell.</h2>
								<h2 class="text-white carousel-subtitle"><i>Nobody can tell your story, the way you can tell your story.</i></h2>
								<div class="row">
									<div class="col-md-4 col-sm-6 push-down">
										<a href="#" class="learn-more btn btn-info btn-block btn-lg text-uppercase">learn more</a>
									</div>
									{if empty($smarty.session.user)}
									<div class="col-md-4 col-sm-6">
										<a href="#" class="btn btn-outline btn-outline-info btn-block btn-lg text-uppercase" data-toggle="modal" data-target="#register-modal" data-backdrop="static">sign up</a>
									</div>
									{/if}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		    <div class="item">
				<img src="/resource/img/temp/hero_image2.jpg" class="full-height" alt="">
				<div class="carousel-content-holder">
					<div class="container">
						<div class="row row-flex">
							<div class="col-sm-5 col-sm-push-7 push-down">
								<img src="/resource/img/elements/book-icons.svg" class="img-responsive book-icons" alt="">
							</div>
							<div class="col-sm-7 col-sm-pull-5">
								<h2 class="h1 text-uppercase text-white carousel-title">You have a story to tell.</h2>
								<h2 class="text-white carousel-subtitle"><i>There comes a point in your life, when you need to stop reading other people's story and write your own.</i></h2>
								<div class="row">
									<div class="col-md-4 col-sm-6 push-down">
										<a href="#" class="learn-more btn btn-info btn-block btn-lg text-uppercase">learn more</a>
									</div>
									{if empty($smarty.session.user)}
									<div class="col-md-4 col-sm-6">
										<a href="#" class="btn btn-outline btn-outline-info btn-block btn-lg text-uppercase" data-toggle="modal" data-target="#register-modal" data-backdrop="static">sign up</a>
									</div>
									{/if}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="item">
				<img src="/resource/img/temp/hero_image3.jpg" class="full-height" alt="">
				<div class="carousel-content-holder">
					<div class="container">
						<div class="row row-flex">
							<div class="col-sm-5 col-sm-push-7 push-down">
								<img src="/resource/img/elements/book-icons.svg" class="img-responsive book-icons" alt="">
							</div>
							<div class="col-sm-7 col-sm-pull-5">
								<h2 class="h1 text-uppercase text-white carousel-title">You have a story to tell.</h2>
								<h2 class="text-white carousel-subtitle"><i>...but sometime you don't have enough time to focus and write...</i></h2>
								<div class="row">
									<div class="col-md-4 col-sm-6 push-down">
										<a href="#" class="learn-more btn btn-info btn-block btn-lg text-uppercase">learn more</a>
									</div>
									{if empty($smarty.session.user)}
									<div class="col-md-4 col-sm-6">
										<a href="#" class="btn btn-outline btn-outline-info btn-block btn-lg text-uppercase" data-toggle="modal" data-target="#register-modal" data-backdrop="static">sign up</a>
									</div>
									{/if}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		    <div class="item">
				<img src="/resource/img/temp/hero_image4.jpg" class="full-height" alt="">
				<div class="carousel-content-holder">
					<div class="container">
						<div class="row row-flex">
							<div class="col-sm-5 col-sm-push-7 push-down">
								<img src="/resource/img/elements/book-icons.svg" class="img-responsive book-icons" alt="">
							</div>
							<div class="col-sm-7 col-sm-pull-5">
								<h2 class="h1 text-uppercase text-white carousel-title">You have a story to tell.</h2>
								<h2 class="text-white carousel-subtitle"><i>...but with story you can invite, collaborate, share and publish your story quickly and easily</i></h2>
								<div class="row">
									<div class="col-md-4 col-sm-6 push-down">
										<a href="#" class="learn-more btn btn-info btn-block btn-lg text-uppercase">learn more</a>
									</div>
									{if empty($smarty.session.user)}
									<div class="col-md-4 col-sm-6">
										<a href="#" class="btn btn-outline btn-outline-info btn-block btn-lg text-uppercase" data-toggle="modal" data-target="#register-modal" data-backdrop="static">sign up</a>
									</div>
									{/if}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<a href="#section-anchor" class="btn-scroll-down" id="btn-chevron" data-click="scroll-to-target">
			<i class="fa fa-chevron-down"></i>
		</a>
	</section>

	<section class="section" id="section-anchor">
		<div class="container">
			<div class="row">
				<div class="col-md-7 push-down">
					<div class="title-with-border">
						<div class="dropdown">
						  	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						  		Sort By
						  		<i class="fa fa-chevron-down icon"></i>
						  	</a>
						  	<ul class="sorting-list dropdown-menu">
							    <li>
							    	<a href="?filter=recent-stories">Recent Stories</a>
							    </li>
							    <li>
							    	<a href="?filter=top-stories">Top Stories</a>
							    </li>
						  	</ul>
						</div>
						<div class="dropdown">
						  	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						  		Story Type
						  		<i class="fa fa-chevron-down icon"></i>
						  	</a>
						  	<ul class="dropdown-menu">
							    <li>
							    	<a href="/">All</a>
							    </li>
							    {foreach from=$types item=type}
							    <li>
							    	<a href="/?filter={$type.slug}">{$type.name}</a>
							    </li>
							    {/foreach}
						  	</ul>
						</div>
					</div>
					{if !empty($stories)}
						<div class="card-story-container">
							{foreach from=$stories item=story}
							<div class="card-story">
								<h2>
									<a href="/story/view/{$story.slug}">
										{$story.title}
									</a>
								</h2>
								<div class="media media-card">
									<div class="media-left">
											<img src="/resource/img/user/{$story.owner_image}" class="media-object">
									</div>
									<div class="media-body">
										<ul class="list-story">
											<li>
												<a class="story-type">
													{if $story.type == NOVEL}
														Novel
													{elseif $story.type == SCREENPLAY}
														Screenplay
													{elseif $story.type == SHORT_STORY}
														Short Story
													{elseif $story.type == STORY}
														Story
													{/if}
												</a>
											</li>
											<span class="list-dotted"></span>
											<li>
												by {if !empty($user['user_id'])} <a href="/user/view/{$story.owner_id}">{/if}{$story.owner_name}{if !empty($user['user_id'])}</a>{/if}
											</li>
											<span class="list-dotted"></span>
											<li class="mobile-block">
												Last Edited {$story.time_updated}
											</li>
										</ul>
									</div>
								</div>
								{if !empty($story.image)}
								<div style="width: 100%;height: 300px;">
									<img src="/resource/img/story/{$story.image}" class="image-preview" style="border-radius: 0px; width: 100%; height:100%;">
								</div>
								{/if}
								<hr>
								<div class="card-story-footer">
									<ul class="list-inline list-unstyled pull-left mb0">
										{if $story.user_role >= ROLE_CONTRIBUTOR}
										<li>
											<a href="/story/edit/{$story.slug}" class="story-edit-link" data-story="{$story.slug}">
												<i class="fa fa-pencil"></i> Edit Story
											</a>
										</li>
										{/if}
										{if $story.user_role == ROLE_OWNER}
										<li>
											<a class="text-danger" href="/story/delete/{$story.slug}">
												<i class="fa fa-minus-square"></i> Delete Story
											</a>
										</li>
										{/if}
									</ul>
									<ul class="list-story-social hidden-xs">
										<li>
											<i class="fa fa-users"></i> <a>{$story.stats.no_contributors}</a>
										</li>
										<li>
											<i class="fa fa-eye"></i> <a>{$story.stats.no_views}</a>
										</li>
										<li>
											<i class="fa fa-pencil"></i> <a>{$story.stats.no_edits}</a>
										</li>
										<li>
											<i class="fa fa-comment"></i> <a>{$story.stats.no_comments}</a>
										</li>
										<li>
											<i class="fa fa-star"></i> <a>{$story.stats.rating}</a>
										</li>
									</ul>
								</div>
								{if !empty($story.50_words)}
								<div class="story-50-words">
									{$story.50_words}...
								</div>
								{/if}
							</div>
							{/foreach}
						</div>
						<a href="/stories/load_more_stories/" data-id="{$last_story_id}" data-filter="{$filter}" class="btn btn-info btn-block btn-load-more text-uppercase">Load More</a>
                    {else}
						<a disabled class="btn btn-info btn-block btn-load-more text-uppercase">No stories</a>
                    {/if}
				</div>
				<div class="col-sm-4 col-sm-offset-1">
					{if isset($best_story)}
					<aside>
						<div class="title-with-border">
							Best Story of Month 
						</div>
						<div class="aside-item" style="border-bottom: unset;">
							<h3 class="aside-caption">
								<a href="/story/view/{$best_story.slug}">
									{$best_story.title}
								</a>
							</h3>
							<p>by {$best_story.owner_name}</p>
						</div>
					</aside>
					{/if}
					{if !empty($other_stories)}
					<aside>
						<div class="title-with-border">
							Trending Now 
						</div>
						{foreach from=$other_stories item=other_story}
						<div class="aside-item">
							<h3 class="aside-caption">
								<a href="/story/view/{$other_story.slug}">
									{$other_story.title}
								</a>
							</h3>
							<p>by {$other_story.owner_name}</p>
						</div>
						{/foreach}
					</aside>
					{/if}
				</div>
			</div>
		</div>
	</section>
</main>
<script type="text/javascript">
			
	var enjoyhint_instance = new EnjoyHint({});

	var enjoyhint_script_steps = [];

	{if isset($smarty.session.user) && isset($smarty.session.user.user_id)}

		enjoyhint_script_steps.push({
		
			selector:'#write-story',
			
			description:'Create a New Story and Invite other to collaborate in your story.',
			
			shape:'circle',

			showNext: true,

		});

	{else}
		enjoyhint_script_steps.push({
		
			selector:'#register-btn',
			
			description:'New to Fableus?<br>Register Now to get a wonderful experience of writing a story.',
			
			shape:'circle',

			showNext: true,

		});

		enjoyhint_script_steps.push({
		
			selector:'#login-btn',
			
			description:'Login and start creating or collaborating stories.',
			
			shape:'circle',

			showNext: true,

		});
	{/if}

	enjoyhint_script_steps.push({
		
		selector:'#tell-a-friend',
		
		description:'Share Fableus with Friends, Family & Colleagues and Start Creating or Collaborating Stories.',
		
		shape:'circle',

		showNext: true,

	});

	enjoyhint_script_steps.push({
		
		selector:'#fableus-search',
		
		description:'Search Fableus for others stories.',
		
		shape:'circle',

		skipButton : { text : "Finish" }

	});

	enjoyhint_instance.set(enjoyhint_script_steps);

	$('.learn-more').on('click', function() {

		enjoyhint_instance.run();

	});

</script>