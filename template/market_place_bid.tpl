<div class="modal fade" id="place-bid" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal">
				<i class="fa fa-times"></i>
			</button>
			<div class="modal-header">
				<div class="modal-logo">
					<img src="/resource/img/elements/site-logo.svg" alt="logo">
				</div>
				<h2 align="center">
					<i><strong>Place Bid!</strong></i>
				</h2>
			</div>
			<div class="modal-body">
				{if $user.plan == MEMBER_AUTHOR}
				<div class="bidding-message"></div>
				<div class="bidding-container">
					<h3 align="center" class="text-muted" style="margin-bottom: 10px;">{$project.title}</h3>
					<h4 align="center" class="text-muted" style="margin-bottom: 10px;">
						Project Budget (USD): 
						<span style="font-size: 18px;">${$project.range_from} - ${$project.range_to}</span>
					</h4>
					<form action="/market/bid_project/{$project.id}" id="place-bid-form" method="POST">
						<div class="form-group form-group-lg">
							<div class="row">
								<div class="col-sm-7 col-sm-offset-3">
									<input type="number" min="{$project.range_from}" max="{$project.range_to}" step="0.01" class="form-control" name="bid_amount" placeholder="Your bid amount (USD)..." required>
								</div>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<div class="row">
								<div class="col-sm-7 col-sm-offset-3">
									<input type="number" class="form-control" name="bid_days" min="1" placeholder="No. of Days" required>
								</div>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<div class="row">
								<div class="col-sm-7 col-sm-offset-3">
									<textarea class="form-control" name="bid_proposal" placeholder="Write a Proposal..." style="min-height: 150px;" required></textarea>
								</div>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<div class="row">
								<div class="col-sm-3 col-sm-offset-7">
									<button type="submit" class="btn btn-info btn-block btn-lg text-uppercase">
										Place Bid!
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				{else}
				<h3 align="center" class="text-muted">
					Oh-uh! You are not eligible to bid.<br>Kindly Upgrade to <strong>Author</strong> to start bidding.
				</h3>
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<div class="card">
							<h3 align="center" style="font-size: 25px; margin-bottom: 1px;">Author</h3>
							<h4 align="center" class="text-muted">$ 2.99/Month</h4>
							<span class="text-muted" style="font-size: 16px; margin-left: 42%">Benefits</span>
							<ul style="margin-top: 20px;">
								<li>Create articles</li>
								<li>Collaborate in other articles</li>
								<li>Export article (as pdf)</li>
								<li>Live chat with contributors/owner of article</li>
								<li>Share article on Social Media</li>
								<li>Article Analysis</li>
								<li>Bidding/Market Place</li>
							</ul>
							<div class="center">
								<a href="#" class="btn btn-info" style="margin-left: 13%;">Go Author</a>
							</div>
						</div>
					</div>
				</div>
				{/if}
			</div>
		</div>
	</div>
</div>