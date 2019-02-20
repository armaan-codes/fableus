<div class="modal fade" id="edit-project" tabindex="-1">
	<div class="modal-dialog modal-lg" style="width: 900px;">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal">
				<i class="fa fa-times"></i>
			</button>
			<div class="modal-header">
				<div class="modal-logo">
					<img src="/resource/img/elements/site-logo.svg" alt="logo">
				</div>
				<h2 align="center">
					<i><strong>Edit Project: </strong></i> {$project.title}
				</h2>
			</div>
			<div class="modal-body">
				<div class="edit-message"></div>
				<div class="edit-container">
					<h4 align="center" class="text-muted" style="margin-bottom: 10px;">
						Project Budget (USD): 
						<span style="font-size: 18px;">${$project.range_from} - ${$project.range_to}</span>
					</h4>
					<form action="/projects/edit/{$project.id}" id="edit-project-form" method="POST">
						<div class="form-group form-group-lg">
							<div class="row">
								<div class="col-sm-3">
									<h4 class="text-muted bid-input-label" align="right">Category:</h4>
								</div>
								<div class="col-sm-8">
									<select class="form-control" style="line-height: 25px;" name="edit-category">
										<option value="{STORY}" {if $project.category == STORY} selected {/if}>Story</option>
										<option value="{SHORT_STORY}" {if $project.category == SHORT_STORY} selected {/if}>Short Story</option>
										<option value="{NOVEL}" {if $project.category == NOVEL} selected {/if}>Novel</option>
										<option value="{SCREENPLAY}" {if $project.category == SCREENPLAY} selected {/if}>Screenplay</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<div class="row">
								<div class="col-sm-3">
									<h4 class="text-muted bid-input-label" align="right">Bid (from-to in USD):</h4>
								</div>
								<div class="col-sm-4">
									<input type="number" name="edit-range-from" class="form-control" min="0" step="0.01" placeholder="Enter range from (USD)" value="{$project.range_from}" required>
								</div>
								<div class="col-sm-4">
									<input type="number" name="edit-range-to" class="form-control" min="0.01" step="0.01" placeholder="Enter range to (USD)" value="{$project.range_to}" required>
								</div>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<div class="row">
								<div class="col-sm-3">
									<h4 class="text-muted bid-input-label" align="right">Description:</h4>
								</div>
								<div class="col-sm-8">
									<textarea class="form-control" name="edit-description" id="edit-proposal-textarea" placeholder="Project Description...">{$project.description}</textarea>
								</div>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<div class="row">
								<div class="col-sm-3 col-sm-offset-8">
									<button type="submit" class="btn btn-info btn-block btn-lg text-uppercase">
										Update!
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	CKEDITOR.replace("edit-proposal-textarea");
</script>