<style type="text/css">
	.line-behind:before, .line-behind:after {
		width: 135px !important;
	}


	.user-image {
		position: relative;
		top: -50px;
	}
</style>
<main>
	<section>
		<div class="container">
			<div class="profile-area">
				<div class="row" style="background-image: url('/resource/img/user-profile-bg.jpg'); height: 200px;"></div>
				<div class="row" style="margin-right: -5px;">
					<div class="col-sm-2 user-image">
						<div class="col-sm-10 col-sm-offset-1">
							<img src="/resource/img/user/{$member.image}" width="100" height="100" class="profile-image image-preview">
						</div>
					</div>
					<div class="col-sm-2 profile-box">
						Name:
						<h3 class="text-muted" align="right"><strong>{$member.name}</strong></h3>
					</div>
					<div class="col-sm-2 profile-box">
						Membership:
						<h3 class="text-muted" align="right">
							<strong>Regular</strong>
						</h3>
					</div>
					<div class="col-sm-2 profile-box">
						No. of Stories:
						<h3 class="text-muted" align="right"><strong>{$member.no_stories}</strong></h3>
					</div>
					<div class="col-sm-2 profile-box">
						No. of Story Contributions:
						<h3 class="text-muted" align="right"><strong>{$member.no_contributions}</strong></h3>
					</div>
					<div class="col-sm-2 profile-box">
						
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="section" id="section-anchor">
		<div class="container">
			<div class="row">
				<div class="col-sm-7">
					<div class="title-holder">
						<div class="dropdown">
						  	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						  		Story Type
						  		<i class="fa fa-chevron-down icon"></i>
						  	</a>
						  	<ul class="dropdown-menu">
							    <li>
							    	<a href="/user/view/{$member.user_id}">All</a>
							    </li>
							    <li>
							    	<a href="?filter=novel">Novel</a>
							    </li>
							    <li>
							    	<a href="?filter=screenplay">Screenplay</a>
							    </li>
							    <li>
							    	<a href="?filter=short-story">Short Story</a>
							    </li>
							    <li>
							    	<a href="?filter=story">Story</a>
							    </li>
						  	</ul>
						</div>
					</div>
					{if !empty($stories)}
					<div class="card-story-container">
						{foreach from=$stories item=story}
						<div class="card-story" id="{$story.id}">
							<h2>
								<a href="/story/view/{$story.id}">
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
											<a>
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
											by <a href="/user/view/{$story.owner_id}">{$story.owner_name}</a>
										</li>
										<span class="list-dotted"></span>
										<li class="mobile-block">
											Last Edited {$story.time_updated}
										</li>
									</ul>
								</div>
							</div>
							{if !empty($story.image)}
								<img src="/resource/img/story/{$story.image}" class="image-preview" style="border-radius: 0px; width: 100%;">
							{/if}
							<hr>
							<div class="card-story-footer">
								<ul class="list-inline list-unstyled pull-left mb0">
									{if $story.user_role == ROLE_CONTRIBUTOR}
										<li>
											<a href="/story/edit/{$story.id}">
												<i class="fa fa-pencil"></i> Edit Story
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
										<i class="fa fa-arrow-up"></i> <a>{$story.stats.no_votes}</a>
									</li>
								</ul>
							</div>
							{if !empty($story.50_words)}
							<div class="story-50-words">
								{$story.50_words}...
							</div>
							{/if}
							{if !empty($story.collabs)}
							<p class="line-behind">Contributors</p>
							<ul class="list-contributor">
								{foreach from=$story['collabs'] item=collab}
								<li>
									<a href="/user/view/{$collab.id}">
										<img src="/resource/img/user/{$collab.image}" class="contributor-pic">
										{$collab.name}
									</a>
								</li>
								{/foreach}
							</ul>
							{/if}
						</div>
						{/foreach}
					</div>
					<a href="/stories/load_member_stories/{$member.user_id}" data-id="{$last_story_id}" data-filter="{$filter}" class="btn btn-info btn-block btn-load-more text-uppercase">Load More</a>
					{else}
					<div class="card-story-container">
						<a disabled class="btn btn-info btn-block text-uppercase">No stories</a>
					</div>
					{/if}
				</div>
				{if !empty($other_stories)}
				<div class="col-sm-4 col-sm-offset-1">
					<aside>
						<div class="title-with-border">
							Trending Now
						</div>
						{foreach from=$other_stories item=other_story}
						<div class="aside-item">
							<h3 class="aside-caption">
								<a href="/story/view/{$other_story.id}">
									{$other_story.title}
								</a>
							</h3>
							<p>by {$other_story.owner_name}</p>
						</div>
						{/foreach}
					</aside>
				</div>
				{/if}
			</div>
		</div>
	</section>
</main>