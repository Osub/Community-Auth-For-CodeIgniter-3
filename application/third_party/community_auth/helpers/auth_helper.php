<?php  
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Community Auth - Auth Helper
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2016, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

// ------------------------------------------------------------------------

/**
 * Allows to check role just about anywhere
 *
 * @param string The role or comma delimited string of roles
 * @return bool 
 */
if( ! function_exists('is_role') )
{
	function is_role( $role = '' )
	{
		$CI =& get_instance();

		$auth_model = $CI->authentication->auth_model;

		return $CI->$auth_model->is_role( $role );
	}
}

// ------------------------------------------------------------------------

/**
 * Allows to check ACL permissions just about anywhere
 *
 * @param string The category name and action code to check for the logged in user.
 *               This string is joined with a period.
 * @return bool 
 */
if( ! function_exists('acl_permits') )
{
	function acl_permits( $str )
	{
		$CI =& get_instance();

		$auth_model = $CI->authentication->auth_model;

		return $CI->$auth_model->acl_permits( $str );
	}
}

// ------------------------------------------------------------------------

/* End of file auth_helper.php */
/* Location: /community_auth/helpers/auth_helper.php */