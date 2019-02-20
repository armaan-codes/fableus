<style type="text/css">
	.user-area .profile-image {
		width: 35px !important;
		height: 35px !important;
	}
</style>
<main>
	<section>
		<div class="container">
			<div class="row message-container">
				<div class="col-sm-4 message-list">
					<div id="story-messages">
						{if !empty($chats.story)}
						{foreach from=$chats.story item=chat}
						<div class="message-thumbnail" data-room={$chat.room_id}>
							<h4 class="text-muted" style="margin-bottom: 5px;">
								<strong>{$chat.title}</strong>
							</h4>
							{if !empty($chat.message)}
							<div class="row" style="margin: 0px;" data-message="{$chat.message.id}">
								<div class="col-sm-10 last-message-col">
									{$chat.message.message}
								</div>
								<div class="col-sm-2 last-message-time-col">
									{$chat.message.time}
								</div>
							</div>
							{else}
							<div class="row" style="margin: 0px; text-align: -webkit-center" data-message="0">
								Start conversation.
							</div>
							{/if}
						</div>
						{/foreach}
						{else}
							<p class="text-muted" align="center">No Conversation.</p>
						{/if}
					</div>
				</div>
				<div class="col-sm-8">
					<div class="initial-message">
						<p class="line-behind">Select any Conversation.</p>
					</div>
					<div class="message-box">
						<div class="message-head row"></div>
						<div class="message-body"></div>
						<div class="message-foot row"></div>
						<div class="message-script"></div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>
<script type="text/javascript">
	var chat_timer;
	
	$(".message-thumbnail").on("click", function(){
	
		clearInterval(chat_timer);
	
		$(".message-box").hide();
	
		var thumbnail = $(this);
	
		var room_id = $(this).attr("data-room");
	
		$.ajax({
	
			url: "/messages/load/" + room_id,
	
			success: function(data){
	
				var response = JSON.parse(data);
	
				if (response.status == "Success") {
	
					$(".message-head").html(response.title);
	
					$(".message-body").html(response.data);
	
					$(".message-foot").html(response.form);
	
					$(".message-script").html(response.script);
	
					$(".initial-message").remove();
	
					$(".message-box").show();
	
					var height = $(".message-body").prop("scrollHeight");
	
					$(".message-body").scrollTop(height);
	
				}

				if (response.status == "Failure") {

					location.reload();

				}

			}

		});

	});
</script>