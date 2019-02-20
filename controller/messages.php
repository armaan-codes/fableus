<?php
class MessagesController extends BaseController
{
	protected $models = array('messages');

	function chat(){
		$user = $this->getSessionUser();
		$messages_dlgt = $this->getDelegate("messages");

		if (isset($_POST["member_id"]) && !empty($_POST["member_id"]) && $user["plan"] > MEMBER_BASIC) {
			$member_id = $_POST["member_id"];
			
			if($room_id = $messages_dlgt->chat_room(CHAT_PROJECT, $user["user_id"], $member_id)["room_id"]) {
				$chat = $messages_dlgt->room_chat($user["user_id"], $room_id);
			} else {
				$room_id = $messages_dlgt->create_chat_room($user["user_id"], $member_id, CHAT_PROJECT);
				$chat = $messages_dlgt->room_chat($user["user_id"], $room_id);
			}
			if (!empty($chat["messages"])) {
				$response["status"] = "Success";
				foreach ($chat["messages"] as $message) {
					if ($message["member_id"] == $user["user_id"]) {
						$response["data"] .= '<div class="row">
												<div class="user-area pull-right">
													<img src="/resource/img/user/'.$message["member_image"].'" width="25" height="25" class="profile-image" alt="user-image" title="'.$message["member_name"].'">
													<p align="center">'.$message["time"].'</p>
												</div>
												<div class="message-area-user pull-right">
													'.$message["message"].'
												</div>
											</div>';
					} else {
						$response["data"] .= '<div class="row">
												<div class="user-area pull-left">
													<img src="/resource/img/user/'.$message["member_image"].'" width="25" height="25" class="profile-image" alt="user-image" title="'.$message["member_name"].'">
													<p align="center">'.$message["time"].'</p>
												</div>
												<div class="message-area pull-left">
												'.$message["message"].'
												</div>
											</div>';
					}
					$last_message_id = $message["id"];
				}
			} else {
				$response["status"] = "Success";
				$response["data"] .= '<div class="row"><p class="text-muted" align="center" style="font-size: 15px;">No converstaion.</p></div>';
				$last_message_id = 0;
			}

			$response["form"] = '<form action="/messages/send/'.$room_id.'" id="chat-panel-send-form" method="POST" data-id="'.$last_message_id.'">
            			<input type="text" class="form-control" placeholder="Enter message..." name="message" autocomplete="off" required>
            		</form>';
			$response["title"] = $chat["title"];
            $response["script"] = '<script type="text/javascript">
									$("#chat-panel-send-form").submit(function(event){
										var url = $(this).attr("action");
										var input = $(this).serialize();
										$.ajax({
											url: url,
											data: input,
											type: "POST",
											success: function(data){
												var response = JSON.parse(data);
												if(response.status == "Success") {
													$("input[name=\'message\']").val("");
												}
												if(response.status == "Failure") {
													location.reload();;
												}
											}
										});
										event.preventDefault();
									});
									chat_panel_timer = setInterval(
										function(){
											var room_id = '.$room_id.';
											var last_id = $("#chat-panel-send-form").attr("data-id");
											$.ajax({
												url: "/messages/new_message/"+ room_id,
												data: { "last_id": last_id },
												type: "POST",
												success: function(data){
													var response = JSON.parse(data);
													if(response.status == "Success"){
														if(response.data == true) {
															$.ajax({
																url: "/messages/load_chat/"+ room_id,
																success: function(msg) {
																	var message = JSON.parse(msg);
																	if(message.status == "Success") {
																		$("#chat-panel-send-form").attr("data-id", message.last_message_id);
																		$(".chat-body").html(message.data);
																		var body_height = $(".chat-body").prop("scrollHeight");
																		$(".chat-body").scrollTop(body_height);
																	}

																	if(message.status == "Failure") {
																			location.reload();
																		}
																}
															});
														}

														if(response.data == false) {
															return;
														}
													}

													if(response.status == "Failure") {
														location.reload();
													}
												}
											});
										},
										500
									);
									</script>';
            echo json_encode($response);
            return;
		}

		$response["status"] = "Failure";
		echo json_encode($response);
		return;
	}

