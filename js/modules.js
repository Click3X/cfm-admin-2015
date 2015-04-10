// $(document).ready(function() {
//     $('input[type="checkbox"]').click(function() {
//         console.log('fuck');
//     });
// });

$(function() {

	var module_val;

    $('.modal-container').easyModal({
    	top: 200,
		overlay: 0.2
    });


	$('.add-module-button').click(function(e) {

		module_val = $('#module-selection').val();
			
		if (module_val) {						
			$(this).attr('href', '#' + module_val);
			var target = module_val + '-modal';
		}
		$('#' + target).trigger('openModal');
		e.preventDefault();
	});

	

	$('.add-gallery-img-button').click(function(e) {
		e.preventDefault();

		$('#gallery-input-container').append('<div class="single-media-container"><hr><label >Enter gallery image file name (no extension):</label><br><input type="text" class="gallery-added" name="gallery[]"><select name="media-type[]" class="media-type"><option value="1">jpg</option><option value="3">gif</option><option value="4">png</option></select><br><label >Enter gallery title (appear as hover text):</label><br><input type="text" name="gallery-title[]" /><br><label >Enter gallery link:</label><br><input type="text" name="gallery-link[]" /></div><a class="gallery-delete-button button">-</a>');

		
		$('.gallery-delete-button').click(function() {
			
			$(this).prev().remove();
			$(this).remove();

		});
	});



	$('.cancel-modal').click(function(e) {
		$('.modal-container').trigger('closeModal');
	});

	$(".save-modal").on('submit', function(e){

		e.preventDefault();

		var data = {};
		
	    $.ajax({
	        type: 'POST',
	        url: base_url + "/projects/saveModule",
	        data: data,
	        dataType: "json",
	        success: function( data ) {
	            console.log( data );
	        }

	        // error: function() {
	        // 	console.log('error!!!');
	        // }
	    });

	    $('.modal-container').trigger('closeModal');
		
	});

	// DELETE A MODULE
	$(".delete-module-button").click(function(){
		// console.log('going to delete a module');
		
		var mdid = $(this).data("module-id");
		var pjid = $(this).data("project-id");

		var data = {
			module_id: mdid,
			project_id: pjid
			};
		// console.log(data);
		
	    $.ajax({
	        type: 'POST',
	        url: base_url + "projects/deletemodule",
	        data: data,
	        dataType: "text",
	        success: function( data ) {
	            console.log( data );
	        }
	    });

	    location.reload();
		
	});

	// DELETE A GALLERY MEDIA
	$(".delete-gallery-media-button").click(function(){
		// console.log('going to delete a module');
		
		var mdid = $(this).data("module-id");
		var meid = $(this).data("media-id");
		var pjid = $(this).data("project-id");

		var data = {
			module_id: mdid,
			media_id: meid,
			project_id: pjid

			};
		console.log(data);

		if ($(this).parents('.module.gallery').find('.gallery-media').length == 1) {
			// remove entire module
			console.log('remove entire module');
			$.ajax({
		        type: 'POST',
		        url: base_url + "projects/deletemodule",
		        data: data,
		        dataType: "text",
		        success: function( data ) {
		            console.log( data );
		        }
		    });
		    $(this).parents('.module.gallery').remove();
		} else {
			// remove only gallery media
			console.log('remove only gallery media');
			$.ajax({
		        type: 'POST',
		        url: base_url + "projects/deleteGalleryMedia",
		        data: data,
		        dataType: "text",
		        success: function( data ) {
		            console.log( data );
		        }
		    });
		    $(this).parent().remove();
		}

		
	});






});

function btnShow() {
	module_val = $('#module-selection').val();
    if (module_val) {
    	$('.add-module-button').css({'opacity': '1', 'cursor': 'pointer'});
    }
}







