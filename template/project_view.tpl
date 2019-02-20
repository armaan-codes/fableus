<main>
	<section class="section">
		<div class="container">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1" id="view-project">
					<div class="project-container">
						<h2 align="center">
							<b>{$project.title}</b><br>
							{if $project.status == 1}
								<span class="text-muted" style="font-size: 18px;">(Awarded)</span>
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
									<span class="list-dotted"></span>
									<li>
										<i class="fa fa-gavel" aria-hidden="true"></i> <a>{$project.stats.bids}</a>
									</li>
								</ul>
							</div>
						</div>
						<hr>
						<div class="project-description">{$project.description}</div>
						<hr>
						<div class="row">
							<div class="project-price-area col-sm-4">
								<h3 class="text-muted">
									Range: 
									<span style="font-size: 20px;">${$project.range_from} - ${$project.range_to}</span>
								</h3>
							</div>
							<div class="project-bid-area col-sm-3 pull-right">
							{if $project.status == 0 && $user.user_id != $project.owner_id}
								{if $user.bid}
								<a href="#" data-toggle="modal" data-target="#bid-proposal" data-backdrop="static" class="btn btn-info btn-block text-uppercase">Edit Proposal</a>
								{else}
								<a href="#" data-toggle="modal" data-target="#place-bid" data-backdrop="static" class="btn btn-info btn-block text-uppercase">Submit Bid</a>
								{/if}
							{elseif $project.status == 0 && $user.user_id == $project.owner_id}
								<a href="#" data-toggle="modal" data-target="#edit-project" data-backdrop="static" class="btn btn-info btn-block text-uppercase">Edit Project</a>
							{/if}
							</div>
							<div class="col-sm-3 pull-right">
							{if $project.status == 0 && $user.user_id == $project.owner_id}
								<a href="#" data-toggle="modal" data-target="#project-award" data-backdrop="static" class="btn btn-info btn-block text-uppercase">Award Project</a>
							{/if}
							</div>
						</div>
						{if $project.status == 1}
						<div class="awards-container">
							<p class="line-behind">Awards</p>
							<div class="row">
								{foreach from=$awards item=award}
								<div class="col-sm-3 award-container">
									<div class="row">
										<div class="col-sm-6 col-sm-offset-3">
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
										<div class="col-sm-8 col-sm-offset-2">
											<button class="btn btn-info btn-block text-uppercase">
												Pay
											</button>
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
			</div>
		</div>
		{if $bids && $project.status == 0}
		<div class="container">
			<p class="line-behind">Bidders</p>
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					<div class="bidder-area">
						{foreach from=$bids item=bid}
						<div class="bidder-detail">
							<div class="media media-card">
								<div class="media-left">
									<img src="/resource/img/user/{$bid.bidder_image}" class="media-object">
								</div>
								<div class="media-body">
									<ul class="list-story">
										<span class="list-dotted"></span>
										<li>
											by 	<a href="/user/view/{$bid.bidder_id}">{$bid.bidder_name}</a>
										</li>
										<span class="list-dotted"></span>
										<li>
											{$bid.time}
										</li>
										<li class="pull-right">
											<i class="fa fa-gavel" aria-hidden="true"></i> <a>${$bid.bid_amount}</a>
										</li>
									</ul>
									{if $user.user_id == $project.owner_id}
									<hr>
									<div class="bid-proposal">
										{$bid.bid_proposal}
									</div>
									<hr>
									<div class="row">
										<div class="col-sm-3 pull-right">
											<button class="btn btn-info btn-project-chat btn-block text-uppercase" data-user="{$bid.bidder_id}">
												<i class="fa fa-envelope" aria-hidden="true"></i> Message
											</button>
										</div>
									</div>
									{/if}
								</div>
							</div>
						</div>
						{/foreach}
					</div>
				</div>
			</div>
		</div>
		{/if}
	</section>
</main>
{if $project.status == 0 && $user.user_id != $project.owner_id}
	{if $user.bid}
		{include file="market_place_proposal.tpl"}
	{else}
		{include file="market_place_bid.tpl"}
	{/if}
{elseif $project.status == 0 && $user.user_id == $project.owner_id}
	{include file="project_edit.tpl"}
	{include file="project_award.tpl"}
	{include file="chat_panel.tpl"}
{/if}
<script type="text/javascript">
	$(".btn-project-chat").on("click", function(event){
		clearInterval(chat_panel_timer);
		var user_id = $(this).attr("data-user");
		$.ajax({
			url: "/messages/chat/",
			type: "POST",
			data: { "member_id": user_id },
			success: function(data) {
				var response = JSON.parse(data);
				if (response.status == "Failure") {
					location.reload();
				}

				if (response.status == "Success") {
					$(".chat-body").html(response.data);
					$(".chat-title").html(response.title);
					$(".chat-foot").html(response.form);
					$(".chat-script").html(response.script);
					$(".chat-panel").addClass("active");
					var body_height = $(".chat-body").prop("scrollHeight");
					$(".chat-body").scrollTop(body_height);
				}
			}
		});
	});
</script>