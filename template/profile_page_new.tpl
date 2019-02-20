<style type="text/css">
	
	.line-behind:before, .line-behind:after {
	
		width: 50px !important;
	
	}

	.user-image input.upload {
	
		display: none;
	
	}

	.upload-user-image {
	
		margin: 5px;
	
		display: none;
	
	}

	.profile-image {

		box-shadow: none;

		border: 3px solid white;

		margin-bottom: 25px;

	}

	.profile-name-edit {
	
		display: none;
	
	}

	#edit-name {
	
		cursor: pointer;
	
	}

	#done-name {
	
		cursor: pointer;
	
	}
</style>
<main>
	<section>
		<div class="container-fluid">
			<div class="row" style="background-image: url('/resource/img/user-profile-bg-1.jpg'); background-size: cover; padding: 50px 20px;">
				<div class="col-sm-3 user-image" style="text-align: center;">
					<img src="/resource/img/user/{$user.image}" width="100" height="100" class="profile-image image-preview">
					<form action="/user/image_upload" method="POST" enctype="multipart/form-data">
						<input type="file" name="user-image" accept="image/*" class="upload">
						<div class="col-sm-10 col-sm-offset-1">
							<button class="btn btn-default upload-user-image" type="submit">Save</button>
						</div>
					</form>
					<div class="row">
						<div class="col-sm-10 col-sm-offset-1">
							<a class="btn btn-default" href="#" data-toggle="modal" data-target="#user-followers" data-backdrop="static">
								<i class="fa fa-users" aria-hidden="true"></i> Followers
							</a>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-sm-10 col-sm-offset-1">
							<div class="panel panel-default">
								<div class="panel-body">
									<h4 class="text-muted" style="margin-bottom: 0px;">
										{if empty($user.first_name) && empty($user.last_name)}
											{$user.name}
										{/if}

										{if !empty($user.first_name) || !empty($user.last_name)}
											{$user.first_name} {$user.last_name}
										{/if}
									</h4>
									<hr style="margin-top: 10px; margin-bottom: 10px;">
									<p align="left"><strong>Email:</strong> {$user.email}</p>
								</div>
							</div>
							<div style="text-align: center;">
								<div class="col-sm-6" style="padding: 0px; margin: 5px auto;">
									<a class="text-muted btn btn-block btn-default" data-toggle="modal" data-target="#edit-profile-modal" data-backdrop="static" style="cursor: pointer;">
										<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Profile
									</a>
								</div>
								<div class="col-sm-6" style="padding: 0px; margin: 5px auto;">
									<a class="text-muted btn btn-block btn-default" data-toggle="modal" data-target="#change-password-modal" data-backdrop="static" style="cursor: pointer;">
										<i class="fa fa-key" aria-hidden="true"></i> Password
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-7 col-sm-offset-1">
					<div class="row">
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title" style="color: #777;">Nickname</h3>
								</div>
								<div class="panel-body">
									{$user.name}
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title" style="color: #777;">Bio</h3>
								</div>
								<div class="panel-body">
									{if !empty($user.bio)}
										{$user.bio}
									{else}
										No bio information.
									{/if}
								</div>
							</div>
						</div>
					</div>
					<div class="row" style="margin-top: 100px;">
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title" style="color: #777;">No. of Stories Contributed</h3>
								</div>
								<div class="panel-body">
									<strong>{$user.no_contributions}</strong>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title" style="color: #777;">No. of Stories</h3>
								</div>
								<div class="panel-body">
									<strong>{$user.no_stories}</strong>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>
<div class="modal fade" id="edit-profile-modal" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal">
				<i class="fa fa-times"></i>
			</button>
			<div class="modal-header">
				<h2 align="center" class="text-muted">Edit Profile</h2>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2">
						<div class="user-info-holder">
							<p class="line-behind">Change Information</p>
							<form action="/user/change_information" id="user-info-form" method="POST">
								<div class="form-group">
									<label>Nickname:</label>
									<input type="text" name="nickname" class="form-control" placeholder="Your Nickname..." required value="{$user.name}">
								</div>
								<div class="form-group">
									<label>First Name:</label>
									<input type="text" name="first-name" class="form-control" placeholder="Your first name..." required value="{$user.first_name}">
								</div>
								<div class="form-group">
									<label>Last Name:</label>
									<input type="text" name="last-name" class="form-control" placeholder="Your last name..." value="{$user.last_name}">
								</div>
								<div class="form-group">
									<label>Bio:</label>
									<textarea name="bio" class="form-control" placeholder="Your bio...">{$user.bio}</textarea>
								</div>
								<div class="row form-group">
									<div class="col-sm-6 pull-right">
										<button class="btn btn-info btn-block text-uppercase" type="submit">
											Update
										</button>
									</div>
								</div>
								<div class="user-info-message"></div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="change-password-modal" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal">
				<i class="fa fa-times"></i>
			</button>
			<div class="modal-header">
				<h2 align="center" class="text-muted">Change Password</h2>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2">
						<div class="password-holder">
							<p class="line-behind">Change Password</p>
							<form action="/user/change_password" id="password-change" method="POST">
								<div class="form-group">
									<label>Current password:</label>
									<input type="password" name="current-password" class="form-control" placeholder="Current Password..." required>
								</div>
								<div class="form-group">
									<label>New Password:</label>
									<input type="password" name="new-password" class="form-control" placeholder="New Password..." required>
								</div>
								<div class="form-group">
									<label>Confirm New Password:</label>
									<input type="password" name="confirm-new-password" class="form-control" placeholder="Confirm New Password..." required>
								</div>
								<div class="row form-group">
									<div class="col-sm-6 pull-right">
										<button class="btn btn-info btn-block text-uppercase" type="submit">
											Change
										</button>
									</div>
								</div>
								<div class="password-message"></div>
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
		
		$('input[name="new-password"]').password();

	});
</script>
{include "user_followers.tpl"}