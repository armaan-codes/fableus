<div class="modal-dialog modal-lg" style="height: 470px;">
	<div class="modal-content">
		<div class="modal-header" align="center">
			<h2>Payment Successful</h2>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					<h4 align="center">
						Congratulations {$user.name}! your account is successfully activated with plan benfits. Kindly Login.
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
			<br>
			<div class="row">
				<div class="col-sm-2 col-sm-offset-5">
					<a href="#" class="btn btn-outline btn-outline-info btn-block btn-lg text-uppercase" data-toggle="modal" data-target="#sign-modal" style="color: #529ecc;">Login</a>
				</div>
			</div>
		</div>
	</div>
</div>