	function new_message($room_id) {
		$user = $this->getSessionUser();
		$messages_dlgt = $this->getDelegate("messages");

		if (!$user) {
			$response["status"] = "Failure";
			echo json_encode($response);
			return;
		}

		if (isset($_POST["last_id"])) {
			$last_id = $_POST["last_id"];
			if ($latest_id = $messages_dlgt->latest_message($room_id)["id"]) {
				$response["status"] = "Success";
				if ($latest_id == $last_id) {
					$response["data"] = false;
				} else {
					$response["data"] = true;
				}
				echo json_encode($response);
				return;
			} else {
				$response["status"] = "Success";
				$response["data"] = false;
				echo json_encode($response);
				return;
			}
		}

		$response["status"] = "Failur";
		echo json_encode($response);
		return;
	}

##########################################################################################
	function check_room_messages($room_id) {
		$user = $this->getSessionUser();
		$messages_dlgt = $this->getDelegate("messages");
		
		if (!$user) {
			$response["status"] = "Failure";
			echo json_encode($response);
			return;
		}

		if (isset($_POST["message_id"])) {
			$message_id = $_POST["message_id"];
			$last_message_id = $messages_dlgt->last_message_id($room_id);

			if ($message_id != $last_message_id) {
				$response["status"] = "Success";
				$response["data"] = $messages_dlgt->get_message($last_message_id);
				echo json_encode($response);
				return;
			}
		}

		$response["status"] = "Failure";
		echo json_encode($response);
		return;
	}

	function check_messages(){
		$user = $this->getSessionUser();
		$messages_dlgt = $this->getDelegate("messages");

		if (!$user) {
			$response["status"] = "Failure";
			echo json_encode($response);
			return;
		}

		if (isset($_POST["room_id"], $_POST["message_id"])) {
			$room_id = $_POST["room_id"];
			$message_id = $_POST["message_id"];

			$last_message_id = $messages_dlgt->last_message_id($room_id);

			if ($message_id != $last_message_id) {
				$response["status"] = "Success";
				$response["data"] = $messages_dlgt->get_message($last_message_id);
				echo json_encode($response);
				return;
			}
		}

		$response["status"] = "Failure";
		echo json_encode($response);
		return;
	}

	/*	Revised Code as on 08/06/2018	*/


	function check_new_messages($room_id)
	{
		$user = $this->getSessionUser();
		
		$messages_dlgt = $this->getDelegate("messages");

		if (!$user) {
		
			$response["status"] = "Failure";
		
			echo json_encode($response);
		
			return;
		
		}

		if(isset($_POST["message_id"]) && !empty($_POST["message_id"])) {
		
			$last_message_id = $_POST["message_id"];

			$new_message_count = $messages_dlgt->new_message_count($room_id, $last_message_id)["COUNT(*)"];
			
			if ($new_message_count > 0) {
			
				$response["status"] = "Success";
			
				$response["data"] = $new_message_count;
			
				echo json_encode($response);
			
				return;
			
			}

		}

		$response["status"] = "Failure";

		echo json_encode($response);

		return;

	}

