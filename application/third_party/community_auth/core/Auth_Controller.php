<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Auth Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Auth_Controller extends CI_Controller {

	/**
	 * The logged-in user's user ID
	 *
	 * @var string
	 * @access public
	 */
	public $auth_id;

	/**
	 * The logged-in user's username
	 *
	 * @var string
	 * @access public
	 */
	public $auth_user_name;

	/**
	 * The logged-in user's authentication account type by number
	 *
	 * @var string
	 * @access public
	 */
	public $auth_level;

	/**
	 * The logged-in user's authentication account type by name
	 *
	 * @var string
	 * @access public
	 */
	public $auth_role;

	/**
	 * The logged-in user's authentication data,
	 * which is their user table record, but could
	 * be whatever you want it to be if you modify 
	 * the queries in the auth model.
	 *
	 * @var object
	 * @access private
	 */
	private $auth_data;

	/**
	 * Either 'https' or 'http' depending on the current environment
	 *
	 * @var string
	 * @access public
	 */
	public $protocol = 'http';

	// --------------------------------------------------------------
	
	public function __construct()
	{
		parent::__construct();

		/**
		 * Set no-cache headers so pages are never cached by the browser.
		 * This is necessary because if the browser caches a page, the 
		 * login or logout link and user specific data may not change when 
		 * the logged in status changes.
		 */
	 	header('Expires: Wed, 13 Dec 1972 18:37:00 GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		header('Pragma: no-cache');

		/**
		 * By setting the protocol here, we don't have to test for it 
		 * everytime we want to know if we are in a secure environment or not.
		 */
		if( ! empty( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) !== 'off' )
		{
			$this->protocol = 'https';
		}

		/**
		 * If the http user cookie is set, make user data available in views
		 */
		if( get_cookie( config_item('http_user_cookie_name') ) )
		{
			$http_user_data = unserialize_data( get_cookie( config_item('http_user_cookie_name') ) );

			$this->load->vars( $http_user_data );
		}

		//$this->output->enable_profiler();
	}

	// --------------------------------------------------------------

	/**
	 * Require a login by user of account type specified numerically.
	 * User assumes your priveledges are linear in relationship to account types.
	 * 
	 * @param  int    the minimum level of user required
	 * @param  mixed  either returns TRUE or doesn't return
	 */
	protected function require_min_level( $level )
	{
		// Check if logged in or if login attempt
		if( $this->auth_data = $this->authentication->user_status( $level ) )
		{
			$this->_set_user_variables();

			return TRUE;
		}

		// Else check if we need to redirect to the login page
		else if( $this->uri->uri_string() != LOGIN_PAGE )
		{
			$this->_redirect_to_login_page();
		}

		// Else this is a failed login attempt or the login page was loaded
		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Require a login by role in a specific group
	 * or groups, specified by group name(s).
	 * 
	 * @param  string  a group name or names as a comma separated string.
	 */
	protected function require_group( $group_names )
	{
		// Get all groups from config
		$groups = config_item('groups');

		// Get group(s) allowed to login
		$group_array = explode( ',', $group_names );

		// Trim off any space chars
		$group_array = array_map( 'trim', $group_array );

		// Initialize array of roles allowed to login
		$roles = array();

		// Add group members to roles array
		foreach( $group_array as $group )
		{
			// Turn group members into an array
			$temp_arr = explode( ',', $groups[$group] );

			// Merge array of group members with roles array
			$roles = array_merge( $roles, $temp_arr );
		}

		// Turn the array of roles into a comma seperated string
		$roles_string = implode( ',', $roles );

		// Try to login via require_role method
		return $this->require_role( $roles_string );
	}

	// --------------------------------------------------------------

	/**
	 * Require a login by user of a specific account type, specified by name(s).
	 * 
	 * @param  string  a comma seperated string of account types that are allowed.
	 * @param  mixed  either returns TRUE or doesn't return
	 */
	protected function require_role( $roles )
	{
		// Turn the roles string into an array or roles
		$role_array = explode( ',', $roles );

		// Trim off any space chars
		$role_array = array_map( 'trim', $role_array );

		// Check if logged in or if login attempt
		if( $this->auth_data = $this->authentication->user_status( $role_array ) )
		{
			$this->_set_user_variables();

			return TRUE;
		}
		
		// Else check if we need to redirect to the login page
		else if( $this->uri->uri_string() != LOGIN_PAGE )
		{
			$this->_redirect_to_login_page();
		}

		// Else this is a failed login attempt or the login page was loaded
		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Redirect to the login page
	 */
	private function _redirect_to_login_page()
	{
		// Determine the login redirect
		$redirect = $this->input->get('redirect')
			? urlencode( $this->input->get('redirect') ) 
			: urlencode( $this->uri->uri_string() );

		// Redirect to the login form
		$url = USE_SSL === 1 
			? secure_site_url( LOGIN_PAGE . '?redirect=' . $redirect ) 
			: site_url( LOGIN_PAGE . '?redirect=' . $redirect );

		header(
			'Location: ' . $url,
			TRUE,
			302
		);
	}
	
	// -----------------------------------------------------------------------

	/**
	 * Function used for allowing a login that isn't required. An example would be
	 * a optional login during checkout in an eCommerce application. Login isn't 
	 * mandatory, but useful because a user's account can be accessed.
	 *
	 * @return  mixed  either returns TRUE or doesn't return
	 */
	protected function optional_login()
	{
		if( $this->auth_data = $this->authentication->user_status( 0 ) )
		{
			$this->_set_user_variables();

			return TRUE;
		}
	}

	// --------------------------------------------------------------

	/**
	 * Function is an alias of verify_min_level, but with no arguments.
	 */
	protected function is_logged_in()
	{
		$this->verify_min_level( 0 );
	}

	// --------------------------------------------------------------

	/**
	 * Verify if user logged in by account type specified numerically.
	 * This is for use when login is not required, but beneficial.
	 * 
	 * @param   int    the minimum level of user to be verified.
	 * @return  mixed  either returns TRUE or doesn't return
	 */
	protected function verify_min_level( $level )
	{
		if( $this->auth_data = $this->authentication->check_login( $level ) )
		{
			$this->_set_user_variables();

			return TRUE;
		}
	}

	// --------------------------------------------------------------

	/**
	 * Verify if user logged in by account type specified by name(s).
	 * This is for use when login is not required, but beneficial.
	 * 
	 * @param   string  comma seperated string of account types that to be verified.
	 * @return  mixed   either returns TRUE or doesn't return
	 */
	protected function verify_role( $roles )
	{
		$role_array = explode( ',', $roles );

		if( $this->auth_data = $this->authentication->check_login( $role_array ) )
		{
			$this->_set_user_variables();

			return TRUE;
		}
	}

	// --------------------------------------------------------------

	/**
	 * Set variables related to authentication, for use in views / controllers.
	 */
	private function _set_user_variables()
	{
		// Set user specific variables to be available in controllers
		$this->auth_user_id    = $this->auth_data->user_id;
		$this->auth_user_name  = $this->auth_data->user_name;
		$this->auth_level      = $this->auth_data->user_level;
		$this->auth_role       = $this->authentication->roles[$this->auth_data->user_level];
		$this->auth_email      = $this->auth_data->user_email;

		// Set user specific variables to be available in all views
		$data = array(
			'auth_user_id'    => $this->auth_user_id,
			'auth_user_name'  => $this->auth_user_name,
			'auth_level'      => $this->auth_level,
			'auth_role'       => $this->auth_role,
			'auth_email'      => $this->auth_email
		);

		// Set user specific variables to be available as config items
		$this->config->set_item( 'auth_user_id',    $this->auth_user_id );
		$this->config->set_item( 'auth_user_name',  $this->auth_user_name );
		$this->config->set_item( 'auth_level',      $this->auth_level );
		$this->config->set_item( 'auth_role',       $this->auth_role );
		$this->config->set_item( 'auth_email',      $this->auth_email );

		// Load vars
		$this->load->vars($data);
	}

	// --------------------------------------------------------------

	/**
	 * Show any login error message.
	 */
	protected function setup_login_form()
	{
		$this->tokens->name = 'login_token';

		/**
		 * Check if IP, username, or email address on hold.
		 *
		 * If a malicious form post set the on_hold authentication class 
		 * member to TRUE, there'd be no reason to continue. Keep in mind that 
		 * since an IP address may legitimately change, we shouldn't do anything 
		 * drastic unless this happens more than an acceptable amount of times.
		 * See the 'deny_access_at' config setting in config/authentication.php
		 */
		if( $this->authentication->on_hold === TRUE )
		{
			$view_data['on_hold_message'] = 1;
		}

		// This check for on hold is for normal login attempts
		else if( $on_hold = $this->authentication->current_hold_status() )
		{
			$view_data['on_hold_message'] = 1;
		}

		// Display a login error message if there was a form post
		if( $this->authentication->login_error === TRUE )
		{
			// Display a failed login attempt message
			$view_data['login_error_mesg'] = 1;
		}

		if( isset( $view_data ) )
		{
			$this->load->vars( $view_data );
		}
	}

	// --------------------------------------------------------------

	/**
	 * Checks if logged in user is of a specific account type
	 * 
	 * @param   string  a comma seperated string of account types to check.
	 * @return  bool
	 */
	protected function is_role( $role = '' )
	{
		if( $role != '' && ! empty( $this->auth_role ) )
		{
			$role_array = explode( ',', $role );

			if( in_array( $this->auth_role, $role_array ) )
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Force the request to be redirected to HTTPS, or optionally show 404.
	 * A strong security policy does not allow for redirection.
	 */
	protected function force_ssl()
	{
		// Force SSL if available
		if( USE_SSL !== 0 && $this->protocol == 'http' )
		{
			// Allow redirect to the HTTPS page
			if( config_item('redirect_to_https') !== 0 )
			{
				// Load string helper for trim_slashes function
				$this->load->helper('string');

				// 301 Redirect to the secure page
				header("Location: " . secure_site_url( trim_slashes( $this->uri->uri_string() ) ), TRUE, 301);
			}

			// Show a 404 error
			else
			{
				show_404();
			}

			exit;
		}
	}

	// --------------------------------------------------------------

}