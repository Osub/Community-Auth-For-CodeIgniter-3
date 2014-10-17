/*
 * DEFAULT CHAR LIMITERS FOR DISALLOWED INPUT CHARACTERS PLUGIN
 *
 * Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * http://community-auth.com
 *
 * Licensed under the BSD licence:
 * http://www.opensource.org/licenses/BSD-3-Clause
 */
 $(document).ready(function(){

	// The maximum allowed characters for any field with the max_chars class
	$(document).disableChars(
		'.max_chars', 
		{
			allow:" !@#$%^*+-=?_~.,:;/'\"()\s"
		}
	);

	// Only alpha and numeric characters allowed in fields with the alpha_numeric class
	$(document).disableChars(
		'.alpha_numeric'
	);

	// Only alpha characters are allowed for fields with the alpha_only class
	$(document).disableChars(
		'.alpha_only', 
		{
			limit_to:"alpha"
		}
	);	

	// Only numeric characters are allowed for fields with the numeric_only class
	$(document).disableChars(
		'.numeric_only', 
		{
			limit_to:"numeric"
		}
	);

	// Fields with first_name and last_name classes are allowed to have alpha characters, plus a few extra
	$(document).disableChars(
		'.first_name, .last_name', 
		{
			limit_to:"alpha",
			allow:" /.()-,'"
		}
	);

	// Password fields accept most characters, but not all
	$(document).disableChars(
		'.password', 
		{
			allow:"~`!@#$%^&*()_-+={[}]|:;<,>.?/"
		}
	);
});