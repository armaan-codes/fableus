<div class="modal fade" id="new-project" tabindex="-1">
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
					<i><strong>New Project</strong></i>
				</h2>
			</div>
			<div class="modal-body">
				<form id="new-project-form" action="/market/create" method="POST">
					<div class="form-group form-group-lg">
						<div class="row">
							<div class="col-sm-10 col-sm-offset-1">
								<div id="new-project-message"></div>
							</div>
						</div>
					</div>
					<div class="form-group form-group-lg">
						<div class="row">
							<div class="col-sm-10 col-sm-offset-1">
								<span style="font-size: 18px;">
									<strong>Project Title</strong>
								</span>
								<input type="text" class="form-control" name="project-title" placeholder="Project Title" required>
							</div>
						</div>
					</div>
					<div class="form-group form-group-lg">
						<div class="row">
							<div class="col-sm-10 col-sm-offset-1">
								<span style="font-size: 18px;">
									<strong>Project Description</strong>
								</span>
								<textarea class="form-control" name="description" id="project-description" rows="5" placeholder="Project Description..." required></textarea>
							</div>
						</div>
					</div>
					<div class="form-group form-group-lg">
						<div class="row">
							<div class="col-sm-10 col-sm-offset-1">
								<span style="font-size: 18px;">
									<strong>Category: </strong>
								</span>
								<select class="form-control" style="line-height: 25px;" name="category">
									<option value="{STORY}" selected>Story</option>
									<option value="{SHORT_STORY}">Short Story</option>
									<option value="{NOVEL}">Novel</option>
									<option value="{SCREENPLAY}">Screenplay</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group form-group-lg">
						<div class="row">
							<div class="col-sm-6 col-sm-offset-1">
								<span style="font-size: 18px;">
									<strong>Range (from-to in USD): </strong>
								</span>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-5 col-sm-offset-1">
								<input type="number" name="range-from" class="form-control" min="0" step="0.01" placeholder="Enter range from (USD)" required>
							</div>
							<div class="col-sm-5">
								<input type="number" name="range-to" class="form-control" min="0.01" step="0.01" placeholder="Enter range to (USD)" required>
							</div>
						</div>
					</div>
					<div class="form-group form-group-lg">
						<div class="row">
							<div class="col-sm-3 col-sm-offset-8">
								<button type="submit" class="btn btn-info btn-block btn-lg text-uppercase">
									Create
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	CKEDITOR.replace("project-description");
</script>