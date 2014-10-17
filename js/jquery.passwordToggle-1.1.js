/*
 * PASSWORD TOGGLE
 * Version 1.1.1 - December 9, 2011
 * @ requires jQuery
 *
 * Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 *
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 *
 * OPTIONS
 * -------
 * 
 * 1) target
 * The id of the password type input to toggle
 *
 * EXAMPLE
 * --------
 *
 *  Basic usage on an element that has an id of "password":
 *  $('#my-checkbox').passwordToggle();
 *
 *  Example showing a target element with an id that is not "password":
 *  $('#my-checkbox').passwordToggle({target: '#password-confirmation'});
 *
 */
(function($){
	$.fn.passwordToggle = function(options) {

		/* Set defaults */
		var defaults = {
			target:		'#password'
		};

		/* Override defaults with options */
		var options = $.extend(defaults, options);

		/* Register the click event on the trigger */
		$(this).on('click', function(e){

			/* Prevent default if trigger is a link */
			if( $(this).is('a') ){
				e.preventDefault();
			}

			/* Set resource var */
			var ele = $(options.target);

			/* Determine replacement type */
			var type = ele.attr('type') === 'text' ? 'password' : 'text';

			/* Build the replacement element */
			var new_element = $('<input />', {
				id           : ele.attr('id'),
				'class'      : ele.attr('class'),
				type         : type,
				name         : ele.attr('name'),
				value        : ele.val(),
				maxlength    : ele.attr('maxlength'),
				autocomplete : ele.attr('autocomplete')
			});

			/* Do the replacement */
			ele.replaceWith(new_element);

		});

		/* Allow method chaining */
		return this;
	}
})(jQuery);