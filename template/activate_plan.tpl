<div class="modal-dialog modal-lg" style="height: 470px;">
	<div class="modal-content">
		<div class="overlay" id="activate-spinner">
			<div class="spinner"></div>
		</div>
		<div class="modal-header" align="center">
			<h2>Activate Plan</h2>
		</div>
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3" id="activate-error"></div>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					<h4 align="center">
						We noticed you initiated for {if $plan.plan == MEMBER_PREMIUM}Advance{/if}{if $plan.plan == MEMBER_AUTHOR}Author{/if} plan. Kindly make payment to activate the plan benifits.
					</h4>
					<h5 align="center">
						<span style="font-size: 20px;">
							<strong>Plan Details:</strong>
						</span>
						{if $plan.plan == MEMBER_PREMIUM}
							Advance (${$plan.amount}/{$plan.period})
						{elseif $plan.plan == MEMBER_AUTHOR}
							Author (${$plan.amount}/{$plan.period})
						{/if}
					</h5>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-sm-3 col-sm-offset-3">
					<h2 align="center">
						<a class="btn btn-info btn-block btn-lg text-uppercase" id="pay-pending-btn">
							Activate
						</a>
					</h2>
				</div>
				<div class="col-sm-3">
					<h2 align="center">
						<a class="btn btn-info btn-block btn-lg text-uppercase" id="delete-pending-btn">
							Delete
						</a>
					</h2>
				</div>
			</div>
		</div>
	</div>
</div>