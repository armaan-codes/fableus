<div class="modal fade" id="user-followers" tabindex="-1">
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
					<i><strong>Followers</strong></i>
				</h2>
			</div>
			<div class="modal-body">
				<div class="row comment-section" style="padding:15px">
					{foreach $user.followers as $follower}
						<div class="col-sm-12 comment-view" style="padding: 15px 30px;">
							<div class="row">
								<img src="/resource/img/user/{$follower.image}" class="profile-image" width="50" style="margin-bottom: 0px;">
								<a href="/user/view/{$follower.user_id}" style="font-size: 16px;">{$follower.name}</a>
								<button class="btn btn-default pull-right compose-follower" data-follower-email="{$follower.email}" title="Compose">
									<i class="fa fa-paper-plane" aria-hidden="true"></i>
								</button>
							</div>
						</div>
					{/foreach}
					<div class="col-sm-12 compose-view" style="display: none;">
						<button class="close">
							<i class="fa fa-times"></i>
						</button>
						<div class="col-sm-8 col-sm-offset-2" style="padding: 25px;">
							<div class="compose-message"></div>
							<p class="line-behind">Compose Mail</p>
							<form action="/user/compose" id="compose-follower" method="POST">
								<div class="form-group">
									<label>To:</label>
									<input type="text" name="follower-email" class="form-control" readonly>
								</div>
								<div class="form-group">
									<label>Message:</label>
									<textarea class="form-control" name="follower-message" required></textarea>
								</div>
								<div class="form-group">
									<div class="col-sm-6 pull-right">
										<button class="btn btn-info btn-block text-uppercase" type="submit">Send</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {

		 $('textarea[name="follower-message"]').summernote({
		 	toolbar: [
				['style', ['bold', 'italic', 'underline']],
				['para', ['ul', 'ol', 'paragraph']],
				['fontsize', ['fontsize']],
			],
			height: 100,
		 });

		$('.compose-follower').on('click', function() {

			var email = $(this).attr('data-follower-email');
			
			if(email != "") {

				$('.compose-view input[name="follower-email"]').val(email);
				
				$('.comment-view').fadeOut(function() {

					$('.compose-view').fadeIn();
				
				});
			
			} else {

				location.reload();
			
			}


		});
		
		$('.compose-view button.close').on('click', function() {

			$('.compose-view').fadeOut(function() {

				$('.comment-view').fadeIn();
			
			});

		});

		$('#compose-follower').submit(function(event){

			var input = $(this).serialize();

			$.ajax({
				
				url: $(this).attr('action'),

				type: "POST",

				data: input,

				success: function(data) {

					var response = JSON.parse(data);

					$('.compose-message').html(response.data);

				}

			});

			event.preventDefault();
		});
	
	});
</script>