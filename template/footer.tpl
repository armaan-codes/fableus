<footer>
	<div class="container">
		<div class="footer-inner">
			<div class="row">
				<div class="col-sm-6 push-down">
					<p class="mobile-text-center">Copyright © Fableus.com 2018. All Rights Reserved</p>
				</div>
				<div class="col-sm-6">
					<ul class="footer-nav">
					    <li>
					        <a href="#" data-toggle="modal" data-target="#contact-modal" data-backdrop="static">Contact Us</a>
					    </li>
						{if !empty($smarty.session.user)}
						<li>
							<a href="/stories">stories</a>
						</li>
						{/if}
						{if empty($smarty.session.user)}
						<li>
							<a href="#" data-toggle="modal" data-target="#sign-modal" data-backdrop="static">submit a story</a>
						</li>
						{else}
						<li>	
							<a href="#" data-toggle="modal" data-target="#story-title" data-backdrop="static">submit a story</a>
						</li>
						{/if}
						{if empty($smarty.session.user)}
						<li>
							<a href="#" data-toggle="modal" data-target="#register-modal" data-backdrop="static">sign up</a>
						</li>
						<li>
							<a href="#" data-toggle="modal" data-target="#sign-modal" data-backdrop="static">sign in</a>
						</li>
						{/if}
					</ul>	
				</div>
			</div>
		</div>
	</div>
</footer>
{if empty($smarty.session.user)}
	{include file="signup.tpl"}
	{include file="signin.tpl"}
{else}
	{include file="story_title.tpl"}
{/if}
{include file="contact.tpl"}