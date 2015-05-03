<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Create User View
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

echo '<h1>' . ( isset( $type ) ? ucfirst( $type ) . ' Creation' : 'User Creation' ) . '</h1>';

if( isset( $validation_passed, $user_created ) )
{
	echo '
		<div class="feedback confirmation">
			<p class="feedback_header">
				The new ' . $type . ' has been successfully created.
			</p>
		</div>
	';
}
else if( isset( $validation_errors ) )
{
	echo '
		<div class="feedback error_message">
			<p class="feedback_header">
				' . ucfirst( $type ) . ' Creation Contained The Following Errors:
			</p>
			<ul>
				' . $validation_errors . '
			</ul>
			<p>
				' . strtoupper( $type ) . ' NOT CREATED
			</p>
		</div>
	';
}

if( isset( $level, $type ) )
{
	echo $user_creation_form;
}
else
{
	echo '
		<p>Please choose a user type to create:</p>
		<ul class="std-list">
	';

	foreach( $roles as $k => $v )
	{
		if( $k < $auth_level )
		{
			echo '<li>' . secure_anchor( 'administration/create_user/' . $v, $v ) . '</li>';
		}
	}

	echo '</ul>';
}

/* End of file create_user.php */
/* Location: /application/views/administration/create_user.php */