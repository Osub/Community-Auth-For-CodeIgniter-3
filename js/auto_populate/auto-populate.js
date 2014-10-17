/*
 * Community Auth - auto-populate.js
 * @ requires jQuery
 *
 * Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 *
 * Licensed under the BSD licence:
 * http://www.opensource.org/licenses/BSD-3-Clause
 */
$(document).ready(function(){

	// Whenever one of the form dropdowns is changed
	$('#type, #make, #model').change(function(){
		// When type is changed, reset make and model
		if( $(this).attr('id') == 'type' ){
			$('#make option').attr('selected', false);
			$('#model option').attr('selected', false);
			$('#color option').attr('selected', false);
		}else if( $(this).attr('id') == 'make' ){
			$('#model option').attr('selected', false);
			$('#color option').attr('selected', false);
		}else if( $(this).attr('id') == 'model' ){
			$('#color option').attr('selected', false);
		}
		// Get the CI CSRF token name
		ci_csrf_token_name = $('#ci_csrf_token_name').val();
		// Set post vars
		var post_vars = {
			'type':  $('#type option:selected').val(),
			'make':  $('#make option:selected').val(),
			'model':  $('#model option:selected').val(),
			'token': $('input[name="token"]').val()
		};
		post_vars[ci_csrf_token_name] = $('input[name="' + ci_csrf_token_name + '"]').val();
		// POST
		$.ajax({
			type: 'post',
			cache: false,
			url: $('#ajax_url').val(),
			data: post_vars,
			dataType: 'json',
			success: function(response, textStatus, jqXHR){
				if(response.status == 'success'){
					// Update the dropdowns and tokens
					$('#make').html(response.make);
					$('#model').html(response.model);
					$('#color').html(response.color);
					$('input[name="token"]').val(response.token);
					$('input[name="' + ci_csrf_token_name + '"]').val( response.ci_csrf_token );
				}else{
					alert(response.message);
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert('Error: Server Connectivity Error.\nHTTP Error: ' + jqXHR.status + ' ' + errorThrown);
			}
		});
	});

});