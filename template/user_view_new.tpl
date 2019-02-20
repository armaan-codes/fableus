<style type="text/css">
	.profile-image {

		box-shadow: none;

		border: 3px solid white;

		margin-bottom: 25px;

	}
</style>
<main>
	<section>
		<div class="container-fluid">
			<div class="row" style="background-image: url('/resource/img/user-profile-bg-1.jpg'); background-size: cover; padding: 50px 20px;">
				<div class="col-sm-3" style="text-align: center;">
					<img src="/resource/img/user/{$member.image}" width="100" height="100" class="profile-image image-preview">
					<div class="row">
						<div class="col-sm-10 col-sm-offset-1">
							{if in_array($follow_check, $member.followers)}
							<button class="btn btn-default" id="member-unfollow" data-member-id="{$member.user_id}">
								<i class="fa fa-user-times" aria-hidden="true"></i> Unfollow
							</button>
							{else}
							<button class="btn btn-default" id="member-follow" data-member-id="{$member.user_id}">
								<i class="fa fa-user-plus" aria-hidden="true"></i> Follow
							</button>
							{/if}
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-sm-10 col-sm-offset-1">
							<div class="panel panel-default">
								<div class="panel-body">
									<h4 class="text-muted" style="margin-bottom: 0px;">
										{if empty($member.first_name) && empty($member.last_name)}
											{$member.name}
										{/if}

										{if !empty($member.first_name) || !empty($member.last_name)}
											{$member.first_name} {$member.last_name}
										{/if}
									</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-7 col-sm-offset-1">
					<div class="row">
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title" style="color: #777;">Nickname</h3>
								</div>
								<div class="panel-body">
									{$member.name}
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title" style="color: #777;">Bio</h3>
								</div>
								<div class="panel-body">
									{if !empty($member.bio)}
										{$member.bio}
									{else}
										No bio information.
									{/if}
								</div>
							</div>
						</div>
					</div>
					<div class="row" style="margin-top: 100px;">
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title" style="color: #777;">No. of Stories</h3>
								</div>
								<div class="panel-body">
									<strong>{$member.no_stories}</strong>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title" style="color: #777;">No. of Stories Contributed</h3>
								</div>
								<div class="panel-body">
									<strong>{$member.no_contributions}</strong>
								</div>
							</div>
						</div>
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
											<a href="/story/edit/{$story.slug}" class="story-edit-link" data-story="{$story.slug}">
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
					<a href="/stories/load_member_stories/{$member.user_id}" data-id="{$last_story_id}" data-filter="{$filter}" class="btn btn-info btn-block btn-load-more text-uppercase">Load More</a>
					{else}
					<!-- <div class="card-story-container">
						<a disabled class="btn btn-info btn-block text-uppercase">No stories</a>
					</div> -->
					{/if}
				</div>
				{if !empty($other_stories)}
				<div class="col-sm-4 col-sm-offset-1">
					<aside>
						<div class="title-with-border">
							Other Stories 
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