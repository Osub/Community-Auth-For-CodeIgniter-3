/*
 * Community Auth - pending_registrations.js
 * @ requires jQuery
 *
 * Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 *
 * Licensed under the BSD licence:
 * http://www.opensource.org/licenses/BSD-3-Clause
 */

 $(document).ready(function(){
	var table = $("#myTable");
	table.tablesorter({
		widgets: ['zebra'],
		headers: { 
			0: { sorter: false }
		}
	});
});