	function chat_story($room_id)
	{

		$user = $this->getSessionUser();
		
		$messages_dlgt = $this->getDelegate("messages");

		if ($user && $chat = $messages_dlgt->room_chat($user["user_id"], $room_id)) {
		
			$response["status"] = "Success";
			
			if (!empty($chat["messages"])) {
				
				foreach ($chat["messages"] as $message) {
					
					if ($message["member_id"] == $user["user_id"]) {
						
						$response["data"] .= '<div class="row">
												<div class="user-area pull-right">
													<img src="/resource/img/user/'.$message["member_image"].'" width="25" height="25" class="profile-image" alt="user-image" title="'.$message["member_name"].'">
													<p align="center">'.$message["time"].'</p>
												</div>
												<div class="message-area-user pull-right">
													'.$message["message"].'
												</div>
											</div>';

					} else {

						$response["data"] .= '<div class="row">
												<div class="user-area pull-left">
													<img src="/resource/img/user/'.$message["member_image"].'" width="25" height="25" class="profile-image" alt="user-image" title="'.$message["member_name"].'">
													<p align="center">'.$message["time"].'</p>
												</div>
												<div class="message-area pull-left">
												'.$message["message"].'
												</div>
											</div>';
					
					}
					
					$last_message_id = $message["id"];
				
				}
			
			} else {
				
				$response["data"] .= '<div class="row"><p class="text-muted" align="center" style="font-size: 15px;">No converstaion.</p></div>';
				
				$last_message_id = 0;
			
			}

			$response["last_message_id"] = $last_message_id;

			$response["form"] = '<form action="/messages/send/'.$room_id.'" id="chat-panel-send-form" method="POST" data-id="'.$last_message_id.'">
						<input type="text" class="form-control" placeholder="Enter message..." name="message" autocomplete="off" required>
            		</form>';
			
			$response["title"] = $chat["title"];
            
            $response["script"] = '<script type="text/javascript">
									$("#chat-panel-send-form").submit(function(event){
										var url = $(this).attr("action");
										var input = $(this).serialize();
										$.ajax({
											url: url,
											data: input,
											type: "POST",
											success: function(data){
												var response = JSON.parse(data);
												if(response.status == "Success") {
													$("input[name=\'message\']").val("");
												}
												if(response.status == "Failure") {
													location.reload();;
												}
											}
										});
										event.preventDefault();
									});
									chat_panel_timer = setInterval(
										function(){
											var room_id = '.$room_id.';
											var last_id = $("#chat-panel-send-form").attr("data-id");
											$.ajax({
												url: "/messages/check_new_messages/"+ room_id,
												data: { "message_id": last_id },
												type: "POST",
												success: function(data){
													var response = JSON.parse(data);
													if(response.status == "Success"){
														$.ajax({
															url: "/messages/load_chat/"+ room_id,
															success: function(msg) {
																var message = JSON.parse(msg);
																
																if(message.status == "Success") {
																	$("#chat-panel-send-form").attr("data-id", message.last_id);
																	$(".chat-body").html(message.data);
																	var body_height = $(".chat-body").prop("scrollHeight");
																	$(".chat-body").scrollTop(body_height);
																	$("#chat-icon").attr("data-message", message.last_id);
																}

																if(message.status == "Failure") {
																	location.reload();
																}
															}
														});
													}
												}
											});
										},
										500
									);
									</script>';
			echo json_encode($response);
			return;
		}

		$response["status"] = "Failure";
		echo json_encode($response);
		return;
	}

	function send($room_id)
	{

		$user = $this->getSessionUser();
		
		$messages_dlgt = $this->getDelegate("messages");

		if (!$user) {
		
			$response["status"] = "Failure";
		
			echo json_encode($response);
		
			return;
		
		}

		if (isset($_POST["message"]) && !empty($_POST["message"])) {
		
			$message = trim($_POST["message"]);

			if($messages_dlgt->room_member($user["user_id"], $room_id)) {
		
				if($messages_dlgt->send_message($user["user_id"], $room_id, $message)){
		
					$response["status"] = "Success";
		
					echo json_encode($response);
		
					return;
		
				}
		
			}
		
		}

		$response["status"] = "Failure";
		
		echo json_encode($response);
		
		return;
	
	}

	function load_chat($room_id)
	{

		$user = $this->getSessionUser();
		
		$messages_dlgt = $this->getDelegate("messages");

		if (!$user) {
		
			$response["status"] = "Failure";
		
			echo json_encode($response);
		
			return;
		
		}

		if ($chat = $messages_dlgt->room_chat($user["user_id"], $room_id)) {
			
			$response["status"] = "Success";

			if (!empty($chat["messages"])) {
		
				foreach ($chat["messages"] as $message) {
		
					if ($message["member_id"] == $user["user_id"]) {
		
						$response["data"] .= '<div class="row">
												<div class="user-area pull-right">
													<img src="/resource/img/user/'.$message["member_image"].'" width="25" height="25" class="profile-image" alt="user-image" title="'.$message["member_name"].'">
													<p align="center">'.$message["time"].'</p>
												</div>
												<div class="message-area-user pull-right">
													'.$message["message"].'
												</div>
											</div>';
		
					} else {
		
						$response["data"] .= '<div class="row">
												<div class="user-area pull-left">
													<img src="/resource/img/user/'.$message["member_image"].'" width="25" height="25" class="profile-image" alt="user-image" title="'.$message["member_name"].'">
													<p align="center">'.$message["time"].'</p>
												</div>
												<div class="message-area pull-left">
												'.$message["message"].'
												</div>
											</div>';
		
					}

					$last_message_id = $message["id"];
				}

			} else {

				$response["data"] .= '<div class="row"><p class="text-muted" align="center" style="font-size: 15px;">No converstaion.</p></div>';
				
				$last_message_id = 0;
			
			}

			$response["last_id"] = $last_message_id;
			
			echo json_encode($response);
			
			return;
		
		}

		$response["status"] = "Failure";
		
		echo json_encode($response);
		
		return;
	
	}

