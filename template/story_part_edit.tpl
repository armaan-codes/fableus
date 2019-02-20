<style type="text/css">
	.story-image-upload {
		visibility: hidden;
	}

	#upload-story-image {
		margin: 15px auto;
		{if !empty($story.image)}
		height: {$story.image_height};
		width: {$story.image_width};
		{else}
		width: 100%;
		height:450px;
		{/if}
	}

	#story-image {
		cursor: pointer;
	}

	.chat-panel {
		bottom: 60px;
	}

	.btn-group, .btn-group-vertical {
		position: relative;
		display: inline-block;
		vertical-align: middle;
	}

	#sketch-area {

		display: none;
		text-align: center;
		margin-top: 10px;
		margin-bottom: 10px;

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
				<div class="col-md-8 col-md-offset-2 story-container">
					<div class="row">
						<div class="col-md-6">
							<div class="col-xs-3" style="text-align: right;">
								<img src="/resource/img/user/{$story.owner_image}" class="profile-image" height="60" width="60">
							</div>
							<div class="col-xs-9" style="padding:8px 0px;">
								<a style="font-size: 18px; font-weight: 600;" href="/user/view/{$story.owner_id}">{$story.owner_name}</a>
								<br>
								<span class="list-dotted"></span>
								<a href="#">
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
									<a href="#">{$stats.no_contributors}</a>
								</li>
								<li>
									<i class="fa fa-eye"></i>
									<a href="#">{$stats.no_views}</a>
								</li>
								<li>
									<i class="fa fa-pencil"></i>
									<a href="#">{$stats.no_edits}</a>
								</li>
								<li>
									<i class="fa fa-comment"></i>
									<a href="#">{$stats.no_comments}</a>
								</li>
								<li>
									<i class="fa fa-star"></i>
									<a href="#">{$stats.rating}</a>
								</li>
							</ul>
						</div>
					</div>
					{if $user.role >= ROLE_CONTRIBUTOR}
					<div class="row">
						<div class="col-md-12"> 
							{if $user.user_id == $story.owner_id}
								<a href="#" data-toggle="modal" class="btn btn-xs btn-info pull-right" style="margin: 5px" data-target="#story-analysis" data-backdrop="static">
									<i class="fa fa-bar-chart" aria-hidden="true"></i> Analysis
								</a>
								<a href="/story/pdf_export/{$story.slug}" target="_blank" class="btn btn-xs btn-info pull-right" style="margin: 5px">
									<i class="fa fa-book" aria-hidden="true"></i> PDF
								</a>
							{/if}
								<a href="/story/view/{$story.slug}/{$title_part_id}" class="btn btn-xs btn-info pull-right" style="margin: 5px">
									<i class="fa fa-eye" aria-hidden="true"></i> View
								</a>
								<a href="#" class="btn btn-xs btn-info pull-right" style="margin: 5px" data-target="story-drawing" data-backdrop="static">
									<i class="fa fa-file-image-o" aria-hidden="true"></i> Sketch
								</a>
							{if $user.user_id == $story.owner_id && $story.publish == DEVELOPMENT}
								<a href="/story/publish_story/{$story.slug}" class="publish-story btn btn-xs btn-info pull-right" style="margin: 5px">
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
					<div class="row" id="sketch-area">
						<div class="col-xs-12">
							<div id="canvas-editor"></div>
						</div>
						<div class="col-xs-12" style="margin-top: 8em;">
							<div class="col-xs-4 col-xs-offset-8">
								<button class="btn btn-info btn-block" id="download-drawing">Download</button>
							</div>
						</div>
					</div>
					{if $story.intro_chap}
					<form action="/story/upload_image/{$story.id}" id="upload-story-image-form">
						<div class="overlay" id="story-image-spinner">
							<div class="spinner"></div>
						</div>
						<div id="upload-story-image">
							{if !empty($story.image)}
							<div class="col-sm-12" style="padding: 15px;">
								<img src="/resource/img/story/{$story.image}" class="image-preview" id="story-image" style="border-radius: 0px; width:100%;" width="{$story.image_width}" height="{$story.image_height}">
								<input type="file" name="story-image" accept="image/*" class="story-image-upload">
							</div>
							
							<script type="text/javascript">
								$(document).ready(function(){
								
									$("#story-image").resizable({

										containment: "#upload-story-image",

										resize: function( event, ui ) {
										
											$(".upload-action").html('<button class="btn btn-info btn-block text-uppercase" type="submit">update image</button>');
										
										}

									});

								});
							</script>
							
							{else}
							<div class="col-sm-12" style="padding: 15px 0px;">
								<img src="/resource/img/story/sample.jpg" class="image-preview" id="story-image" style="border-radius: 0px; width:100%;">
								<input type="file" name="story-image" accept="image/*" class="story-image-upload">
							</div>
							{/if}
						</div>
						<div class="row">
							<div class="col-sm-4 pull-right upload-action"></div>
						</div>
					</form>
					{/if}
					<hr>
					<h2 class="text-center">{$story.title}</h2>
					<div class="row" style="padding: 10px;">
						<form action="/story/doedit/{$story.slug}" id="edit-story-form" method="post">
							<div class="editcontainer">
								<div class="wrapper-holder wrapper-holder-white">
									<div class="form-group form-group-lg">
										<input type="text" name="title" class="form-control input-sm" placeholder="Title" value="{$story.def_chapter.title}" required>
									</div>
									<div class="form-group form-group-lg">
										<textarea name="body" class="form-control input-sm" id="edit-container" placeholder="Body">{$story.def_chapter.body}</textarea>
									</div>
									<div class="pull-right">
										<button type="submit" class="btn btn-primary">Save</button>
										<a href="/story/view/{$story.slug}">Cancel</a>
									</div>
									<div class="pull-left">
										{if $story.def_chapter.publish}
											<a href="/story/unpublish_chapter/{$story.slug}/{$title_part_id}" class="text-danger chapter-status">Unpublish this chapter</a>
										{else}
											<a href="/story/publish_chapter/{$story.slug}/{$title_part_id}" class="text-info chapter-status">Publish this chapter</a>
										{/if}
									</div>
								</div>
							</div>
							<input type="hidden" name="title_part_id" value="{$title_part_id}">
							<input type="hidden" name="parent_part_id" value="{$parent_part_id}">
							<input type="hidden" name="display_order" value="{$display_order}">	
						</form>
					</div>
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
				{include file='table_of_content.tpl' parts=$story.toc callback_type="edit" story_id=$story.id parent_part_id=$story.id}
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
					<a href="/user/view/{$member.id}">
						<img src="/resource/img/user/{$member.image}" class="contributor-pic">
						{$member.name}
					</a>
				</li>
				{/foreach}
			</ul>
			{else}
			<p align="center">No active contributors.</p>
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

	$(document).ready(function(){

		$('.chapter-status').on('click', function(e) {

			$.ajax({

				url: $(this).attr('href'),
				
				success: function(data) {

					location.reload();

				}

			});

			e.preventDefault();
		});

		$('#edit-container').summernote({

			callbacks: {

				onImageUpload: function(files) {

					var form = new FormData();

					form.append("image", files[0]);

					$.ajax({
						
						url: "/story/image_upload/{$story.slug}",
						
						cache: false,

						contentType: false,

						processData: false,

						data: form,

						type: "post",

						success: function(url) {
						
							var image = $('<img>').attr('src', url);
						
							$('#edit-container').summernote("insertNode", image[0]);
						
						},

					});

				}

			}

		});
	
		var time = 0;
	
		var timer = setInterval(function(){

			time++;

		}, 1000);

		$("#edit-story-form").submit(function(event){

			$.ajax({ type: "POST", url: "/story/time_record/{$story.slug}", data: { time } });

		});

		$("#story-image").on("click", function(){

			$("input[name='story-image']").click();

		});

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

		$('a[data-target="story-drawing"]').on('click', function() {
			
			$('#sketch-area').toggle("slow", function() {
			
				var display = $(this).css('display');

				if(display === "block") {
			
					$('.editable-canvas-image').click();
			
				}

			});
		
		});

		var drawer = new DrawerJs.Drawer(null, {
	        texts: customLocalization,
	        plugins: drawerPlugins,
	        defaultActivePlugin : { name : 'Pencil', mode : 'lastUsed'},
	    }, '100%', 250);
	    
	    $('#canvas-editor').append(drawer.getHtml());
	    
	    drawer.onInsert();

    	$('#download-drawing').on('click', function(e) {

    		$('a[data-target="story-drawing"]').click();

    		setTimeout(function(){
    			var anchor = document.createElement('a');
    			anchor.download = "story-sketch";
    			var img_src = $('.editable-canvas-image').attr('src');
    			anchor.href = img_src;
    			anchor.click();
    
    		}, 1500);

    	});

    	setInterval(function(){
    		var title = $('input[name="title"]').val();
    		var body = $('textarea[name="body"]').val();
    		var title_part_id = $('input[name="title_part_id"]').val();
    		var parent_part_id = $('input[name="parent_part_id"]').val();
    		var display_order = $('input[name="display_order"]').val();

    		if(title_part_id != 0) {

				$.ajax({

					url: "/story/auto_save/{$story.slug}",
					type: "POST",
					data: { 'title' : title, 'body' : body, 'title_part_id' : title_part_id, 'parent_part_id' : parent_part_id, 'display_order' : display_order },

				});

    		}

    	}, {REFRESH_TIME});
		
	});
</script>
<script src="/resource/vendors/drawerJs/drawerJs.standalone.min.js"></script>
<script src="/resource/vendors/drawerJs/drawerLocalization.js"></script>
<script src="/resource/vendors/drawerJs/drawerJsConfig.js"></script>
<script language="JavaScript">
	
	window.onbeforeunload = clearEditor;
	
	function clearEditor()
	{
		$.ajax({

			url: "/story/clear_editor",

			type: "POST",

			data: { 'story_id' : '{$story.slug}' },

		});
	}
</script>