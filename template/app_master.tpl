<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
	    <title>Fableus</title>
	    <meta name="description" content="{if isset($html_description)}{$html_description}{/if}" />
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<meta content="IE=edge" http-equiv="X-UA-Compatible">
		<meta name="format-detection" content="telephone=no">
		
		{if isset($story) && !empty($story)}
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Fableus: {$story.title}" />
		<meta property="og:description" content="{$story.def_chapter.50_words}" />
		<meta property="og:image" content="{BASE_URL}/resource/img/story/{$story.image}" />
		{else}
		<meta property="og:type" content="website" />
		<meta property="og:title" content="Fableus" />
		<meta property="og:description" content="We all have a story to tell." />
		<meta property="og:image" content="{BASE_URL}/resource/img/elements/book-icons.png" />
		{/if}


		<link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
		<link rel="stylesheet" href="/resource/style/main.css" type="text/css" />
		<link rel="stylesheet" href="/resource/style/style.min.css" />
		<link rel="stylesheet" href="/resource/vendors/password-strength-meter-master/dist/password.min.css" />
		<link rel="stylesheet" href="/resource/vendors/summernote/summernote.css" />
		<link rel="stylesheet" href="/resource/vendors/drawerJs/drawerJs.min.css" />
		<link rel="stylesheet" href="/resource/vendors/drawerJs/font-awesome.min.css" />
		<link rel="stylesheet" href="/resource/vendors/jssocials/jssocials.css" />
		<link rel="stylesheet" href="/resource/vendors/jssocials/jssocials-theme-flat.css" />
		<link rel="stylesheet" href="/resource/style/bootstrap-tour.min.css">

		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	    <script src="/resource/vendors/jquery/dist/jquery.min.js"></script>
	    <script src="/resource/vendors/jquery/dist/jquery-ui.min.js"></script>
		<script src="/vendors/ckeditor/ckeditor.js"></script>
		<script src="/resource/vendors/password-strength-meter-master/dist/password.min.js"></script>
		<script src="/resource/js/scripts.js"></script>
		<script src="/resource/js/jstree.min.js"></script>
		<script src="/vendors/ckeditor/ckeditor.js"></script>
		<script src="/resource/vendors/slick-carousel/slick/slick.min.js"></script>
		<script src="/resource/vendors/bootstrap-sass-official/assets/javascripts/bootstrap.js"></script>
		<script src="/resource/vendors/summernote/summernote.js"></script>
		<script src="/resource/vendors/jssocials/jssocials.min.js"></script>
		<script src="/resource/js/bootstrap-tour.min.js"></script>

		<!-- EnjoyHint Plugin -->
		<link rel="stylesheet" href="/resource/vendors/enjoyhint-master/enjoyhint.css" />
		<script src="/resource/vendors/enjoyhint-master/enjoyhint.min.js"></script>

		<style type="text/css">
			.note-popover.popover {
				display: none !important;
			}

			.jssocials-share-link {
				border-radius: 50%;
			}
		</style>
		<script type="text/javascript">
			var tour = new Tour({

				storage: false,
				steps: [
					{
						element: "#tell-friend",
						content: "Invite your friends & family to Fableus.",
					},
				]

			});
		</script>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-133294535-1"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){ dataLayer.push(arguments); }
			gtag('js', new Date());

			gtag('config', 'UA-133294535-1');
		</script>

	</head>
	
	<body>
		{if isset($smarty.session.user) && isset($smarty.session.user.user_id) }
			{include file='header_private.tpl'}
		{else}
			{include file='header.tpl'}
		{/if}
			{$contents}
			{include file='footer.tpl'}
			{include file='invite.tpl'}
		<script src="/resource/js/xpand.js"></script>
	</body>
</html>