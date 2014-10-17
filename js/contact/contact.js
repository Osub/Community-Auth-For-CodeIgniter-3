/*
 * CONTACT FORM'S OFFLINE MODE JAVASCRIPT
 *
 * Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * http://community-auth.com
 *
 * Licensed under the BSD licence:
 * http://www.opensource.org/licenses/BSD-3-Clause
 */
$(document).ready(function(){

	// Any form element triggers modal
	$('input, textarea').click(function(e){
		// Make sure the form submit button doesn't do anything
		e.preventDefault();

		// Fade in the mask overlay slowly
		$('#mask_overlay').fadeIn(250).fadeTo(500,0.8);

		//Get the window height and width
		var winH = $(window).height();
		var winW = $(window).width();

		$('#modal_dialog')
		/**
		 * Modal width set here so it can be custom. Also set here because 
		 * the calculations for height and width won't work if the width is
		 * applied in the same css object.
		 */
		.css({
			'width': '480px'
		})
		// Center the modal both vertically and horizontally
		.css({
			'top': winH / 2 - $('#modal_dialog').height() / 2,
			'left': winW / 2 - $('#modal_dialog').width() / 2
		})
		// Fade in the modal box
		.fadeIn(750);
	});

	// Hide the mask overlay and modal box when mask overlay, close link, close button, or red x is clicked
	$('#mask_overlay, #close-link, #close-button, #red-x-close').click(function(e){
		// prevent the close link from being followed
		e.preventDefault();
		// Hide the mask overlay
		$('#mask_overlay').hide();
		// Hide the modal box
		$('#modal_dialog').hide();
	});

});