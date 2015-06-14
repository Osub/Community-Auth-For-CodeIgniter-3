<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Authentication Library
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Authentication
{
	/**
	 * The CodeIgniter super object
	 *
	 * @var object
	 * @access public
	 */
	public $CI;

	/**
	 * An array of all user roles where key is 
	 * level (int) and value is the role name (string)
	 *
	 * @var array
	 * @access public
	 */
	public $roles;

	/**
	 * An array of all user levels where key is 
	 * role name (string) and value is level (int)
	 *
	 * @var array
	 * @access public
	 */
	public $levels;

	/**
	 * The status of a login attempt
	 *
	 * @var bool
	 * @access public
	 */
	public $login_error = FALSE;

	/**
	 * The hold status for the IP, posted username, or posted email address
	 *
	 * @var bool
	 * @access public
	 */
	public $on_hold = FALSE;

	/**
	 * A large number holding the user ID, last login time, 
	 * and last modified time for a logged-in user
	 *
	 * @var int
	 * @access private
	 */
	private $auth_identifier;

	// --------------------------------------------------------------

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		$this->CI =& get_instance();

		// Make roles available by user_level (int) => role name (string)
		$this->roles = config_item('levels_and_roles');

		// Make levels available by role name (string) => user_level (int)
		$this->levels = array_flip( $this->roles );

		// Get the auth identifier from the session if it exists
		$this->auth_identifier = $this->CI->session->userdata('auth_identifier');
	}

	// --------------------------------------------------------------

	/**
	 * Handle a login attempt, or determine if user already logged in.
	 * 
	 * @param   mixed  a user level (int) or an array of user roles
	 * @return  mixed  either an object containing the user's data or FALSE
	 */
	public function user_status( $requirement )
	{
		$string     = $this->CI->input->post('login_string');
		$password   = $this->CI->input->post('login_pass');
		$form_token = $this->CI->input->post('login_token');
		$token_jar  = $this->CI->tokens->jar;

		// If the request resembles a login attempt in any way
		if(
			$string     !== FALSE OR 
			$password   !== FALSE OR 
			$form_token !== FALSE
		)
		{
			// Log as long as error logging threshold allows for debugging
			log_message(
				'debug',
				"\n string     = " . $string .
				"\n password   = " . $password .
				"\n form_token = " . $form_token .
				"\n token_jar  = " . json_encode( $token_jar )
			);
		}

		// Check to see if a user is already logged in
		if( $this->auth_identifier )
		{
			// Check login, and return user's data or FALSE if not logged in
			if( $auth_data = $this->check_login( $requirement ) )
			{
				return $auth_data;
			}
		}

		// If this is a login attempt, all values must not be empty
		else if( 
			$string     !== FALSE && 
			$password   !== FALSE && 
			$form_token !== FALSE && 
			! empty( $token_jar ) && 
			$this->_login_page_is_allowed()
		)
		{
			// Verify that the form token and flash session token are the same
			if( $this->CI->tokens->token_check( 'login_token', TRUE ) )
			{
				// Must make form processing at the destination does not think it posted to.
				$this->CI->tokens->match = FALSE;

				// Make sure that the token name is changed, in case there is another form.
				$this->CI->tokens->name = config_item('token_name');

				// Attempt login with posted values and return either the user's data, or FALSE
				if( $auth_data = $this->login( $requirement, $string, $password ) )
				{
					return $auth_data;
				}
			}
		}

		/**
		 * If a login string and password were posted, and the form token 
		 * and flash token were not set, then we treat this as a failed login
		 * attempt.
		 */
		else if(
			$string      !== FALSE && 
			$password    !== FALSE
		)
		{
			// Log the error
			$this->log_error( $this->CI->security->xss_clean( $string ) );

			$this->login_error = TRUE;
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Test post of login form 
	 * 
	 * @param   mixed  a user level (int) or an array of user roles
	 * @param   string  the posted username or email address
	 * @param   string  the posted password
	 * @return  mixed  either an object containing the user's data or FALSE
	 */
	private function login( $requirement, $user_string, $user_pass )
	{
		/**
		 * Validate the posted username / email address and password.
		 */
		$this->CI->load->library('form_validation');
		$this->CI->config->load( 'form_validation/auth/login' );
		$this->CI->form_validation->set_rules( config_item('login_rules') );

		if( $this->CI->form_validation->run() !== FALSE )
		{
			// Check if IP, username or email address is already on hold.
			$this->on_hold = $this->current_hold_status();

			if( ! $this->on_hold )
			{
				// Get user table data if username or email address matches a record
				if( $auth_data = $this->CI->auth_model->get_auth_data( $user_string ) )
				{
					// Confirm user
					if( ! $this->_user_confirmed( $auth_data, $requirement, $user_pass ) )
					{
						// Login failed ...
						log_message(
							'debug',
							"\n user is banned             = " . ( $auth_data->user_banned === 1 ? 'yes' : 'no' ) .
							"\n password in database       = " . $auth_data->user_pass .
							"\n posted/hashed password     = " . $this->hash_passwd( $user_pass, $auth_data->user_salt ) . 
							"\n required level or role     = " . ( is_array( $requirement ) ? implode( $requirement ) : $requirement ) . 
							"\n user level in database     = " . $auth_data->user_level . 
							"\n user level equivalant role = " . $this->roles[$auth_data->user_level]
						);
					}
					else
					{
						// Set session cookie and HTTP user data delete_cookie
						$this->_maintain_state( $auth_data );

						// Send the auth data back to the controller
						return $auth_data;
					}
				}
				else
				{
					// Login failed ...
					log_message(
						'debug',
						"\n NO MATCH FOR USERNAME OR EMAIL DURING LOGIN ATTEMPT"
					);
				}
			}
			else
			{
				// Login failed ...
				log_message(
					'debug',
					"\n IP, USERNAME, OR EMAIL ADDRESS ON HOLD"
				);
			}
		}
		else
		{
			// Login failed ...
			log_message(
				'debug',
				"\n LOGIN ATTEMPT DID NOT PASS FORM VALIDATION"
			);
		}

		// Log the error
		$this->log_error( $this->CI->security->xss_clean( $user_string ) );

		$this->login_error = TRUE;
		
		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Verify if user already logged in. 
	 * 
	 * @param   mixed  a user level (int) or an array of user roles
	 * @return  mixed  either an object containing the user's data or FALSE
	 */
	public function check_login( $requirement )
	{
		// Check that the auth identifier is not empty
		if( ! $this->auth_identifier )
		{
			return FALSE;
		}

		// Get the last user modification time from the session
		$user_last_mod = $this->expose_user_last_mod( $this->auth_identifier );

		// Get the user ID from the session
		$user_id = $this->expose_user_id( $this->auth_identifier );

		// Get the last login time from the session
		$login_time = $this->expose_login_time( $this->auth_identifier );

		/*
		 * Check database for matching user record:
		 * 1) last user modification time matches
		 * 2) user ID matches
		 * 3) login time matches ( not applicable if multiple logins allowed )
		 */
		$auth_data = $this->CI->auth_model->check_login_status( $user_last_mod, $user_id, $login_time );

		// If the query produced a match
		if( $auth_data !== FALSE )
		{
			// Confirm user
			if( ! $this->_user_confirmed( $auth_data, $requirement ) )
			{
				// Logged in check failed ...
				log_message(
					'debug',
					"\n user is banned                  = " . ( $auth_data->user_banned === 1 ? 'yes' : 'no' ) .
					"\n disallowed multiple logins      = " . ( config_item('disallow_multiple_logins') ? 'true' : 'false' ) .
					"\n hashed user agent               = " . md5( $this->CI->input->user_agent() ) . 
					"\n user agent from database        = " . $auth_data->user_agent_string . 
					"\n required level or role          = " . ( is_array( $requirement ) ? implode( $requirement ) : $requirement ) . 
					"\n user level in database          = " . $auth_data->user_level . 
					"\n user level in database (string) = " . $this->roles[$auth_data->user_level]
				);
			}
			else
			{
				// If session ID was regenerated, we need to update the user record
				$this->CI->auth_model->update_user_session_id( $auth_data->user_id );

				// Send the auth data back to the controller
				return $auth_data;
			}
		}
		else
		{
			// Auth Data === FALSE because no user matching in DB ...
			log_message(
				'debug',
				"\n last user modification time from session = " . $user_last_mod . 
				"\n user id from session                     = " . $user_id . 
				"\n last login time from session             = " . $login_time . 
				"\n disallowed multiple logins               = " . ( config_item('disallow_multiple_logins') ? 'true' : 'false' )
			);
		}

		// Unset session
		$this->CI->session->unset_userdata('auth_identifier');

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Gets the hold status for the user's IP,
	 * posted username or posted email address
	 * Post variable for email address is different 
	 * for login vs recovery, hence the lone bool parameter.
	 * 
	 * @param   bool   if check is from recovery (FALSE if from login)
	 * @return  bool
	 */
	public function current_hold_status( $recovery = FALSE )
	{
		// Clear holds that have expired
		$this->CI->auth_model->clear_expired_holds();

		// Check to see if the IP or posted username/email-address is now on hold
		return $this->CI->auth_model->check_holds( $recovery );
	}

	// --------------------------------------------------------------

	/**
	 * Create the auth identifier, which contains 
	 * the user ID and last modification time.
	 * 
	 * @param   int  the user ID 
	 * @param   int  an epoch time that the user account was last modified
	 * @return  int  the auth identifier
	 */
	public function create_auth_identifier( $user_id, $user_modified, $login_time )
	{
		$umod_split = str_split( $user_modified , 5 );

		$login_time_split = str_split( $login_time , 5 );

		return $login_time_split[0] .
			rand(0,9) .
			$umod_split[1] .
			rand(0,9) .
			$user_id .
			rand(0,9) .
			$umod_split[0] .
			rand(0,9) .
			rand(0,9) .
			$login_time_split[1];
	}

	// --------------------------------------------------------------

	/**
	 * Reveal the user ID hiding within the auth identifier
	 * 
	 * @param   int  the auth identifier
	 * @return  int  the user ID
	 */
	public function expose_user_id( $auth_identifier )
	{
		$temp = substr( $auth_identifier , 12 );

		return substr_replace( $temp , '' , -13 );
	}

	// --------------------------------------------------------------

	/**
	 * Reveal the last modification time hiding within the auth identifier
	 * 
	 * @param   int  the auth identifier
	 * @return  int  the user's last modified data
	 */
	public function expose_user_last_mod( $auth_identifier )
	{
		return substr( $auth_identifier , -12 , 5 ) . substr( $auth_identifier , 6 , 5 );
	}

	// --------------------------------------------------------------

	/**
	 * Reveal the login time hiding within the auth identifier
	 * 
	 * @param   int  the auth identifier
	 * @return  int  the user's last login time
	 */
	public function expose_login_time( $auth_identifier )
	{
		return substr( $auth_identifier , 0 , 5 ) . substr( $auth_identifier , -5 , 5 );
	}

	// --------------------------------------------------------------

	/**
	 * Insert details of failed login attempt into database
	 * 
	 * @param   string  the username or email address used to attempt login
	 * @return  void
	 */
	public function log_error( $string )
	{
		// Clear up any expired rows in the login errors table
		$this->CI->auth_model->clear_login_errors();

		// Insert the error
		$data = array(
			'username_or_email' => $string,
			'IP_address'        => $this->CI->input->ip_address(),
			'time'              => time()
		);

		$this->CI->auth_model->create_login_error( $data );

		$this->CI->auth_model->check_login_attempts( $string );
	}

	// --------------------------------------------------------------

	/**
	 * Log the user out
	 */
	public function logout()
	{
		// Get the user ID from the session
		$user_id = $this->expose_user_id( $this->auth_identifier );

		// Delete last login time from user record
		$this->CI->auth_model->logout( $user_id );

		if( config_item('delete_session_cookie_on_logout') )
		{
			// Completely delete the session cookie
			delete_cookie( config_item('sess_cookie_name') );
		}
		else
		{
			// Unset auth identifier
			$this->CI->session->unset_userdata('auth_identifier');
		}

		$this->CI->load->helper('cookie');

		// Delete remember me cookie
		delete_cookie( config_item('remember_me_cookie_name') );

		// Delete the http user cookie
		delete_cookie( config_item('http_user_cookie_name') );

		// Delete the https tokens cookie
		delete_cookie( config_item('https_tokens_name') );
	}

	// --------------------------------------------------------------

	/**
	 * Hash Password
	 *
	 * @param   string  The raw (supplied) password
	 * @param   string  The random salt
	 * @return  string  the hashed password
	 */
	public function hash_passwd( $password, $random_salt )
	{
		return crypt( $password . config_item('encryption_key'), '$2a$09$' . $random_salt . '$' );
	}

	// --------------------------------------------------------------

	/**
	 * Check Password
	 *
	 * @param   string  The hashed password 
	 * @param   string  The random salt
	 * @param   string  The raw (supplied) password
	 * @return  bool
	 */
	public function check_passwd( $hash, $random_salt, $password )
	{
		if( $hash === $this->hash_passwd( $password, $random_salt ) )
		{
			return TRUE;
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Make Random Salt
	 */
	public function random_salt()
	{
		return md5( mt_rand() );
	}

	// --------------------------------------------------------------
	
	/**
	 * Confirm the User During Login Attempt or Status Check
	 *
	 * 1) Is the user banned?
	 * 2) If a login attempt, does the password match the one in the user record?
	 * 3) If a status check, does the user agent match when multiple logins disallowed?
	 * 4) Is the user the appropriate level for the request?
	 * 5) Is the user the appropriate role for the request?
	 *
	 * @param   obj    the user record
	 * @param   mixed  the required user level or array of roles
	 * @param   mixed  the posted password during a login attempt
	 * @return  bool
	 */
	private function _user_confirmed( $auth_data, $requirement, $user_pass = FALSE )
	{
		// Check if user is banned
		$is_banned = ( $auth_data->user_banned === '1' );

		// Is this a login attempt
		if( $user_pass )
		{
			// Check if the posted password matches the one in the user record
			$wrong_password = ( ! $this->check_passwd( $auth_data->user_pass, $auth_data->user_salt, $user_pass ) );

			// Check for disallowed multiple logins doesn't apply to login attempt
			$disallowed_multiple_login = FALSE;
		}

		// Else we are checking login status
		else
		{
			// Password check doesn't apply to a login status check
			$wrong_password = FALSE;

			// If multiple logins are not allowed, check if user agent string matches
			$disallowed_multiple_login = ( config_item('disallow_multiple_logins') && md5( $this->CI->input->user_agent() ) != $auth_data->user_agent_string );
		}

		// Check if the user has the appropriate user level
		$wrong_level = ( is_int( $requirement ) && $auth_data->user_level < $requirement );

		// Check if the user has the appropriate role
		$wrong_role = ( is_array( $requirement ) && ! in_array( $this->roles[$auth_data->user_level], $requirement ) );

		// If anything wrong
		if( $is_banned OR $wrong_level OR $wrong_role OR $wrong_password OR $disallowed_multiple_login )
		{
			return FALSE;
		}

		return TRUE;
	}
	
	// ---------------------------------------------------------------
	
	/**
	 * Setup session, HTTP user cookie, and remember me cookie 
	 * during a successful login attempt. Redirect is specified here.
	 *
	 * @param   obj  the user record
	 * @return  void
	 */
	private function _maintain_state( $auth_data )
	{
		// Redirect to specified page, or home page if none provided
		$redirect = $this->CI->input->get('redirect')
			? urldecode( $this->CI->input->get('redirect') ) 
			: '';

		$url = USE_SSL === 1 
			? secure_site_url( $redirect ) 
			: site_url( $redirect );

		header( "Location: " . $url, TRUE, 302 );

		// Store login time in database and cookie
		$login_time = time();

		/**
		 * Since the session cookie needs to be able to use
		 * the secure flag, we want to hold some of the user's 
		 * data in another cookie.
		 */
		$http_user_cookie = array(
			'name'   => config_item('http_user_cookie_name'),
			'domain' => config_item('cookie_domain'),
			'path'   => config_item('cookie_path'),
			'prefix' => config_item('cookie_prefix'),
			'secure' => FALSE
		);

		// Initialize the HTTP user cookie data
		$http_user_cookie_data['_user_name'] = $auth_data->user_name;

		// Serialize the HTTP user cookie data
		$http_user_cookie['value'] = serialize_data( $http_user_cookie_data );

		// Check if remember me requested, and set cookie if yes
		if( config_item('allow_remember_me') && $this->CI->input->post('remember_me') )
		{
			$remember_me_cookie = array(
				'name'   => config_item('remember_me_cookie_name'),
				'value'  => config_item('remember_me_expiration') + time(),
				'expire' => config_item('remember_me_expiration'),
				'domain' => config_item('cookie_domain'),
				'path'   => config_item('cookie_path'),
				'prefix' => config_item('cookie_prefix'),
				'secure' => FALSE
			);

			$this->CI->input->set_cookie( $remember_me_cookie );

			// Make sure the CI session cookie doesn't expire on close
			$this->CI->session->sess_expire_on_close = FALSE;
			$this->CI->session->sess_expiration = config_item('remember_me_expiration');

			// Set the expiration of the http user cookie
			$http_user_cookie['expire'] = config_item('remember_me_expiration') + time();
		}
		else
		{
			// Unless remember me is requested, the http user cookie expires when the browser closes.
			$http_user_cookie['expire'] = 0;
		}

		$this->CI->input->set_cookie( $http_user_cookie );

		// Set CI session cookie
		$this->CI->session->set_userdata( 
			'auth_identifier',
			$this->create_auth_identifier(
				$auth_data->user_id,
				$auth_data->user_modified,
				$login_time
			)
		);

		// For security, force regenerate the session ID
		$session_id = $this->CI->session->sess_regenerate( TRUE );

		// Update user record in database
		$this->CI->auth_model->login_update( $auth_data->user_id, $login_time, $session_id );
	}
	
	// -----------------------------------------------------------------------

	/**
	 * Just make sure that the login is not on any page
	 * except for the ones we define in authentication config.
	 */
	private function _login_page_is_allowed()
	{
		// Get the current URI string
		$uri_string = $this->CI->uri->uri_string();

		// Get all of the allowed login pages
		$allowed_pages = config_item('allowed_pages_for_login');

		// Add LOGIN_PAGE to the allowed login pages
		$allowed_pages[] = LOGIN_PAGE;

		// If there is a match for the URI string, all is well
		if( in_array( $uri_string, $allowed_pages ) )
		{
			return TRUE;
		}

		// No match for URI string, so log it
		log_message(
			'debug',
			"\n URI STRING FROM LOGIN = " . $uri_string
		);

		return FALSE;
	}
	
	// -----------------------------------------------------------------------
}

/* End of file Authentication.php */
/* Location: /application/libraries/Authentication.php */ 