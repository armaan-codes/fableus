<div class="modal-dialog modal-lg" style="height: 470px;">
	<div class="modal-content">
		<div class="modal-header" align="center">
			<h2>Upgrade Successful</h2>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					<h4 align="center">
						Congratulations {$user.name}! your plan is successfully upgraded.
					</h4>
					<h5 align="center">
						<span style="font-size: 20px;">
							<strong>Membership Plan: </strong>
						</span>
						{if $user.plan == MEMBER_PREMIUM}
							Advance ($1.99/Month)
						{elseif $user.plan == MEMBER_AUTHOR}
							Author ($2.99/Month)
						{/if}
					</h5>
					<h5 align="center">
						<span style="font-size: 20px;">
							<strong>Payment Id: </strong>
						</span>
						{$payment.payment_id}
					</h5>
				</div>
			</div>
		</div>
	</div>
</div>