/*
 * Community Auth - uploader-controls.js
 * @ requires jQuery
 *
 * Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 *
 * Licensed under the BSD licence:
 * http://www.opensource.org/licenses/BSD-3-Clause
 */

 // Declare our functions in our own namespace
(function($){
	var namespace;
	namespace = {
		/**
		 *	Update the sort order in the database
		 */
		image_data_update: function(show_response)
		{
			var image_data = {};

			// Get the source attribute for all existing images
			$('.sortable-element img').each(function(i){
				image_data[i] = $(this).attr('src');
			});

			// Turn image data object into json string
			var json_text = JSON.stringify(image_data);

			// Get the CI CSRF token name
			var ci_csrf_token_name = $('#ci_csrf_token_name').val();

			// Set up post vars
			var post_data = {
				'image_data': image_data,
				'token': $('input[name="token"]').val()
			};

			post_data[ci_csrf_token_name] = $('input[name="' + ci_csrf_token_name + '"]').val();

			// Do Post
			$.ajax({
				url: $('#update_image_order_url').val(),
				type: 'POST',
				cache: false,
				data: post_data,
				dataType: 'json',
				success: function(response, textStatus, jqXHR){

					// Renew tokens
					$('input[name="token"]').val( response.token );
					$('input[name="' + ci_csrf_token_name + '"]').val( response.ci_csrf_token );

					// Show a response if applicable
					if(show_response === true){
						$('#status-bar').css('display', 'block');
						$('#status-bar').html('<p>' + response.status + '</p>').delay(2500).fadeOut('slow');
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert('Error: Server Connectivity Error.\nHTTP Error: ' + jqXHR.status + ' ' + errorThrown);
				}
			});
		},
		/**
		 *	Delete an image from the database
		 */
		delete_image: function($draggable){

			// Get CI CSRF token name
			var ci_csrf_token_name = $('#ci_csrf_token_name').val();

			// Set up post vars
			var params = {
				'src': $draggable.find("img").attr("src"),
				'token': $('input[name="token"]').val()
			};

			params[ci_csrf_token_name] = $('input[name="' + ci_csrf_token_name + '"]').val();

			// Do POST
			$.ajax({
				url: $('#delete_image_url').val(),
				type: 'POST',
				data: params,
				dataType: 'json',
				success: function( response ){

					// Renew tokens
					$('input[name="token"]').val( response.token );
					$('input[name="' + ci_csrf_token_name + '"]').val( response.ci_csrf_token );

					// Show status message
					$('#status-bar').css('display', 'block');
					$('#status-bar').html('<p>' + response.status + '</p>').delay(2500).fadeOut('slow');
				}
			});
			
			// Keep the drag to trash as being interpreted as a sort
			$("#image-ul").sortable('cancel');
		},
		/**
		 *	Make the list of images sortable
		 */
		make_sortable: function(){
			$("#image-ul").sortable({
				// allow dragging into the trash can
				connectWith: '#trash_can',
				item: 'li',
				// revert causes bugginess in IE, so left out even though cool
				//revert: 200,
				opacity: 0.6,
				cursor: 'move',
				// apply this css class to available places to drop within list
				placeholder: 'placeholder-border',
				// drop available once mouse has touched droppable area
				tolerance: 'pointer',
				update: function(){
					// Update images data in database
					community_auth.image_data_update(true);
				}
			});
		}
	};
	window.community_auth = namespace;
})(this.jQuery);

$(document).ready(function(){
	// Immediately execute when DOM is ready.
	$(function(){
		// sort the images
		community_auth.make_sortable();
		// allow for deleting images by sending them to the trash can
		$("#trash_can").droppable({
			accept: '#image-ul > li',
			// when the trash can is hovered on by a draggable, set the css class
			hoverClass: 'delete-border',
			drop: function(event, ui) {
				// setTimeout takes care of IE bug where deleted item remains
				setTimeout(function() { ui.draggable.remove(); }, 1);
				// delete the image
				community_auth.delete_image(ui.draggable);
			 }
		});
	});

	new AjaxUpload('upload-button', {
		action: $('#upload_image_url').val(),
		responseType: 'json',
		onSubmit : function(file , ext){

			// Get CI CSRF token name
			var ci_csrf_token_name = $('#ci_csrf_token_name').val();

			// set dynamic data (post vars) with setData
			var post_data = {
				'dir_name':'custom_uploader',
				'user_id': $('#user_id').val(),
				'no_success_callback': 'true',
				'token': $('input[name="token"]').val()
			};

			post_data[ci_csrf_token_name] = $('input[name="' + ci_csrf_token_name + '"]').val();

			this.setData(post_data);

			// Allows only images set in config.
			var allowed_types = $('#allowed_types').val();
			var regex = new RegExp('^(' + allowed_types + ')', 'i');
			if (ext && ext.search(regex) != '-1') {
				//this.disable();
			} else {
				// Show error: extension is not allowed
				$('#status-bar').css('display', 'block');
				$('#status-bar').html('<p>Error: Only images are allowed ( ' + $('#file_types').val() + ' )</p>').delay(2500).fadeOut('slow');
				// cancel upload
				return false;
			}
		},
		onComplete: function(file, response){

			// Get CI CSRF token name
			var ci_csrf_token_name = $('#ci_csrf_token_name').val();

			// Renew tokens
			$('input[name="token"]').val(response.token);
			$('input[name="' + ci_csrf_token_name + '"]').val( response.ci_csrf_token );

			// check if upload was successful
			if (response && /^(success)/i.test(response.status)) {

				// check if image list already exists
				if($("#image-ul").length > 0){
					$("#image-ul").append('<li class="sortable-element"><img src="' + response.file_url + '" width="100" height="75" /></li>');
				// if image list doesn't exist, create it
				}else{
					$('#no-images').replaceWith('<ul id="image-ul"><li class="sortable-element"><img src="' + response.file_url + '" width="100" height="75" /></li></ul>');
					// sort the images
					community_auth.make_sortable();
				}

				// Update images data in database
				community_auth.image_data_update(false);

				// replace text of upload link after first upload
				$('#upload-button').attr('value', 'Upload Another Image');

				// Show status message
				$('#status-bar').css('display', 'block');
				$('#status-bar').html('<p>Upload Successful</p>').delay(2500).fadeOut('slow');
			} else {
				// Show error message
				alert('Error uploading file ('+file+')! \n'+ response.issue);
				$('#status-bar').css('display', 'block');
				$('#status-bar').html('<p>Upload Failed</p>').delay(2500).fadeOut('slow');
			}
		}
	});

	// Show network activity image while ajax request is being performed
 	$('#uploader-activity').bind({
 		ajaxStart: function(){ $(this).show(); },
 		ajaxStop: function(){ $(this).hide(); }
 	});
});