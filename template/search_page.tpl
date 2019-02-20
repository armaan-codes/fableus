<section class="section" id="section-anchor">
	<div class="container">
		<div class="row">
			<div class="col-md-7 col-sm-8 push-down">
				{if !empty($stories)}
				<div class="card-story-container">
					{foreach from=$stories item=story}
					<div class="card-story">
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
										<a href="#">
											{if $story.type == NOVEL}
												Novel
											{elseif $story.type == SCREENPLAY}
												Screenplay
											{elseif $story.type == SHORT_STORY}
												Short Story
											{elseif $story.type == STORY}
												Story
											{else}
												Undefined
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
								{if $story.user_role >= ROLE_CONTRIBUTOR}
								<li>
									<a href="/story/edit/{$story.id}">
										<i class="fa fa-pencil"></i>
										Edit Story
									</a>
								</li>
								<li>
									<div class="text-danger text-clickable">
										<i class="fa fa-minus-square"></i>
										Delete Story
									</div>
								</li>
								{/if}
							</ul>
							<ul class="list-story-social hidden-xs">
								<li>
									<i class="fa fa-users"></i>
									<a href="#">{$story.stats.no_contributors}</a>
								</li>
								<li>
									<i class="fa fa-eye"></i>
									<a href="#">{$story.stats.no_views}</a>
								</li>
								<li>
									<i class="fa fa-pencil"></i>
									<a href="#">{$story.stats.no_edits}</a>
								</li>
								<li>
									<i class="fa fa-comment"></i>
									<a href="#">{$story.stats.no_comments}</a>
								</li>
								<li>
									<i class="fa fa-arrow-up"></i>
									<a href="#">{$story.stats.no_votes}</a>
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
						<input type="hidden" name="ts-updated" value="{$story.ts_updated}">
						<input type="hidden" name="top-story" value="{$story.stats.top}">
					</div>
					{/foreach}
				</div>
				{else}
				<div class="card-story-container">
					<h3 class="text-muted" align="center">No Stories to display</h3>
				</div>
				{/if}
			</div>
		</div>
	</div>
</section>