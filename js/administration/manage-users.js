/*
 * Community Auth - manage_users.js
 * @ requires jQuery
 *
 * Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 *
 * Licensed under the BSD licence:
 * http://www.opensource.org/licenses/BSD-3-Clause
 */

 $(document).ready(function(){

 	// Search controls are only shown if javascript is enabled
 	$('#search-controls-upper').show();

 	// Network activity indicator container only shown if javascript is enabled
 	$('#network-activity-indicator').show();

 	// Search buttons only shown in javascript is enabled
 	$('button').show();

 	// Pagination needs a little padding
 	$('.pagination').css('padding-top','9px');

 	// Form elements need a little space between each other
 	$('.search-form-element').eq(0).css('margin-bottom','5px');

 	// Delete confirmation container only shown if javascript is enabled
 	$('#delete-confirmation').show();

 	// Show network activity image when ajax is being performed
 	$('#network-activity').bind({
 		ajaxStart: function(){ $(this).show(); },
 		ajaxStop: function(){ $(this).hide(); }
 	});

	// If pagination links or search button is clicked
	$(document).on('click','.pagination a, #search-button, #reset-button', function(e){

		// Prevent default action
		e.preventDefault();

		// If reset, remove search_for value
		if($(this).attr('id') == 'reset-button'){
			$('#search_for').val('');
		}

		// Determine the url to send the ajax request to
		if( $(this).is('a') ){
			// Pagination links may point to a specific page
			var this_url = $(this).attr("href");
		}else{
			// Search and reset buttons point to first page
			var this_url = $('#buttons_url').val();
		}

		var post_data = {};
		// Send form token and CI CSRF token with post
		post_data['token'] = $('input[name="token"]').val();
		var ci_csrf_token_name = $('#ci_csrf_token_name').val();
		post_data[ci_csrf_token_name] = $('input[name="' + ci_csrf_token_name + '"]').val();

		// Add in any search criteria if exists
		post_data['search_in'] = $('#search_in option:selected').val();
		post_data['search_for'] = $('#search_for').val();

		$.ajax({
			type: 'post',
			cache: false,
			url: this_url,
			data: post_data,
			dataType: 'json',
			success: function(response, textStatus, jqXHR){
				if(response.test == 'success'){

					// Replace table body with updated set of users
					$('tbody').empty().append(response.table_content);

					// Make sure to show the delete column
					$('.delete-column').show();

					// Replace pagination links
					$('#pagination p').empty().append(response.pagination_links);

					// Update form token and CI CSRF token
					$('input[name="token"]').val( response.token );
					$('input[name="' + ci_csrf_token_name + '"]').val( response.ci_csrf_token );
				}else{

					// Show error message
					alert(response.message);
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert('Error: Server Connectivity Error.\nHTTP Error: ' + jqXHR.status + ' ' + errorThrown);
			}
		});

	});

	$(document).on('click', '.delete-img', function(e){

		// Prevent default following of link
		e.preventDefault();

		// Confirm that the user should be deleted
		var answer = confirm('Delete: ' + $(this).parent().next().next().html() + '?');

		// Delete if confirmed
		if(answer){

			// Get the user ID to delete
			var tr_id = $(this).parent().parent().attr('id');

			var post_data = {};
			// Send form token and CI CSRF token with post
			post_data['token'] = $('input[name="token"]').val();
			var ci_csrf_token_name = $('#ci_csrf_token_name').val();
			post_data[ci_csrf_token_name] = $('input[name="' + ci_csrf_token_name + '"]').val();

			$.post(
				$(this).attr('href'),
				post_data,
				function(data){
					if(data.test == 'success'){

						// Show delete confirmation
						$('#' + tr_id).addClass('pink');
						$('#delete-confirmation p')
							.css('display', 'block')
							.html('MARKED ROWS HAVE BEEN DELETED')
							.delay(2500)
							.fadeOut('slow');

						// Update token and CI CSRF token
						$('input[name="token"]').val( data.token );
						$('input[name="' + ci_csrf_token_name + '"]').val( data.ci_csrf_token );
					}else{

						// Show error message
						alert(data.message);
					}
				},
				'json'
			);
		}else{
			// Show confirmation that user NOT deleted
			alert($(this).parent().next().next().html() + ' was not deleted');
		}
	});
});