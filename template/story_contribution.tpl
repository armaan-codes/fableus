<div class="sticky-bottom-holder">
	<div class="container">
		<div class="row">
			<div class="col-sm-2 col-sm-push-5 push-down mobile-text-center">
				<a href="#" class="sticky-bottom-trigger">
					<i class="fa fa-chevron-up"></i>
					<span>contributions</span>
				</a>
			</div>
			<div class="col-sm-5 col-sm-pull-2 push-down">
				<div class="media media-card">
					<div class="media-left">
						<img src="/resource/img/user/{$story.owner_image}" class="media-object">
					</div>
					<div class="media-body">
						<ul class="list-story">
							<span class="list-dotted"></span>
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
				{if $user.role == ROLE_OWNER}
					<a class="btn btn-info btn-lg text-uppercase mb10" data-toggle="modal" data-target="#invite-modal" data-backdrop="static">invite contributor</a>
					<a class="btn btn-info btn-lg text-uppercase mb10" data-toggle="modal" data-target="#invite-reader-modal" data-backdrop="static">invite to read</a>
				{elseif $user.role == ROLE_CONTRIBUTOR}
					<button class="btn btn-info btn-lg text-uppercase mb10" disabled>Already a contributor</button>
				{elseif $user.role < ROLE_CONTRIBUTOR}
					<a href="/story/apply_collab/{$story.id}" class="btn btn-info btn-lg text-uppercase mb10" id="apply_contribute">
						apply to contribute
					</a>
					<p class="proxima-light-font">Please allow up to 48 hours for a response.</p>
					<p class="proxima-light-font" id="collaboration-message"></p>
				{/if}
			</div>
			<div class="col-sm-5">
				<div class="holder-box hide-when-open">
					<ul class="list-story-social list-aligned hidden-xs pull-right">
						<li>
							<i class="fa fa-users"></i>
							<a href="#">{$stats.no_contributors}</a>
						</li>
						<li>
							<i class="fa fa-eye"></i>
							<a href="#">{$stats.no_views}</a>
						</li>
						<li>
							<i class="fa fa-pencil"></i>
							<a href="#">{$stats.no_edits}</a>
						</li>
						<li>
							<i class="fa fa-comment"></i>
							<a href="#">{$stats.no_comments}</a>
						</li>
						<li>
							<i class="fa fa-star"></i>
							<a href="#">{$stats.rating}</a>
						</li>
					</ul>
				</div>
				<ul class="list-social-info">
					<li class="sm-full-width xs-full-width">
						<div class="social-info-left">
							<p>
								<i class="fa fa-users"></i>
								Collaborators: <a>{$stats.no_contributors}</a>
							</p>
						</div>
						<div class="social-info-body">
							<ul class="list-contributor">
								{foreach from=$story['collab'] item=member}
								<li>
									<a href="/user/view/{$member.id}">
										<img src="/resource/img/user/{$member.image}" class="contributor-pic">
										{$member.name}
									</a>
								</li>    
								{/foreach}
							</ul>
						</div>
					</li>
					<li>
						<div class="social-info-left">
							<p>
								<i class="fa fa-eye"></i>
								Views: <a>{$stats.no_views}</a>
							</p>
						</div>
						<div class="social-info-body">
							<p>Last viewed {$stats.last_view.time}</p>
						</div>
					</li>
					<li>
						<div class="social-info-left">
							<p>
								<i class="fa fa-pencil"></i>
								Edits: <a>{$stats.no_edits}</a>
							</p>
						</div>
						<div class="social-info-body">
							<p>
								Last edited {$stats.last_edit.time} by <a href="/user/view/{$stats.last_edit.id}">{$stats.last_edit.name}</a>.
							</p>
						</div>
					</li>
					<li>
						<div class="social-info-left">
							<p>
								<i class="fa fa-comment"></i>
								Comments: <a>{$stats.no_comments}</a>
							</p>
						</div>
						<div class="social-info-body">
							{if !empty($stats.last_comment)}
								<p>Last comment posted 1 day ago</p>
							{else}
								<p>No Comments yet.</p>
							{/if}
						</div>
					</li>
				</ul>
				{if $story.owner_id != $user.user_id && $user.role < ROLE_OWNER}
					{if isset($user.rating) && empty($user.rating)}
					<div class="story-rating">
						<b>Rate Story:</b> <i class="fa fa-star-o" aria-hidden="true" data-rate="1" data-story-id="{$story.id}"></i> <i class="fa fa-star-o" aria-hidden="true" data-rate="2" data-story-id="{$story.id}"></i> <i class="fa fa-star-o" aria-hidden="true" data-rate="3" data-story-id="{$story.id}"></i> <i class="fa fa-star-o" aria-hidden="true" data-rate="4" data-story-id="{$story.id}"></i> <i class="fa fa-star-o" aria-hidden="true" data-rate="5" data-story-id="{$story.id}"></i>
					</div>
					{else}
					<div class="story-rating">
						Thanks for Rating!
					</div>
					{/if}
				{/if}
			</div>
		</div>
	</div>
</div>
{if $user.role == ROLE_OWNER}
	{include file="invite_collab.tpl"}
	{include file="invite_collab_read.tpl"}
{/if}