	function index($args = array())
	{

		$user = $this->getSessionUser();
		
		$messages_dlgt = $this->getDelegate("messages");

		if (!$user)
			$this->redirect("/");

		$chats = $messages_dlgt->user_chat_rooms($user["user_id"]);

		$this->tpl->assign("user", $user);
		
		$this->tpl->assign("chats", $chats);
		
		$this->tpl->assign('tab', array('messages' => true));
		
		$this->render("messages");
	
	}

	function load($room_id)
	{

		$user = $this->getSessionUser();
		
		$messages_dlgt = $this->getDelegate("messages");

		if ($user && $chat = $messages_dlgt->room_chat($user["user_id"], $room_id)) {
			
			$response["status"] = "Success";
		
			$response["title"] = '<div class="col-sm-10">'.$chat["title"].'</div>';

			if (!empty($chat["messages"])) {
			
				foreach ($chat["messages"] as $message) {
		
					if ($message["member_id"] == $user["user_id"]) {
		
						$response["data"] .= '<div class="row">
												<div class="user-area pull-right">
													<img src="/resource/img/user/'.$message["member_image"].'" width="35" height="35" class="profile-image" alt="user-image" title="'.$message["member_name"].'">
													<p align="center">'.$message["time"].'</p>
												</div>
												<div class="message-area-user pull-right">
													'.$message["message"].'
												</div>
											</div>';
		
					} else {
		
						$response["data"] .= '<div class="row">
												<div class="user-area pull-left">
													<img src="/resource/img/user/'.$message["member_image"].'" width="35" height="35" class="profile-image" alt="user-image" title="'.$message["member_name"].'">
													<p align="center">'.$message["time"].'</p>
												</div>
												<div class="message-area pull-left">
													'.$message["message"].'
												</div>
											</div>';
		
					}
		
					$last_message_id = $message["id"];
		
				}
		
			} else {
				
				$response["data"] .= '<div class="row"><p class="text-muted" align="center" style="font-size: 15px;">No converstaion.</p></div>';
				
				$last_message_id = 0;
			
			}

			$response["form"] = '<form action="/messages/send/'.$room_id.'" id="chat-send-form" method="POST" data-id="'.$last_message_id.'">
									<div class="col-sm-10">
										<input type="text" class="form-control" placeholder="Enter message..." name="message" autocomplete="off" required>
									</div>
									<div class="col-sm-2">
										<button type="submit" class="btn btn-info btn-block text-uppercase">Send</button>
									</div>
								</form>';
            
            $response["script"] = '<script type="text/javascript">
									$("#chat-send-form").submit(function(event){
										var url = $(this).attr("action");
										var input = $(this).serialize();
										$.ajax({
											url: url,
											data: input,
											type: "POST",
											success: function(data){
												var response = JSON.parse(data);
												if(response.status == "Success") {
													$("input[name=\'message\']").val("");
												}
												if(response.status == "Failure") {
													location.reload();;
												}
											}
										});
										event.preventDefault();
									});
									chat_timer = setInterval(
										function(){
											var room_id = '.$room_id.';
											var last_id = $("#chat-send-form").attr("data-id");
											$.ajax({
												url: "/messages/new_message/"+ room_id,
												data: { "last_id": last_id },
												type: "POST",
												success: function(data){
													var response = JSON.parse(data);
													if(response.status == "Success"){
														if(response.data == true) {
															$.ajax({
																url: "/messages/load_chat/"+ room_id,
																success: function(msg) {
																	var message = JSON.parse(msg);
																	if(message.status == "Success") {
																		$("#chat-send-form").attr("data-id", message.last_id);
																		$(".message-body").html(message.data);
																		var body_height = $(".message-body").prop("scrollHeight");
																		$(".message-body").scrollTop(body_height);
																	}

																	if(message.status == "Failure") {
																			location.reload();
																		}
																}
															});
														}

														if(response.data == false) {
															return;
														}
													}

													if(response.status == "Failure") {
														location.reload();
													}
												}
											});
										},
										500
									);
									</script>';
       
            echo json_encode($response);
       
            return;
		
		}

		$response["status"] = "Failure";
		
		echo json_encode($response);
		
		return;
	
	}

}