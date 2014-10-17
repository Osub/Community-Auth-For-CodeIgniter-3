/*
 * DISALLOWED INPUT CHARACTERS
 * Version 3 - December 20, 2011
 * @ requires jQuery 1.7+
 *
 * Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 *
 * Licensed under the BSD licence:
 * http://www.opensource.org/licenses/BSD-3-Clause
 *
 * 
 * OPTIONS
 * -------
 * 
 * 1) allow
 * Characters that will be allowed outside of the charbase
 *
 * 2) limit_to
 * The charbase to limit to
 *
 * 3) direct_regex
 * A regular expressions pattern to to be used instead of a charbase with optional allowed characters
 *
 *
 * EXAMPLES
 * --------
 *
 * -- Example limiting input#element1 to alpha characters only
 *
 *		$(document).disableChars("#element1", {limit_to:"alpha"});
 *
 *
 * -- Example limiting input#element2 to an alphanumeric charbase with some extra allowed characters
 *
 *		$(document).disableChars("#element2", {allow:".,:-() "});
 *
 *
 * -- Example limiting input#element3 to numeric characters only
 *
 *		$(document).disableChars("#element3", {limit_to:"numeric"});
 *
 *
 * -- Example limiting input#element4 to numeric charbase with some extra allowed characters
 *
 *		$(document).disableChars("#element4", {limit_to:"numeric",allow:"-()."});
 *
 *
 * -- Example limiting input#element5 to match a regular expression
 *
 *		$(document).disableChars("#element5", {direct_regex:/[^-A-Z0-9~!#&*()_+:\'",.?]/ig});
 *
 */

(function($){
	$.fn.disableChars = function(element, options) {

		/* Set defaults */
		var defaults = {
			allow: '',
			limit_to: '',
			direct_regex: null
		};

		/* Override defaults with options */
		var options = $.extend(defaults, options);

		/* Check limit_to option and set charbase */
		if(options.limit_to == 'alpha'){
			/* Charbase is alphabet characters only */
			var charbase = 'a-z';
		}else if(options.limit_to == 'numeric'){
			/* Charbase is numeric characters only */
			var charbase = '0-9';
		}else{
			/* Charbase is alpha-numeric */
			var charbase = 'a-z0-9';
		}

		/* Split the allow option (string) into an array of characters */
		var allowed = options.allow.split('');

		/* Process each member of the allowed array */
		var converted_allowed = $.map(allowed, function(chr) {
			/* Ensure that a letter doesn't become a special regex identifier when charbase is numeric. */
			if(options.limit_to == 'numeric'){
				var regEx = /^[a-z]+$/;
				/* If this character is not a letter */
				if(! chr.match(regEx)){
					/* Escape the character */
					return '\\' + chr;
				}else{
					/* Don't escape the character */
					return chr;
				}
			/* Always escape the character if charbase is not numeric */
			}else{
				return '\\' + chr;
			}
		});

		/* Assemble a string from the processed members of the allowed array */
		var extra_chars = converted_allowed.join('');

		/* Determine if direct regex or charbase option used */
		if(options.direct_regex == null){
			/* Combine character base with allowed chars and create regex object */
			var regex = new RegExp('[^' + charbase + extra_chars + ']', 'ig');
		}else{
			/* If direct regex being used, charbase and extra_chars have no effect */
			var regex = options.direct_regex;
		}

		/* Monitor events on designated elements */
		$(document)
		/* Keyup and blur events necessary to handle pasted data */
		.on( 'keyup blur', element, function(){

			/* If a character is being limited */
			if( this.value.search(regex) != '-1' ){
				/* Remove the character from the input */
				this.value = this.value.replace(regex, '');
			}
		})
		/* Disable right clicks to prevent pasting via context menu */
		.on( 'contextmenu', element, function (){
			return false
		});
	}
})(jQuery);