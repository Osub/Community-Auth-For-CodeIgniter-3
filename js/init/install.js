/*
 * COMMUNITY AUTH INSTALLATION JAVASCRIPT
 *
 * Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * http://community-auth.com
 *
 * Licensed under the BSD licence:
 * http://www.opensource.org/licenses/BSD-3-Clause
 */
$(document).ready(function(){
	/**
	 * If any admin fields are not empty, 
	 * then form validation failed, 
	 * and we want to keep the fields visible
	 */
	var admin_fields_empty = true;
	var test_users_fields_empty = true;
	$('#admin_fields input').each(function(i,el){
		if( $(el).val() ){
			admin_fields_empty = false;
		}
	});
	$('#test_users_fields input').each(function(i,el){
		if( $(el).val() ){
			test_users_fields_empty = false;
		}
	});
	if( admin_fields_empty ){
		$('#admin_fields').hide();
	}
	if( test_users_fields_empty ){
		$('#test_users_fields').hide();
	}
	/**
	 * If the "Create Admin" or "Create Test Users" 
	 * checkboxes are checked, we want to show the 
	 * appropriate fields so they can be filled in
	 */
	$('#admin').click(function(){
		if($(this).is(':checked')){
			$('#admin_fields').slideDown('slow');
		}else{
			$('#admin_fields').slideUp('fast');
		}
	});
	$('#users').click(function(){
		if($(this).is(':checked')){
			$('#test_users_fields').slideDown('fast');
		}else{
			$('#test_users_fields').slideUp('fast');
		}
	});
	/**
	 * Simple form validation to make sure 
	 * required fields are not empty when 
	 * there is a form submission
	 */
	$('#submit_button').click(function(e){
		var submit_failure = false;
		var admin_fields_empty = false;
		var test_users_fields_empty = false;
		$('#admin_fields input').each(function(i,el){
			if( ! $(el).val() ){
				admin_fields_empty = true;
			}
		});
		$('#test_users_fields input').each(function(i,el){
			if( ! $(el).val() ){
				test_users_fields_empty = true;
			}
		});
		if( admin_fields_empty && $('#admin').is(':checked') ){
			alert('All admin fields are required to create an admin.');
			submit_failure = true;
		}
		if( test_users_fields_empty && $('#users').is(':checked') ){
			alert('All test user fields are required to create test users.');
			submit_failure = true;
		}
		if( ! submit_failure ){
			return true;
		}else{
			return false;
		}
	});
});