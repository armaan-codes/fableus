<style type="text/css">
	.line-behind:before, .line-behind:after {
		width: 135px !important;
	}


	.user-image {
		position: relative;
		top: -50px;
	}
	
	.user-image input.upload {
		display: none;
	}

	.upload-user-image {
		margin-top: 15px;
		display: none;
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
		<div class="container">
			<div class="profile-area">
				<div class="row" style="background-image: url('/resource/img/user-profile-bg.jpg'); height: 200px;"></div>
				<div class="row" style="margin-right: -5px;">
					<div class="col-sm-2 user-image">
						<div class="col-sm-10 col-sm-offset-1">
							<img src="/resource/img/user/{$user.image}" width="100" height="100" class="profile-image image-preview">
						</div>
						<form action="/user/image_upload" method="POST" enctype="multipart/form-data">
							<input type="file" name="user-image" accept="image/*" class="upload">
							<div class="col-sm-10 col-sm-offset-1">
								<button class="btn btn-info btn-block upload-user-image" type="submit">Save</button>
							</div>
						</form>
					</div>
					<div class="col-sm-2 profile-box">
                        <div class="profile-name">
                            Name: <span id="edit-name" class="pull-right"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
                            <h3 class="text-muted" align="right"><strong>{$user.name}</strong></h3>
                        </div>
                        <div class="profile-name-edit">
                            <form action="/user/change_name" method="POST">
                                Name: <button id="done-name" type="submit" class="pull-right"><i class="fa fa-check" aria-hidden="true"></i> Done</button>
                                <input type="text" name="user-name" value="{$user.name}"  class="form-control">
                            </form>
                        </div>
					</div>
					<div class="col-sm-2 profile-box">
                        Membership:
						<h3 class="text-muted" align="right">
							<strong>Regular</strong>
						</h3>
					</div>
					<div class="col-sm-2 profile-box">
						No. of Stories:
						<h3 class="text-muted" align="right"><strong>{$user.no_stories}</strong></h3>
					</div>
					<div class="col-sm-2 profile-box">
						No. of Stories Contributed:
						<h3 class="text-muted" align="right"><strong>{$user.no_contributions}</strong></h3>
					</div>
					<div class="col-sm-2 profile-box">
						
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="section" id="section-anchor">
		<div class="container">
			<div class="row">
				<div class="col-sm-7">
					<p class="line-behind">Member Details</p>
					<div class="row">
						<div class="col-sm-4">
							<h3 align="right" class="text-muted">Email:</h3>
						</div>
						<div class="col-sm-8">
							<h3 align="left" class="text-muted">{$user.email}</h3>
						</div>
					</div>
					{if $plan}
					<div class="row">
						<div class="col-sm-4">
							<h3 align="right" class="text-muted">Next Renewal Date:</h3>
						</div>
						<div class="col-sm-8">
							<h3 align="left" class="text-muted">{$plan.ts_remaining}</h3>
						</div>
					</div>
					{/if}
				</div>
				<div class="col-sm-5">
					<div class="password-holder">
						<p class="line-behind">Change Password</p>
						<form action="/user/change_password" id="password-change" method="POST">
							<div class="form-group">
								<input type="password" name="current-password" class="form-control" placeholder="Current Password..." required>
							</div>
							<div class="form-group">
								<input type="password" name="new-password" class="form-control" placeholder="New Password..." required>
							</div>
							<div class="form-group">
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
	</section>
</main>
<script type="text/javascript">
	$(document).ready(function() {
		
		$('input[name="new-password"]').password();

		$('#edit-name').on('click', function(){
		
		    $('.profile-name').hide();
        
            $('.profile-name-edit').show();
        
        });

        $('#done-name').on('click', function(){
        
            $('.profile-name-edit').hide();
        
            $('.profile-name').show();
        
        });
    
    });
</script>