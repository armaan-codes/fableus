<div class="chat-panel">
	<div class="chat-head">
		<div class="chat-title"></div>
		<span class="pull-right close-chat-panel">x</span>
	</div>
	<div class="chat-body"></div>
	<div class="chat-foot"></div>
	<div class="chat-script"></div>
</div>
<script type="text/javascript">
	var chat_panel_timer;

	$(".close-chat-panel").click(function(){
	
		var panel = $(this).closest(".chat-panel");
	
		var active_class = $(panel).hasClass("active");

		if (active_class) {
	
			panel.removeClass("active");
	
			clearInterval(chat_panel_timer);
	
			check_message_timer = setInterval(check_new_messages, 500);
	
			$(".chat-body").html("");
	
			$(".chat-title").html("");
	
			$(".chat-foot").html("");
	
			$(".chat-script").html("");
		}
	});
</script>