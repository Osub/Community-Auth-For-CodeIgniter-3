<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Optional Login Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Optional_login extends MY_Controller {

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// Force encrypted connection
		$this->force_ssl();
	}

	// --------------------------------------------------------------

	/**
	 * Show a login form to allow a user to optionally login.
	 * Content on page is different if user is logged in.
	 */
	public function index()
	{
		// Login is optional
		if( $this->optional_login() )
		{
			// Community Auth's standard set of logged in user variables will be available.
		}
		else
		{
			// Check if user already on hold
			if( $this->authentication->on_hold === TRUE )
			{
				$view_data['on_hold_message'] = 1;
			}

			// This check for on hold is for normal login attempts
			else if( $on_hold = $this->authentication->current_hold_status() )
			{
				$view_data['on_hold_message'] = 1;
			}

			// If not on hold, proceed with optional login
			else
			{
				// Set token
				$this->tokens->name = 'login_token';
			}

			// Display a login error message if there was a form post
			if( $this->authentication->login_error === TRUE )
			{
				// Display a failed login attempt message
				$view_data['login_error_mesg'] = 1;
			}

			// Ouput alert-bar message if cookies not enabled
			$this->check_cookies_enabled('Cookies are required for optional login. Please enable cookies if you wish to login.');

			// Turn off the H1 header in the login form view
			$view_data['optional_login'] = TRUE;

			// Use the normal login form as a nested view
			$view_data['login_form'] = $this->load->view('auth/login_form', $view_data, TRUE );
		}

		$data = array(
			'title' => WEBSITE_NAME . ' - Optional Login Example',
			'javascripts' => array(
				'js/jquery.passwordToggle-1.1.js',
				'js/jquery.char-limiter-3.0.0.js',
				'js/default-char-limiters.js'
			),
			'extra_head' => '
				<script>
					$(document).ready(function(){
						$("#show-password").passwordToggle({target:"#login_pass"});
					});
				</script>
			',
			'content' => $this->load->view( 'auth/optional_login_example', ( isset( $view_data ) ) ? $view_data : '', TRUE )
		);

		$this->load->view( $this->template, $data );
	}

	// --------------------------------------------------------------
}

/* End of file optional_login.php */
/* Location: /application/controllers/optional_login.php */