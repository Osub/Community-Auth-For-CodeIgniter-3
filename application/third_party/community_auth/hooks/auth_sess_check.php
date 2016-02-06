<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Auth Session Regeneration Check
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2016, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

function auth_sess_check(){

	$CI =& get_instance();

	// Check if no call to auth verification or requirement methods
	if( $CI->authentication->post_system_sess_check )
	{
		// Check if the session was regenerated
		if( ! is_null( $CI->session->regenerated_session_id ) )
		{
			// Verify login, which will update the session ID in user record
			$CI->authentication->check_login( 1 );
		}
	}

}

/* End of file auth_sess_check.php */
/* Location: /community_auth/hooks/auth_sess_check.php */