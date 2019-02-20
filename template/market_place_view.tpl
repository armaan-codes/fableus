<main>
	<section class="section">
		<div class="container">
			<div class="row">
				<div {if $project.status == 1} class="col-md-10 col-md-offset-1" {else} class="col-md-8" {/if} id="view-project">
					<div class="project-container">
						<h2 align="center">
							<b>{$project.title}</b><br>
							{if $project.status == 1}
								<span class="text-muted" style="font-size: 18px;">(Closed)</span>
							{/if}
						</h2>
						<div class="media media-card">
							<div class="media-left">
								<img src="/resource/img/user/{$project.owner_image}" class="media-object">
							</div>
							<div class="media-body">
								<ul class="list-story">
									<span class="list-dotted"></span>
									<li>
										<a href="#">
											{if $project.category == NOVEL}
												Novel
											{elseif $project.category == SCREENPLAY}
												Screenplay
											{elseif $project.category == SHORT_STORY}
												Short Story
											{elseif $project.category == STORY}
												Story
											{else}
												Undefined
											{/if}
										</a>
									</li>
									<span class="list-dotted"></span>
									<li>
										by 	<a href="/user/view/{$project.owner_id}">{$project.owner_name}</a>
									</li>
									<span class="list-dotted"></span>
									<li>
										Created {$project.time}
									</li>
								</ul>
							</div>
						</div>
						<hr>
						<div class="project-description">{$project.description}</div>
						<hr>
						<div class="project-price-area">
							<h3 class="text-muted">
								Range: 
								<span style="font-size: 20px;">${$project.range_from} - ${$project.range_to}</span>
							</h3>
						</div>
						{if $project.status == 0}
						<hr>
						<div class="project-bid-area">
							<div class="row">
								<div class="col-md-3">
									{if $user.user_id == $project.owner_id}
										<a href="#" data-toggle="modal" data-target="#edit-project" data-backdrop="static" class="btn btn-info btn-block text-uppercase">Edit Project</a>
									{else}
										{if $bid}
											<a href="#" data-toggle="modal" data-target="#bid-proposal" data-backdrop="static" class="btn btn-info btn-block text-uppercase">Edit Proposal</a>
										{else}
											<a href="#" data-toggle="modal" data-target="#place-bid" data-backdrop="static" class="btn btn-info btn-block text-uppercase">Submit Bid</a>
										{/if}
									{/if}
								</div>
								{if $user.user_id == $project.owner_id}
								<div class="col-md-4 pull-right">
									<a href="#" data-toggle="modal" data-target="#final-project" data-backdrop="static" class="btn btn-info btn-block text-uppercase">Finalise Project</a>
								</div>
								{/if}
							</div>
						</div>
						{else}
						<div class="awards-container">
							<p class="line-behind">Awards</p>
							<div class="row">
								{foreach from=$awards item=award}
								<div class="col-md-3 award-container">
									<div class="row">
										<div class="col-md-6 col-md-offset-3">
											<img src="/resource/img/user/{$award.author_image}" width="100">
										</div>
									</div>
									<p class="text-muted" align="center">
										<a href="/user/view/{$award.author_id}">{$award.author_name}</a>
									</p>
									<p class="text-muted" align="center">
										Task: {$award.author_task}
									</p>
									<p class="text-muted" align="center">
										Award: ${$award.task_amount}
									</p>
									{if $user.user_id == $project.owner_id}
									<div class="row">
										<div class="col-md-8 col-md-offset-2">
											<button class="btn btn-info btn-block text-uppercase">Pay</button>
										</div>
									</div>
									{/if}
								</div>
								{/foreach}
							</div>
						</div>
						{/if}
					</div>
				</div>
				{if $user.user_id == $project.owner_id && $project.status == 0}
				<div class="col-md-4">
					<div class="project-bidder-area">
						{if $bidders}
						{foreach from=$bidders item=bidder}
						<div class="bidder-detail">
							<div class="row">
								<div class="col-md-3">
									<img src="/resource/img/user/{$bidder.user_image}" width="40px" class="media-object">
								</div>
								<div class="col-md-7">
									<div class="row">
										<h3 class="text-muted">
											{$bidder.user_name}
										</h3>
									</div>
									<div class="row">
										{$bidder.time}
									</div>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-md-6">
									<h5 class="text-muted">
										Amount: <span style="font-size: 14px;">${$bidder.amount}</span>
									</h5>
								</div>
								<div class="col-md-6">
									<h5 class="text-muted">
										Days: <span style="font-size: 14px;">{$bidder.days}</span>
									</h5>
								</div>
								{if !empty($bidder.proposal)}
								<div class="col-md-12">
									<h5 class="text-muted">
										Proposal: <span style="font-size: 14px;">{$bidder.proposal}</span>
									</h5>
								</div>
								{/if}
							</div>
							<hr>
							<div class="row">
								<div class="col-md-6 pull-right">
									<a href="#" class="btn btn-info btn-block text-uppercase">Message</a>
								</div>
							</div>
						</div>
						{/foreach}
						{else}
						<h4 class="text-muted" align="center">No Biddings yet.</h4>
						{/if}
					</div>
				</div>
				{/if}
			</div>
		</div>
	</section>
</main>
{if $project.status == 0}
	{if $user.user_id == $project.owner_id}
		{include file="market_place_edit.tpl"}
		{include file="market_place_final.tpl"}
	{else}
		{if $bid}
			{include file="market_place_proposal.tpl"}
		{else}
			{include file="market_place_bid.tpl"}
		{/if}
	{/if}
{/if}