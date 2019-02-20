<div class="modal fade" id="story-title" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal">
				<i class="fa fa-times"></i>
			</button>
			<div class="modal-header">
				<div class="modal-logo" style="text-align: center;">
					<img src="/resource/img/elements/book-icons.png" style="width: 50%" alt="logo">
				</div>
				<h2 align="center">
					<i><strong>Story Title</strong></i>
				</h2>
			</div>
			<div class="modal-body">
				<div class="form-group form-group-lg">
					<div class="row">
						<div class="col-sm-8 col-sm-offset-2" id="story-error"></div>
					</div>
				</div>
				<form action="/story/create/" method="POST" id="create-story">
					<div class="form-group form-group-lg">
						<div class="row">
							<div class="col-sm-10 col-sm-offset-1">
								<input type="text" class="form-control" name="story_title" placeholder="Story title..." required>
							</div>
						</div>
					</div>
					<div class="form-group form-group-lg">
						<div class="row">
							<div class="col-sm-8 col-sm-offset-2">
								<span style="font-size: 16px;">
									<strong>Story Type:</strong>
								</span>
								<label class="radio-inline">
									<input type="radio" name="story_type" value="Novel">Novel
								</label>
								<label class="radio-inline">
									<input type="radio" name="story_type" value="Screenplay">Screenplay
								</label>
								<label class="radio-inline">
									<input type="radio" name="story_type" value="Short Story">Short Story
								</label>
								<label class="radio-inline">
									<input type="radio" name="story_type" value="Story" checked>Story
								</label>
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