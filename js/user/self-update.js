/*
 * Community Auth - self-update.js
 * @ requires jQuery
 *
 * Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 *
 * Licensed under the BSD licence:
 * http://www.opensource.org/licenses/BSD-3-Clause
 */
 $(document).ready(function(){
	
	// Show passwords
	$("#show-password").passwordToggle({target:"#user_pass"});
	$("#show-password").passwordToggle({target:"#user_pass_confirm"});

	// Show uploader activity container
	$('.uploader-activity-container').show();

	// Change CSS for profile upload controls
	$('.profile-upload-controls').css({
		'width' : '329px'
	});

	// Change CSS for uploader button
	$('.uploader-button').css({
		'width'      : '100px',
		'text-align' : 'left',
		'margin'     : '0'
	})

	// Replace file input element with button for ajax uploader
	var new_button = $('<input />', {
		type  : 'button',
		id    : 'profile_image_uploader',
		value : 'upload'
	});

	$('#file-input').replaceWith( new_button );

	// Get the profile image upload URL (changes if index.php is present or removed in site URLs)
	var upload_url = $('#upload_url').val();

	// Upload Profile Image
	new AjaxUpload('profile_image_uploader', {
		action: upload_url,
		responseType: 'json',
		onSubmit : function(file, ext){
			// Get CI csrf token name
			var ci_csrf_token_name = $('#ci_csrf_token_name').val();
			// Allows only standard image types
			var allowed_types = $('#allowed_types').val();
			var regex = new RegExp('^(' + allowed_types + ')', 'i');
			if (ext && ext.search(regex) != '-1') {
				// set dynamic data (post vars) with setData
				var post_vars = {
					'dir_name': 'profile_images',
					'user_id': $('#user_id').val(),
					'token': $('input[name="token"]').val(),
					'success_callback': '_profile_image'
				};

				post_vars[ci_csrf_token_name] = $('input[name="' + ci_csrf_token_name + '"]').val();

				this.setData( post_vars );
				// Show the uploader activity bar
				$('#uploader-activity').show();
			// If image was not one of the allowed types
			}else{
				alert('Image type not allowed.');
				// cancel upload
				return false;
			}
		},
		onComplete : function(file, response){
			// hide uploader activity bar
			$('#uploader-activity').hide();
			// check if upload was successful
			if (response && /^(success)/i.test(response.status)){
				// display the uploaded image on the page
				var img_src = ( $('#upload_url').val().indexOf('database') > 0 ) ? 'data:image/jpg;base64,' + response.callback_response : response.callback_response;
				$('.profile_image img').replaceWith('<img src="' + img_src + '" />');
				// Show the delete link
				$('#delete-profile-image').show();
			// If response indicates error
			}else{
				// Display error message
				alert('Error uploading file ('+file+')! \n'+ response.issue);
			}
			// Always update the token
			$('input[name="token"]').val(response.token);
			// Get CI csrf token name
			var ci_csrf_token_name = $('#ci_csrf_token_name').val();
			$('input[name="' + ci_csrf_token_name + '"]').val( response.ci_csrf_token );
		}
	});

	// Delete profile image
	$('#delete-profile-image').click(function(e){
		e.preventDefault();
		// Get CI csrf token name
		var ci_csrf_token_name = $('#ci_csrf_token_name').val();
		var post_vars = { 'token': $('input[name="token"]').val() };
		post_vars[ci_csrf_token_name] = $('input[name="' + ci_csrf_token_name + '"]').val();
		$.ajax({
			type: 'post',
			url: $('#delete_url').val(),
			cache: false,
			data: post_vars,
			dataType: 'json',
			success: function(response, textStatus, jqXHR){
				if(response.status == 'success'){
					// Hide the delete link
					$('#delete-profile-image').hide();
					// Show the default image
					$('.profile_image img').replaceWith('<img src="img/default-profile-image.jpg" />');
				}else{
					alert(response.message);
				}
				// update the form token(s)
				$('input[name="token"]').val(response.token);
				$('input[name="' + ci_csrf_token_name + '"]').val( response.ci_csrf_token );
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert('Error: Server Connectivity Error.\nHTTP Error: ' + jqXHR.status + ' ' + errorThrown);
			}
		});
	});
});