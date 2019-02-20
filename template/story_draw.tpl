<style>
    #canvas-editor {
        margin-top: 25px;
        margin-bottom: 125px;
    }

    .line-behind:before, .line-behind:after {

    	width: 125px !important;

    }

    #redactor-drawer-box {
        z-index: 2000;
        top: 50px !important;
        left: 15px !important;
    }
</style>

<div class="modal-header">
	<div class="modal-logo">
		<img src="/resource/img/elements/site-logo.svg" alt="">
	</div>
</div>
<div class="modal-body">
	<div id="canvas-editor"></div>
</div>
<div class="modal-footer">
	<div class="col-md-6 col-md-offset-3">
		<button class="btn btn-info btn-block" id="done-drawing">Done</button>
	</div>
</div>


<script type="text/javascript">
	$(document).ready(function () {
	    var drawer = new DrawerJs.Drawer(null, {
	        texts: customLocalization,
	        plugins: drawerPlugins,
	        defaultActivePlugin : { name : 'Pencil', mode : 'lastUsed'},
	    }, 700, 250);
	    
	    $('#canvas-editor').append(drawer.getHtml());
	    
	    drawer.onInsert();

    	$('#done-drawing').on('click', function(e) {
    		setTimeout(function(){
    			var anchor = document.createElement('a');
    			anchor.download = "story-sketch";
    			var img_src = $('.editable-canvas-image').attr('src');
    			anchor.href = img_src;
    			anchor.click();
    
    		}, 1500);
    	});
		
	});
</script>
		<script src="/resource/vendors/drawerJs/drawerJs.standalone.min.js"></script>
		<script src="/resource/vendors/drawerJs/drawerLocalization.js"></script>
		<script src="/resource/vendors/drawerJs/drawerJsConfig.js"></script>