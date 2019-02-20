<main>
	<section class="section" id="section-anchor">
		<div class="container">
			<div class="row">
				<div class="col-sm-7 push-down">
					<div class="title-holder">
						<div class="dropdown">
						  	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						  		Story Type
						  		<i class="fa fa-chevron-down icon"></i>
						  	</a>
						  	<ul class="dropdown-menu">
							    <li>
							    	<a href="/stories/collabs">All</a>
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
						<div class="card-story" id="{$story.id}" data-updated="{$story.ts_updated}" data-top="{$story.stats.top}">
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
										{if $story.publish == DEVELOPMENT}
										<span class="list-dotted"></span>
										<li class="mobile-block">
											<span style="color: red; font-weight: 600;">Development</span>
										</li>
										{/if}
									</ul>
								</div>
							</div>
							{if !empty($story.image)}
							<div style="width: 100%;height: 300px;">
								<img src="/resource/img/story/{$story.image}" class="image-preview" style="border-radius: 0px; width: 100%;height:100%;">
							</div>
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
					<a href="/stories/load_collab_stories/" data-id="{$last_story_id}" data-filter="{$filter}" class="btn btn-info btn-block btn-load-more text-uppercase">
						Load More
					</a>
					{else}
					<div class="card-story-container">
						<a disabled class="btn btn-info btn-block text-uppercase">Apply to start your first collaboration!</a>
					</div>
					{/if}
				</div>
				<div class="col-sm-5">
					<!-- Collaboration Invite -->
					{if !empty($invites.contribution)}
						<div class="col-sm-12">
							<div class="title-holder">
								<span class="text-mutted">Contribution Invite</span>
								<p class="pull-right text-mutted">
									{$invites.contribution|count}
									{if $invites.contribution|count > 1}
										Invites
									{else}
										Invite
									{/if}
								</p>
							</div>
							<div class="card-invite-container">
								{foreach from=$invites.contribution item=story}
								<div class="card-invite">
									<h2 class="story-caption">
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
													by
													<a href="/user/view/{$story.owner_id}">
														{$story.owner_name}
													</a>
												</li>
												<span class="list-dotted"></span>
												<li>
													Requested {$story.time_requested}
												</li>
											</ul>
										</div>
									</div>
									{if !empty($story.50_words)}
									<div class="story-50-words">
										{$story.50_words}...
									</div>
									{/if}
									<div class="card-story-footer">
										<ul class="list-story-social hidden-xs pull-right" style="padding: 10px">
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
										</ul>
									</div>
									<div class="row card-story-footer">
										<div class="col-sm-4 col-sm-offset-3" style="margin-top: 5px">
											<a href="/stories/accept/{$story.slug}" class="btn btn-info btn-block text-uppercase">Accept</a>
										</div>
										<div class="col-sm-4"  style="margin-top: 5px">
											<a href="/stories/decline/{$story.slug}" class="btn btn-info btn-block text-uppercase">Decline</a>
										</div>
									</div>
								</div>
								{/foreach}
							</div>
						</div>
					{/if}
					<!-- Collaboration Read Invite -->
					{if !empty($invites.contribution_read)}
						<div class="col-sm-12">
							<div class="title-holder">
								<span class="text-mutted">Read Contribution Invite</span>
								<p class="pull-right text-mutted">
									{$invites.contribution_read|count}
									{if $invites.contribution_read|count > 1}
										Invites
									{else}
										Invite
									{/if}
								</p>
							</div>
							<div class="card-invite-container">
								{foreach from=$invites.contribution_read item=story}
								<div class="card-invite">
									<h2 class="story-caption">
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
													by
													<a href="/user/view/{$story.owner_id}">
														{$story.owner_name}
													</a>
												</li>
												<span class="list-dotted"></span>
												<li>
													Requested {$story.time_requested}
												</li>
											</ul>
										</div>
									</div>
									{if !empty($story.50_words)}
									<div class="story-50-words">
										{$story.50_words}...
									</div>
									{/if}
									<div class="card-story-footer">
										<ul class="list-story-social hidden-xs pull-right" style="padding: 10px">
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
										</ul>
									</div>
									<div class="row card-story-footer">
										<div class="col-sm-4 col-sm-offset-3" style="margin-top: 5px">
											<a href="/stories/accept_read/{$story.slug}" class="btn btn-info btn-block text-uppercase">Accept</a>
										</div>
										<div class="col-sm-4"  style="margin-top: 5px">
											<a href="/stories/decline_read/{$story.slug}" class="btn btn-info btn-block text-uppercase">Decline</a>
										</div>
									</div>
								</div>
								{/foreach}
							</div>
						</div>
					{/if}
				</div>
			</div>
		</div>
	</section>
</main>