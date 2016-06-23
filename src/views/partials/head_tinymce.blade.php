<!--<script src="//cdn.tinymce.com/4/tinymce.min.js"></script> -->

<!--<script src="/js/tinymce_compressor/tinymce.gzip.js"></script>
<script src="//tinymce.cachefly.net/4.3/tinymce.min.js"></script>
-->

<script src="/js/tinymce/tinymce.gzip.js"></script>
<script>







	tinymce.init({
		selector: 'textarea'
	});
</script>



<?php
		/*


<!--Summernote [ OPTIONAL ] -->
<!--<link href="{{config('wi.dashboard.theme_path')}}/vendor/summernote/summernote.min.css" rel="stylesheet">-->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.css" rel="stylesheet">
<!--<script src="{{config('wi.dashboard.theme_path')}}/vendor/summernote/summernote.min.js"></script>-->
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.js"></script>
<script>
	// SUMMERNOTE
	// =================================================================
	// Require Summernote
	// http://hackerwins.github.io/summernote/
	// =================================================================


	//$(document).ready(function() {
	//$('.summernote').summernote({});

	//$('.note-codable').eq(0).attr('name','test');


	$(document).ready(function() {
		$('.wysiwyg').summernote();
	});




	$('.wysiwygORG').summernote({

		oninitx: function () {


			$('.note-editor').each(function (index, value) {
				//console.warn($('.note-editor').prev().attr('id'));
				console.warn($(this).prev().attr('id'));
				$(this).find('.note-codable').attr('name',''+$(this).prev().attr('id')+'');
				$(this).find('.note-codable').attr('id',''+$(this).prev().attr('id')+'');
				//console.info($(this).code());
				//$(this).find('.note-codable').html('testa');
				//console.log('div' + index + ':' + $(this).attr('id'));
			});
			//console.log('Summernote is launched');
			//

			//var id = this.id;
			//console.info('test'+id+' - '+this.id);
			//console.info($('.note-codable'));

			//$('.note-editable').attr('data-bind', 'html: Description');
			//var test = $(this).closest('.note-editor').siblings('textarea');
			//$(".summernote").code('data-bind', 'html: Description');
			var content = $('textarea[name="content"]');//.html($('#summernote').code());

		},
		onblurx: function() {
			console.warn('Editable area loses focus 1');
			console.info($(this).code());
			console.info($(this).siblings(".note-codable").html($(this).code()));


			//$('textarea[name="content"]').html($('#summernote').code());
		}

	});


	//});
</script>

		*/
?>