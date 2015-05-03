<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Cookie Checker View
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
?>

<script>
	$(document).ready(function() {
		var cookie_checker = navigator.cookieEnabled ||
			("cookie" in document && (document.cookie.length > 0 ||
			(document.cookie = "test").indexOf.call(document.cookie, "test") > -1));

		if( ! cookie_checker ){
			$('#alert-bar').html('<?php echo addslashes( $message ); ?>').show();
		}

		$('#alert-bar').click(function(){
			$(this).slideUp();
		});
	});
</script>

<?php
/* End of file cookie_checker.php */
/* Location: /application/views/dynamic_js/cookie_checker.php */ 