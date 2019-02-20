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
							    	<a href="/stories">All</a>
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
						<div class="card-story" data-id="{$story.id}">
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
							<div style="width: 100%;height: 200px;">
								<img src="/resource/img/story/{$story.image}" class="image-preview" style="border-radius: 0px; width: 100%; height:100%;">
							</div>
							{/if}
							<hr>
							<div class="card-story-footer">
								<ul class="list-inline list-unstyled pull-left mb0">
									{if $story.user_role == ROLE_OWNER}
										<li>
											<a href="/story/edit/{$story.slug}" class="story-edit-link" data-story="{$story.slug}">
												<i class="fa fa-pencil"></i> Edit Story
											</a>
										</li>
										<li>
											<div class="text-danger text-clickable delete-story" data-story-id="{$story.id}">
												<i class="fa fa-minus-square"></i> Delete Story
											</div>
										</li>
										{if !empty($story.story_apply)}
										<li>
											<button class="story-apply-request">
												<i class="fa fa-envelope-o" aria-hidden="true"></i>
												Requests
											</button>
										</li>
										{/if}
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
					<a href="/stories/load_user_stories/" data-id="{$last_story_id}" data-filter="{$filter}" class="btn btn-info btn-block btn-load-more text-uppercase">
						Load More
					</a>
					{else}
					<div class="card-story-container">
						<a href="#" data-toggle="modal" data-target="#story-title" data-backdrop="static" class="btn btn-info btn-block text-uppercase">Write your first story!</a>
					</div>
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
								<a href="/story/view/{$best_story.id}">
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
								<a href="/story/view/{$other_story.id}">
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
{include file="story_apply.tpl"}