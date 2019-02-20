<style type="text/css">
	.chat-panel {
		bottom: 60px;
	}
</style>
<div id="google_translate_element" class="pull-right"></div>

<script type="text/javascript">
function googleTranslateElementInit() {
	new google.translate.TranslateElement({ pageLanguage: 'en', includedLanguages: 'en,hi,bn' }, 'google_translate_element');
}
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<main>
	<section class="section-xs">
		<div class="container" style="padding: 15px;">
			<div class="row">
				<div class="col-sm-4 col-sm-offset-4 story-message">
					{if $story.publish == 0}
					<div class="alert alert-warning">
						<p align="center">Story is under development mode.</p>
					</div>
					{/if}
				</div>
			</div>
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2 story-container" style="overflow: hidden">
					<div class="row">
						<div class="col-md-6">
							<div class="col-xs-3" style="text-align: right">
								<img src="/resource/img/user/{$story.owner_image}" class="profile-image" height="60" width="60">
							</div>
							<div class="col-xs-9">
								<a style="font-size: 18px; font-weight: 600;" {if !empty($user['user_id'])}href="/user/view/{$story.owner_id}"{/if}>{$story.owner_name}</a>
								{if !empty($user['user_id']) && $user['user_id'] != $story['owner_id']}
								{if in_array($follow_check, $story.owner_followers)}
								<button class="btn" id="member-unfollow" data-member-id="{$story.owner_id}">
									<i class="fa fa-user-times" aria-hidden="true"></i> Unfollow
								</button>
								{else}
								<button class="btn" id="member-follow" data-member-id="{$story.owner_id}">
									<i class="fa fa-user-plus" aria-hidden="true"></i> Follow
								</button>
								{/if}
								{/if}
								<br>
								<span class="list-dotted"></span>
								<a>
									{if $story.type == NOVEL}
										Novel
									{elseif $story.type == SCREENPLAY}
										Screenplay
									{elseif $story.type == SHORT_STORY}
										Short Story
									{elseif $story.type == STORY}
										Story
									{else}
										Undefined
									{/if}
								</a>
								<span class="list-dotted"></span>
								Last Edited {$story.time_updated}
							</div>
						</div>
						<div class="col-md-6" style="text-align: right;">
							<ul class="list-story-social list-aligned mobile-float-reset">
								<li>
									<i class="fa fa-users"></i>
									<a>{$stats.no_contributors}</a>
								</li>
								<li>
									<i class="fa fa-eye"></i>
									<a>{$stats.no_views}</a>
								</li>
								<li>
									<i class="fa fa-pencil"></i>
									<a>{$stats.no_edits}</a>
								</li>
								<li>
									<i class="fa fa-comment"></i>
									<a>{$stats.no_comments}</a>
								</li>
								<li>
									<i class="fa fa-star"></i>
									<a>{$stats.rating}</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-md-offset-6">
							<div id="social-share" style="text-align: right;"></div>
						</div>
					</div>
					{if $user.role >= ROLE_CONTRIBUTOR}
					<div class="row" style="margin-top: 10px;">
						<div class="col-md-12">
							{if $user.user_id == $story.owner_id}
							<a href="#" data-toggle="modal" class="btn btn-xs btn-info pull-right" data-target="#story-analysis" data-backdrop="static" style="margin: 5px">
								<i class="fa fa-bar-chart" aria-hidden="true"></i> Analysis
							</a>
							<a href="/story/pdf_export/{$story.slug}" target="_blank" style="margin: 5px" class="btn btn-xs btn-info pull-right">PDF</a>
							{/if}
							<a href="/story/edit/{$story.slug}/{$title_part_id}/{$parent_part_id}" style="margin: 5px" class="btn btn-xs btn-info pull-right story-edit-link" data-story={$story.slug}>
								<i class="fa fa-pencil" aria-hidden="true"></i> Edit
							</a>
							{if $user.user_id == $story.owner_id && $story.publish == DEVELOPMENT}
							<a href="/story/publish_story/{$story.slug}" style="margin: 5px" class="btn btn-xs btn-info publish-story pull-right">
								<i class="fa fa-globe" aria-hidden="true"></i> Publish
							</a>
							{elseif $user.user_id == $story.owner_id && $story.publish == PUBLISH}
							<a href="/story/unpublish_story/{$story.slug}" style="margin: 5px" class="btn btn-xs btn-info unpublish-story pull-right">
								<i class="fa fa-globe" aria-hidden="true"></i> Un-Publish
							</a>
							{/if}
						</div>
					</div>
					{/if}
					{if $story.intro_chap}
					<div class="row" style="text-align:center;">
						{if !empty($story.image)}
						<div class="col-sm-12" style="padding: 15px;">
							<img src="/resource/img/story/{$story.image}" class="image-preview" style="border-radius: 0px; cursor: auto;" width="{$story.image_width}" height="{$story.image_height}">
						</div>
						{/if}
					</div>
					{/if}
					<hr>
					<h2 class="text-center">{$story.title}</h2>
					<div class="row">
						<div class="wrapper-holder wrapper-holder-white">
							<div class="row">
								<h2 class="text-muted" align="center">{$story.def_chapter.title}</h2>
							</div>
							<div class="row">
								<div class="col-sm-12 text-muttet-boulder lead margin-lg story-body-container" style="word-wrap: break-word; text-align: justify; padding: 5px 25px;">
									{$story.def_chapter.body}
								</div>
							</div>
						</div>
					</div>
					<p class="line-behind">Leave a comment!</p>
					<div class="row comment-section">
						{if isset($smarty.session.user) || isset($smarty.session.user.user_id)}
						<form action="/story/comment/{$story.slug}" class="form-group-lg" method="POST">
							<div class="col-sm-10">
								<textarea class="form-control input-mutted" style="min-height: 120px" name="comment-message" placeholder="Type your comment here..." required></textarea>
							</div>
							<div class="col-sm-2">
								<p align="center">
									<img src="/resource/img/user/{$user.image}" class="profile-image"  width="60" height="60" style="margin: 5px;">
									<button type="submit" class="btn btn-info">Comment</button>
								</p>
							</div>
						</form>
						{else}
						<div class="col-sm-6 col-sm-offset-3">
							<h4 class="text-muted" align="center">
								<a href="#" data-toggle="modal" data-target="#sign-modal" data-backdrop="static">Login!</a> to leave a comment.
							</h4>
						</div>
						{/if}
					</div>
					{if !empty($comments)}
					<p class="line-behind">
						{($comments|count)} {if $comments|count > 1}Comments{else}Comment{/if}
					</p>
					<div class="row comment-section" style="padding:15px">
						{foreach from=$comments item=comment}
						<div class="col-sm-12 comment-view">
							<div class="row">
								<img src="/resource/img/user/{$comment.user_image}" class="profile-image" width="50">
								<span class="list-dotted"></span>
								by <a href="/user/view/{$comment.user_id}">{$comment.user_name}</a>
								<span class="list-dotted"></span>
								{$comment.time}
							</div>
							<div class="comment-message" id="comment-message-{$comment.id}" style="font-size: 18px;">{$comment.message}</div>
							{if $user.user_id == $comment.user_id}
							<button class="btn btn-xs btn-primary edit-comment pull-right" data-id="{$comment.id}" style="padding: 1px 10px;">Edit</button>
							{/if}
						</div>
						{/foreach}
					</div>
					{/if}
				</div>
				<div class="col-sm-2"></div>
			</div>
		</div>
		{if !empty($story.collab) && $user.role >= ROLE_CONTRIBUTOR}
			<img src="/resource/img/chat-icon.png" data-room="{$story.room_id}" data-message="{$story.last_message_id}" width="60" height="60" id="chat-icon">
			<span style="height: 20px; width: 20px; background: red; bottom: 145px; right: 50px; position: fixed; border-radius: 50%; text-align: center; color: white; display: none;" id="chat-noticifation"></span>
			
			<script type="text/javascript">

				var check_message_timer;
				
				function check_new_messages()
				{
					
					var room_id = $("#chat-icon").attr("data-room");
					
					var last_message_id = $("#chat-icon").attr("data-message");
					
					$.ajax({
					
						url: "/messages/check_new_messages/" + room_id,
					
						data: { "message_id" : last_message_id },
					
						type: "POST",
					
						success: function(data) {
					
							var response = JSON.parse(data);

							if (response.status == "Success") {

								$("#chat-noticifation").html(response.data);
					
								$("#chat-noticifation").show();

							}

						}
					
					});

				}

				check_message_timer = setInterval(check_new_messages, 500);

			</script>
		{/if}
	</section>
	<div class="sticky-left-holder">
		<div class="sticky-item toc">
			<a href="#" class="sticky-item-trigger">
				<i class="fa fa-list"></i>
			</a>
			<h3 class="sticky-item-caption">Table of Contents</h3>
			<div style="border-left: 3px solid #939393">
				{include file='table_of_content.tpl' parts=$story.toc callback_type="view" story_id=$story.id parent_part_id=$story.id}
			</div>
		</div>
		<div class="sticky-item contributors">
			<a href="#" class="sticky-item-trigger">
				<i class="fa fa-group"></i>
			</a>
			<h3 class="sticky-item-caption">Contributors</h3>
			{if !empty($collabs)}
			<ul class="list-contributor">
				{foreach from=$collabs item=member}
				<li>
					<img src="/resource/img/user/{$member.image}" data-id="{$member.id}" class="contributor-pic {$member.class} success">
                    {if !empty($user['user_id'])}<a href="/user/view/{$member.id}">{/if}
						{$member.name}
                    {if !empty($user['user_id'])}</a>{/if}
                    <a href="#" class="show-words" data-id="{$member.id}" data-show="0">
                    	<i class="fa fa-square-o" aria-hidden="true"></i>
                    </a>
				</li>
				<script type="text/javascript">
					$('.show-words[data-id="{$member.id}"]').css('color', $('.list-contributor img[data-id="{$member.id}"]').css('border-color'));
				</script>
				{/foreach}
			</ul>
			{else}
			<p align="center">No active Contributor.</p>
			{/if}
		</div>
	</div>
	{if isset($smarty.session.user) || isset($smarty.session.user.user_id)}
		{include file='story_contribution.tpl'}
	{/if}
	{if !empty($story.collab) && $user.role >= ROLE_CONTRIBUTOR}
		{include file='chat_panel.tpl'}
	{/if}
	{if $story.owner_id == $user.user_id}
		{include file="story_analysis.tpl"}
	{/if}
