<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Optional Login Example View
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

<h1>Optional Login</h1>

<?php

if( isset( $auth_role ) )
{
	echo '
			<div class="feedback confirmation">
				<p class="feedback_header">
					Congratulations! You are logged in.
				</p>
			</div>
		';
}
else if( ! isset( $on_hold_message ) && ! isset( $login_error_mesg ) )
{
	echo '
			<div class="feedback reminder">
				<p class="feedback_header">
					Login Not Required
				</p>
				<p style="margin:.4em 0 0 0;">
					Login is not required, however, special content is available for logged in users.
				</p>
			</div>
		';
}

if( isset( $login_form ) )
{
	echo $login_form;
}

/* End of file optional_login_example.php */
/* Location: /application/views/optional_login_example.php */ 