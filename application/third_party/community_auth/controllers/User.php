<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - User Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class User extends MY_Controller {

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
	 * This login method only serves to redirect a user to a 
	 * location once they have successfully logged in. It does
	 * not attempt to confirm that the user has permission to 
	 * be on the page they are being redirected to.
	 */
	public function login()
	{
		// Method should not be directly accessible
		if( $this->uri->uri_string() == 'user/login')
		{
			show_404();
		}

		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' )
		{
			$this->require_min_level(1);
		}

		$this->setup_login_form();

		$this->load->view( 'auth/login_form' );
	}

	// --------------------------------------------------------------

	/**
	 * Log out
	 */
	public function logout()
	{
		$this->authentication->logout();

		redirect( secure_site_url( LOGIN_PAGE . '?logout=1') );
	}

	// --------------------------------------------------------------

	/**
	 * User recovery form
	 */
	public function recover()
	{
		// Load resources
		$this->load->model('user_model');

		/// If IP or posted email is on hold, display message
		if( $on_hold = $this->authentication->current_hold_status( TRUE ) )
		{
			$view_data['disabled'] = 1;
		}
		else
		{
			// If the form post looks good
			if( $this->tokens->match && $this->input->post('user_email') )
			{
				if( $user_data = $this->user_model->get_recovery_data( $this->input->post('user_email') ) )
				{
					// Check if user is banned
					if( $user_data->user_banned == '1' )
					{
						// Log an error if banned
						$this->authentication->log_error( $this->input->post('user_email', TRUE ) );

						// Show special message for banned user
						$view_data['user_banned'] = 1;
					}
					else
					{
						/**
						 * Use the password generator to create a random string
						 * that will be hashed and stored as the password recovery key.
						 */
						$this->load->library('generate_password');
						$recovery_code = $this->generate_password->set_options( 
							array( 'exclude' => array( 'char' ) ) 
						)->random_string(64)->show();

						$hashed_recovery_code = $this->_hash_recovery_code( $user_data->user_salt, $recovery_code );

						// Update user record with recovery code and time
						$this->user_model->update_user_raw_data(
							$user_data->user_id,
							array(
								'passwd_recovery_code' => $hashed_recovery_code,
								'passwd_recovery_date' => time()
							)
						);

						$view_data['special_link'] = secure_anchor( 
							'user/recovery_verification/' . $user_data->user_id . '/' . $recovery_code, 
							secure_base_url() . 'user/recovery_verification/' . $user_data->user_id . '/' . $recovery_code, 
							'target ="_blank"' 
						);

						$view_data['confirmation'] = 1;
					}
				}

				// There was no match, log an error, and display a message
				else
				{
					// Log the error
					$this->authentication->log_error( $this->input->post('user_email', TRUE ) );

					$view_data['no_match'] = 1;
				}
			}
		}

		$this->load->view( 'user/recover_form', ( isset( $view_data ) ) ? $view_data : '' );
	}

	// --------------------------------------------------------------

	/**
	 * Verification of a user by email for recovery
	 * 
	 * @param  int     the user ID
	 * @param  string  the passwd recovery code
	 */
	public function recovery_verification( $user_id = '', $recovery_code = '' )
	{
		/// If IP is on hold, display message
		if( $on_hold = $this->authentication->current_hold_status( TRUE ) )
		{
			$view_data['disabled'] = 1;
		}
		else
		{
			// Load resources
			$this->load->model('user_model');

			if( 
				/**
				 * Make sure that $user_id is a number and less 
				 * than or equal to 10 characters long
				 */
				is_numeric( $user_id ) && strlen( $user_id ) <= 10 &&

				/**
				 * Make sure that $recovery code is exactly 64 characters long
				 */
				strlen( $recovery_code ) == 64 &&

				/**
				 * Try to get a hashed password recovery 
				 * code and user salt for the user.
				 */
				$recovery_data = $this->user_model->get_recovery_verification_data( $user_id ) )
			{
				/**
				 * Check that the recovery code from the 
				 * email matches the hashed recovery code.
				 */
				if( $recovery_data->passwd_recovery_code == $this->_hash_recovery_code( $recovery_data->user_salt, $recovery_code ) )
				{
					$view_data['user_id']       = $user_id;
					$view_data['user_name']     = $recovery_data->user_name;
					$view_data['recovery_code'] = $recovery_data->passwd_recovery_code;
				}

				// Link is bad so show message
				else
				{
					$view_data['recovery_error'] = 1;

					// Log an error
					$this->authentication->log_error('');
				}
			}

			// Link is bad so show message
			else
			{
				$view_data['recovery_error'] = 1;

				// Log an error
				$this->authentication->log_error('');
			}

			/**
			 * If form submission is attempting to change password 
			 * verify that the user_name was good, because there will only
			 * be a user_name if everything else was good.
			 */
			if( 
				$this->tokens->match && 
				isset( $view_data['user_name'] ) && 
				$view_data['user_name'] !== FALSE 
			)
			{
				$this->user_model->recovery_password_change();
			}
		}

		$this->load->view( 'user/choose_password_form', $view_data );
	}

	// --------------------------------------------------------------

	/**
	 * Hash the password recovery code (uses the authentication library's hash_passwd method)
	 */
	private function _hash_recovery_code( $user_salt, $recovery_code )
	{
		return $this->authentication->hash_passwd( $recovery_code, $user_salt );
	}

	// --------------------------------------------------------------

}

/* End of file users.php */
/* Location: /application/controllers/users.php */