</main>
<script type="text/javascript">

	$('#social-share').jsSocials({
		
		shares: ["twitter", "facebook", "googleplus"],
		
		shareIn: "popup",

		text: "{$story.title}",

		showLabel: false,
	
	});
	
	$(document).ready(function(){

		$("#chat-icon").on("click", function(){
	
			var room_id = $(this).attr("data-room");
	
			$.ajax({
	
				url: "/messages/chat_story/"+room_id,
	
				success: function(data) {

					var response = JSON.parse(data);
	
					if (response.status == "Failure") {
	
						location.reload();
	
					}

					if (response.status == "Success") {
	
						$(".chat-body").html(response.data);
	
						$(".chat-title").html(response.title);
	
						$(".chat-foot").html(response.form);
	
						$(".chat-script").html(response.script);
	
						$(".chat-panel").addClass("active");
	
						var body_height = $(".chat-body").prop("scrollHeight");
	
						$(".chat-body").scrollTop(body_height);
	
						$("#chat-noticifation").hide();
	
						clearInterval(check_message_timer);
	
						$("#chat-noticifation").html("");
	
						$("#chat-icon").attr("data-message", response.last_message_id);
	
					}
	
				}
	
			});
	
		});

		$('.edit-comment').on('click', function() {

			var comment_id = $(this).attr('data-id');
			
			var comment_message = $('#comment-message-' + comment_id);
			
			comment_message.html('<form action="/story/edit_comment/'+comment_id+'" method="POST"><input name="message" type="text" value="'+comment_message.text()+'" class="form-control"><br><input type="submit" class="btn btn-xs btn-primary pull-right" style="padding: 1px 10px;"></form>');
			
			$(this).hide();
		
		});

		$('.contributor-pic').hover(function() {

			var user_id = $(this).attr("data-id");

			var color_code = $(this).css("border-color");

			$('span[data-user="' + user_id + '"]').css("color", color_code);

		}, function() {

			var user_id = $(this).attr("data-id");

			var show = $(".show-words[data-id='" + user_id + "']").attr("data-show");

			if (show != 1) {

				$('span[data-user="' + user_id + '"]').css("color", "");
				
			}

		});

		$('.show-words').on('click', function(event) {

			var show = $(this).attr("data-show");
			
			var user_id = $(this).attr("data-id");

			var color_code = $(".contributor-pic[data-id='" + user_id + "']").css("border-color");

			if (show == 0) {

				$('span[data-user="' + user_id + '"]').css("color", color_code);

				$(this).attr("data-show", 1);

				$(this).html('<i class="fa fa-check-square-o" aria-hidden="true"></i>');

			} 

			if (show == 1) {

				$('span[data-user="' + user_id + '"]').css("color", "");
				
				$(this).attr("data-show", 0);

				$(this).html('<i class="fa fa-square-o" aria-hidden="true"></i>');

			}

			event.preventDefault();

		});

	});
</script>