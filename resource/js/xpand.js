function filePreview(input) {

	if (input.files && input.files[0]) {
	
		var reader = new FileReader();
	
		reader.onload = function (e) {
	
			$('.image-preview').attr("src", e.target.result);
	
		}
	
		reader.readAsDataURL(input.files[0]);
	
	}

}

$(document).ready(function() {

	tour.init();

	$(".btn-plan").on("click", function(){
		$("#upgrade-spinner").show();
		var plan = $(this).html();
		$.ajax({
			url: "/user/upgrade_plan/",
			type: "POST",
			data: { "plan" : plan },
			success: function(data) {
				$("#upgrade-spinner").hide();
				var response = JSON.parse(data);
				if (response.status == "Success") {
					window.location = response.data;
				}
				if (response.status == "Null") {
					$("#upgrade-error").html(response.data);
				}
				if (response.status == "Failure") {
					location.reload();
				}
			}
		});
	});

	$("#pay-pending-btn").on("click", function(){
		$("#activate-error").html("");
		$("#activate-spinner").show();
		$.ajax({
			url: "/user/pay_pending",
			success: function(data) {
				var response = JSON.parse(data);
				$("#activate-spinner").hide();
				if (response.status == "Success") {
					window.location = response.data;
				}
				if (response.status == "Failure") {
					$("#activate-error").html(response.data);
				}
			}
		})
	});

	$("#delete-pending-btn").on("click", function() {
		$("#activate-error").html("");
		$("#activate-spinner").show();
		$.ajax({
			url: "/user/delete_pending",
			success: function(data) {
				var response = JSON.parse(data);
				$("#activate-spinner").hide();
				if (response.status == "Success") {
					window.location = response.data;
				}
				if (response.status == "Failure") {
					$("#activate-error").html(response.data);
				}
			}
		})
	});


	$(".icons-container").fadeIn("slow");
	
	$(".members-container").fadeIn("slow");


	$(".members-icon").hover(function(){
		$(this).children(".member-action").fadeIn();
	}, function(){
		$(this).children(".member-action").fadeOut();
	});

	$("#edit-project-form").submit(function(event) {
		$(".edit-message").html("");
		var range_from = parseInt($("input[name='edit-range-from']").val());
		var range_to = parseInt($("input[name='edit-range-to']").val());

		if (range_from < range_to) {
			return;
		}

		var error_message = "";

		if (range_from >= range_to) {
			error_message += '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Range From</strong> cannot be equal to OR greater than <strong>Range To</strong>.</div>';
		}

		if (error_message != "") {
			$(".edit-message").html(error_message);
		}
		event.preventDefault();
	});

	$("#place-bid-form").submit(function(event) {
		$(".bidding-message").hide();
		var input = $(this).serialize();
		var url = $(this).attr("action");
		$.ajax({
			url: url,
			type: "POST",
			data: input,
			success: function(data) {
				var response = JSON.parse(data);
				if (response.status == "Success") {
					$(".bidding-message").html(response.data);
					$(".bidding-message").show(function(){
						$(".bidding-container").hide();
						$(".project-bid-area").remove();
					});
				}

				if (response.status == "Failure") {
					location.reload();
				}
			}
		});
		event.preventDefault();
	});

	$("#update-proposal-form").submit(function(event) {
		$(".proposal-message").hide();
		var input = $(this).serialize();
		var url = $(this).attr("action");
		$.ajax({
			url: url,
			type: "POST",
			data: input,
			success: function(data) {
				var response = JSON.parse(data);
				if (response.status == "Success") {
					$(".proposal-message").html(response.data);
					$(".proposal-message").show(function(){
						$(".proposal-container").hide();	
					});
				}

				if (response.status == "Failure") {
					location.reload();
				}
			}
		});
		event.preventDefault();
	});

	$("#search-project-form").submit(function(event){
		var input = $(this).serialize();
		var url = $(this).attr("action");
		$.ajax({
			type: "POST",
			url: url,
			data: input,
			success: function(data) {
				var response = JSON.parse(data);
				if (response.status == "Success") {
					$(".search-result").html(response.data);
					$(".project-area").fadeOut(function(){
						$(".search-result").show();
					});
				}

				if (response.status == "Failure") {
					location.reload();
				}
			}
		});
		event.preventDefault();
	});

	$("#search-input").on("keyup", function(){
		var input = $(this).val();
		if (input == "") {
			$(".search-result").fadeOut(function(){
				$(".project-area").fadeIn();
			});
		}
	});

	$("#new-project-form").submit(function(event) {
		var title = $("input[name='project-title']").val();
		var from = parseFloat($("input[name='range-from']").val());
		var to = parseFloat($("input[name='range-to']").val());

		if (title != "" && to > from) {
			return;
		}

		var error_message = "";

		if (title == "") {
			error_message += '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Project Title</strong> cannot be empty.</div>';
		}

		if (from == 0) {
			error_message += '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Range From</strong> cannot be <strong>Zero</strong>.</div>';
		}

		if (to <= from) {
			error_message += '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Range To</strong> cannot be equal to OR less than <strong>Range From</strong>.</div>';
		}

		if (error_message != "") {
			$("#new-project-message").html(error_message);
		}
		event.preventDefault();
	});

	$("#search-author-form").submit(function(event){
		var url = $(this).attr("action");
		var input = $(this).serialize();
		
		$.ajax({
			url: url,
			type: "POST",
			data: input,
			success: function(data) {

				var response = JSON.parse(data);
				if (response.status == "Success") {
					$(".other-author").html(response.data);
				}

				if (response.status == "Failure") {
					location.reload();
				}
			}
		});
		event.preventDefault();
	});

	$('input[name="author"]').on("keyup", function(){
		var value = $(this).val();
		if(value == "") {
			$(".other-author").html("");
		}
	});

	$(".btn-load-project").on("click", function(event){
		var btn = $(this);
		var url = $(this).attr("href");
		var last_id = $(this).attr("data-id");
		var output_div = $(this).attr("data-for");

		$.ajax({
			url: url,
			type: "POST",
			data: { "last_id" : last_id },
			success: function(data) {
				var response = JSON.parse(data);
				
				if (response.status == "Failure") {
					location.reload();
				}

				if (response.status == "Success") {
					$("#"+output_div).append(response.data);
					btn.attr("data-id", response.last_id);
				}

				if (response.status == "Null") {
					btn.html("No more projects");
					btn.attr("disabled","");
				}
			}
		});
		event.preventDefault();
	});

	/* Revised Code as on 07-06-2018 */

	$(".btn-load-more").on("click", function(event) {
		var btn = $(this);
		var last_id = $(this).attr("data-id");
		var url = $(this).attr("href");
		var filter = $(this).attr("data-filter");
		
		$.ajax({
			url:url,
			type:"POST",
			data: { "last_id" : last_id, "filter" : filter },

			success: function(data){
				
				var response = JSON.parse(data);
				
				if (response.status == "Failure") {
				
					location.reload();
				
				}

				if (response.status == "Success") {
				
					$(".card-story-container").append(response.data);
				
					btn.attr("data-id", response.last_story_id);
				
				}

				if (response.status == "Null") {
				
					btn.html(response.data);
				
					btn.attr("disabled", "");
				
					btn.removeClass("btn-load-more");
				
				}

				if (response.status == "Null-Collab") {
				
					btn.html(response.data);
				
					btn.attr("disabled", "");
				
					btn.removeClass("btn-load-more");
				
				}

				if (response.status == "New") {
				
					btn.removeClass("btn-load-more");
					
					btn.html(response.data);
				
					btn.attr("data-toggle", "modal");
					
					btn.attr("data-target", "#story-title");

					btn.attr("href", "#");
					
				}
			}
		});

		event.preventDefault();
	});

	$("#signup-form").submit(function(event){

		var url = $(this).attr("action");
		var input = $(this).serialize();

		$.ajax({
			type: "POST",
			url: url,
			data: input,
			success: function(data) {

				var response = JSON.parse(data);
				
				if (response.status == "Success") {

					$("#rg-error").html(response.data);
					
					$("#rg-error").fadeIn();
				
				}
				
				if (response.status == "Failure") {
				
					$("#rg-error").html(response.data);
				
					$("#rg-error").fadeIn();
				
				}
			}
		});

		event.preventDefault();
	});

	$("#forgot_button").on("click", function(e) {
		
		$("#signin").fadeOut(function(){

			$("#forgot_password").fadeIn();

		});

		e.preventDefault();
	});

	$("#back_to_signin").on("click", function(e) {
		
		$("#forgot_password").fadeOut(function(){

			$("#signin").fadeIn();

		});

		e.preventDefault();
	});

	$("#signin-form").submit(function(event) {

		var url = $(this).attr("action");
		
		var input = $(this).serialize();

		$.ajax({
			type: "POST",
			url: url,
			data: input,
			success: function(data) {

				var response = JSON.parse(data);
				
				if (response.status == "Success") {
				
					window.location = response.data;
				
				}
				
				if (response.status == "Failure") {
				
					$("#login-error").html(response.data);
				
					$("#login-error").fadeIn();
				
				}
			
			}
		
		});
		
		event.preventDefault();
	});

	$("#forgot-form").submit(function(event) {

		var url = $(this).attr("action");
		
		var input = $(this).serialize();

		$.ajax({
			type: "POST",
			url: url,
			data: input,
			success: function(data) {

				var response = JSON.parse(data);

				if (response.status == "Success") {

					$("#forgot-error").html(response.data);

					$("#forgot-error").fadeIn();

				}

				if (response.status == "Failure") {
			
					$("#forgot-error").html(response.data);

					$("#forgot-error").fadeIn();

				}

			}

		});

		event.preventDefault();
	});

	$("#new-pass-form").submit(function(event) {

		var url = $(this).attr("action");

		var input = $(this).serialize();

		$.ajax({
			type: "POST",
			url: url,
			data: input,
			success: function(data) {

				var response = JSON.parse(data);

				if (response.status == "Success") {

					$("#reset-error").html(response.data);

					$("#reset-error").fadeIn();

				}

				if (response.status == "Failure") {

					$("#reset-error").html(response.data);

					$("#reset-error").fadeIn();

				}

			}

		});

		event.preventDefault();
	});

	$("#invite-collab-email").submit(function(event){

		$("#invite-collab-email-message").html("");
		
		var url = $(this).attr("action");
		
		var input = $(this).serialize();
		
		$.ajax({
		
			type: "POST",
		
			url: url,
		
			data: input,
		
			success: function(data){
		
				var response = JSON.parse(data);
		
				if (response.status == "Failure") {
		
					$("#invite-collab-email-message").html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> ' + response.data + '.</div>');
		
				}

				if (response.status == "Success") {
		
					$("#invite-collab-email-message").html('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> ' + response.data + '.</div>');
					setTimeout(function(){
		
						location.reload();
		
					}, 5000); 
		
				}
		
			}
		
		});
		
		event.preventDefault();
	});

	$("#invite-collab-team").submit(function(event) {

		$("invite-collab-team-message").html("");
		
		var url = $(this).attr("action");
		
		var input = $(this).serialize();

		$.ajax({
		
			type: "POST",
		
			url: url,
		
			data: input,
		
			success: function(data) {
		
				var response = JSON.parse(data);
		
				if (response.status == "Failure") {
		
					$("#invite-collab-team-message").html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> ' + response.data + '.</div>');
		
				}

				if (response.status == "Success") {
		
					$("#invite-collab-team-message").html('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> ' + response.data + '.</div>');
		
					setTimeout(function(){
		
						location.reload();
		
					}, 3000); 
		
				}
		
			}
		
		});
		
		event.preventDefault();
	
	});

	$("#invite-collab-read-email").submit(function(event){

		$("#invite-collab-read-email-message").html("");
		
		var url = $(this).attr("action");
		
		var input = $(this).serialize();
		
		$.ajax({
		
			type: "POST",
		
			url: url,
		
			data: input,
		
			success: function(data){

				console.log(data);
		
				var response = JSON.parse(data);
		
				if (response.status == "Failure") {
		
					$("#invite-collab-read-email-message").html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> ' + response.data + '.</div>');
		
				}

				if (response.status == "Success") {
		
					$("#invite-collab-read-email-message").html('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> ' + response.data + '.</div>');
					setTimeout(function(){
		
						location.reload();
		
					}, 5000); 
		
				}
		
			}
		
		});
		
		event.preventDefault();
	});

	$("#upvote").on("click", function(event) {
		
		var url = $(this).attr("href");
		
		if (url != "#") {
		
			$.ajax({
		
				url: url,
		
				success: function(data) {
		
					var response = JSON.parse(data);
		
					if (response.status == "Success") {
		
						$("#upvote").attr("href", "#");
		
						$("#upvote").html(response.data);
		
					}

					if (response.status == "Failure") {
		
						window.location = response.data;
		
					}
		
				}
		
			});
		
		}
		
		event.preventDefault();

	});

	$(".publish-story").on("click", function(event){
		
		var url = $(this).attr("href");
		
		$.ajax({
		
			url: url,
		
			success: function(data) {
		
				var response = JSON.parse(data);
		
				if (response.status == "Failure") {
		
					$(".story-message").html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> ' + response.data + '.</div>');
		
				}

				if (response.status == "Success") {
		
					$(".story-message").html('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> ' + response.data + '.</div>');
		
					setTimeout(function(){
		
						location.reload();
		
					}, 3000);
		
				}
		
			}
		
		});
	
		event.preventDefault();
	
	});

	$(".unpublish-story").on("click", function(event){
		
		var url = $(this).attr("href");

		if(confirm('Are you sure you want to unblish story?')) {
			$.ajax({
		
				url: url,
			
				success: function(data) {
			
					var response = JSON.parse(data);
			
					if (response.status == "Failure") {
			
						$(".story-message").html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> ' + response.data + '.</div>');
			
					}

					if (response.status == "Success") {
			
						$(".story-message").html('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> ' + response.data + '.</div>');
			
						setTimeout(function(){
			
							location.reload();
			
						}, 3000);
			
					}
			
				}
			
			});
		}
	
		event.preventDefault();
	
	});

	$("input[name='story-image']").on("change", function(){
		
		filePreview(this);
		
		$(".upload-action").html('<button class="btn btn-info btn-block text-uppercase" type="submit">add image</button>');
		
		$("#story-image").resizable({

			containment: "#upload-story-image"	

		});
	
	});

	$("#upload-story-image-form").submit(function(event){
		
		$("#story-image-spinner").show();
		
		var url = $(this).attr("action");
		
		var width = $("#story-image").css("width");
		
		var height = $("#story-image").css("height");
		
		var input = $("input[name='story-image']")[0].files[0];
		
		var image_form = new FormData();
		
		image_form.append("image", input);
		
		image_form.append("width", width);
		
		image_form.append("height", height);
			
		$.ajax({
		
			url: url,
		
			data: image_form,
		
			type: "POST",
		
			contentType: false,
		
			processData: false,
		
			success: function(data) {
		
				var response = JSON.parse(data);
		
				if (response.status == "Success") {
		
					$("#story-image-spinner").hide();
		
				}

				if (response.status == "Failure") {
				
					location.reload();
				
				}
			
			}
		
		});
		
		event.preventDefault();
	
	});

	$(".delete-story").on("click", function() {
		
		var story_id = $(this).attr("data-story-id");
		
		if(confirm("Are you sure to delete this story?")) {
		
			$.ajax({
		
				url: "/story/delete/" + story_id,
		
				success: function(data) {
		
					location.reload();
		
				}
		
			});
		
		}
	
	});

	$("#create-story").submit(function(event) {
		
		$("#story-error").html("");
		
		var input = $(this).serialize();
		
		var url = $(this).attr("action");
		
		$.ajax({
		
			type: "POST",
		
			url: url,
		
			data: input,
		
			success: function(data) {
		
				var response = JSON.parse(data);
		
				if (response.status == "Success") {
		
					window.location = response.data;
		
				}
		
				if (response.status == "Failure") {
		
					location.reload();
		
				}
		
			}
		
		});
		
		event.preventDefault();
	
	});

	$(".story-apply-request").on("click", function() {

		var story_id = $(this).closest(".card-story").attr("data-id");
		
		$('.apply-container').load("/stories/get_apply_request/" + story_id, function(){
		
			$("#apply-request").modal({show:true});
		
		});
	
	});

	$("#apply_contribute").on("click", function(event) {
		
		var url = $(this).attr("href");
		
		$.ajax({
		
			url: url,
		
			success: function(data) {
		
				var response = JSON.parse(data);
		
				if (response.status == "Success") {
		
					$("#collaboration-message").html(response.data);
		
					setTimeout(function(){
		
						location.reload();
		
					}, 3000); 
		
				}

				if (response.status == "Failure") {
		
					$("#collaboration-message").html(response.data);
		
				}
		
			}
		
		});
		
		event.preventDefault();
	
	});

	$("input[name='team_img']").on("change", function(){
	
		filePreview(this);
	
	});

	$("#add-member-form").submit(function(event) {

		$(".team-view-message").hide();
		
		$(".team-view-message").html("");

		var input = $(this).serialize();
		
		var url = $(this).attr("action");
		
		$.ajax({
		
			type: "POST",
		
			url: url,
		
			data: input,
		
			success: function(data){
				
		
				var response = JSON.parse(data);
		
				if (response.status == "Success") {
		
					window.location = response.data;
		
				}
		
				if (response.status == "Failure") {
		
					$(".team-view-message").html(response.data);
		
					$(".team-view-message").fadeIn();
		
				}
		
			}
		
		});
		
		event.preventDefault();
	
	});

	$(".member-delete").on("click", function(event) {
		
		$(".team-view-message").hide();
		
		$(".team-view-message").html("");

		var href = $(this).attr("href");
		
		var name = $(this).closest(".member-action").prev().children("p").html();

		$(".team-view-message").html('<div class="alert alert-info alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><div class="row"><h4 align="center" class="text-muted"><strong>Are you Sure ?</strong></h4><p align="center">You want to delete '+ name +' ?</p></div><div class="row" style="margin: 10px auto;"><div class="col-sm-4 col-sm-offset-4"><a href="'+ href +'" class="btn btn-info btn-block text-uppercase">Delete</a></div></div></div>');
		
		$(".team-view-message").fadeIn();
		
		event.preventDefault();
	
	});

	$(".team-delete").on("click", function(event) {
		
		$(".team-view-message").hide();
		
		$(".team-view-message").html("");

		var href = $(this).attr("href");

		$(".team-view-message").html('<div class="alert alert-info alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><div class="row"><h4 align="center" class="text-muted"><strong>Are you Sure ?</strong></h4><p align="center">You want to delete your team ?</p></div><div class="row" style="margin: 10px auto;"><div class="col-sm-4 col-sm-offset-4"><a href="'+ href +'" class="btn btn-info btn-block text-uppercase">Delete</a></div></div></div>');
		
		$(".team-view-message").fadeIn();
		
		event.preventDefault();
	
	});

	$("input[name='user-image']").on("change", function() {

		filePreview(this);
		
		$(".upload-user-image").fadeIn();
	
	});

	$(".profile-image").on("click", function() {

		$("input[name='user-image']").click();

	});

	$("#password-change").submit(function(event) {

		var input = $(this).serialize();
		
		var url = $(this).attr("action");
		
		$.ajax({
		
			url: url,
		
			data: input,
		
			type: "POST",
		
			success: function(data){
		
				var response = JSON.parse(data);
		
				if (response.status == "Failure") {
		
					$(".password-message").html(response.data);
		
				}

		
				if (response.status == "Success") {
		
					$(".password-message").html(response.data);
		
					setTimeout(function(){
		
						location.reload();
		
					}, 2500);
		
				}
		
			}
		
		});
		
		event.preventDefault();
	
	});

	$("#user-info-form").submit(function(event) {

		var input = $(this).serialize();
		
		var url = $(this).attr("action");
		
		$.ajax({
		
			url: url,
		
			data: input,
		
			type: "POST",
		
			success: function(data){

				var response = JSON.parse(data);
		
				if (response.status == "Failure") {
		
					$(".user-info-message").html(response.data);
		
				}

		
				if (response.status == "Success") {
		
					$(".user-info-message").html(response.data);
		
					setTimeout(function(){
		
						location.reload();
		
					}, 2500);
		
				}
		
			}
		
		});
		
		event.preventDefault();
	
	});
	
	$('#contact-form').submit(function(event) {
	    
	    var input = $(this).serialize();
		
		var url = $(this).attr("action");
		
		$.ajax({
		
			url: url,
		
			data: input,
		
			type: "POST",
		
			success: function(data){
                
				var response = JSON.parse(data);
		
				$("#contact-error").html(response.data);
		
			}
		
		});
		
		event.preventDefault();
	});

	$("#invite").submit(function(event) {

		var input = $(this).serialize();
		
		var url = $(this).attr("action");

		$.ajax({
		
			url: url,
		
			data: input,
		
			type: "POST",
		
			success: function(data){
                
                var response = JSON.parse(data);
		
				$("#invite-email-message").html(response.data);
		
			}


		});

		event.preventDefault();
	
	});

	$('#member-follow').on('click', function() {
		$.ajax({
			
			url: "/user/follow",
			
			type: "POST",
			
			data: { 'member_id' : $(this).attr('data-member-id') },
			
			success: function(data) {
			
				var response = JSON.parse(data);

				location.reload();
			
			}

		});

	});

	$('#member-unfollow').on('click', function() {
		$.ajax({
			
			url: "/user/unfollow",
			
			type: "POST",
			
			data: { 'member_id' : $(this).attr('data-member-id') },
			
			success: function(data) {
			
				var response = JSON.parse(data);

				location.reload();

			}

		});

	});

	$('.story-rating .fa').on('click', function() {
		
		var rating = $(this).attr('data-rate');

		var story_id = $(this).attr('data-story-id')

		$.ajax({
			url: '/story/rating/' + story_id,

			data: { 'rating' : rating },
			
			type: 'POST',
			
			success: function(data) {
			
				var response = JSON.parse(data);

				if(response.status == "Success") {

					$('.story-rating').html(response.data);

				}

				if(response.status == "Failure") {

					location.reload();

				}
			
			}
		});
	});

	$('#start-tour').on('click', function(event) {

		tour.start();

		event.preventDefault();

	});

	$('.story-edit-link').on('click', function(e) {

		var story = $(this).data('story');

		var link = $(this).attr('href');

		$.ajax({

			url: "/story/check_editor",

			type: "POST",

			data: { 'story_id' : story },

			success: function(data) {

				var response = JSON.parse(data);

				if(response.status == "Success") {

					window.location = link;

				} else {
					
					alert(response.data);

				}

			}

		});

		e.preventDefault();

	});
});