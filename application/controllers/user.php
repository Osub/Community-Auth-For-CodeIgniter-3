<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - User Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
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
	 * The main index
	 */
	public function index()
	{
		if( $this->input->get('logout') && config_item('show_login_form_on_logout') == FALSE )
		{
			$data = array(
				'title' => WEBSITE_NAME . ' User Logout Confirmation',
				'content' => $this->load->view( 'auth/logout_confirmation', '', TRUE )
			);

			$this->load->view( $this->template, $data );
		}

		// Check if a user of any level is logged in
		else if( $this->require_min_level(1) )
		{
			$data = array(
				'title' => WEBSITE_NAME . ' User Index',
				'content' => $this->load->view( 'user/user_index', '', TRUE )
			);

			$this->load->view( $this->template, $data );
		}
	}

	// --------------------------------------------------------------

	/**
	 * User recovery form
	 */
	public function recover()
	{
		// Load resources
		$this->load->model('user_model');

		// Ouput alert-bar message if cookies not enabled
		$this->check_cookies_enabled('Cookies are required for username/password recovery. Please enable cookies.');

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

						$this->load->library('email');
						$this->config->load('email');

						$this->email->quick_email( array(
							'subject'        => WEBSITE_NAME . ' - User Account Recovery - ' . date("M j, Y"),
							'email_template' => 'email_templates/user-recovery',
							'from_name'      => 'no_reply_email_config',
							'template_data'  => array( 'user_data' => $user_data, 'recovery_code' => $recovery_code ),
							'to'             => $this->input->post('user_email')
						) );

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

		$data = array(
			'content' => $this->load->view( 'user/recover_form', ( isset( $view_data ) ) ? $view_data : '', TRUE )
		);

		$this->load->view( $this->template, $data );
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

		$data = array(
			'javascripts' => array(
				'js/jquery.passwordToggle-1.1.js'
			),
			'extra_head' => '
				<script>
					$(document).ready(function(){
						$("#show-password").passwordToggle({target:"#user_pass"});
						$("#show-password").passwordToggle({target:"#user_pass_confirm"});
					});
				</script>
			',
			'content' => $this->load->view( 'user/choose_password_form', $view_data, TRUE )
		);

		$this->load->view( $this->template, $data );
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

	/**
	 * Self update
	 */
	public function self_update()
	{
		// Require a logged in user of any level
		if( $this->require_min_level(1) )
		{
			// Load resources
			$this->load->library('encrypt');
			$this->load->model('user_model');
			$this->load->library('upload');

			// Check if an update post was made
			if( $this->tokens->match )
			{
				// Update the user
				$this->user_model->update_user( $this->auth_role, $this->auth_user_id, 'self_update' );
			}

			// Get the user record
			$user_row = $this->user_model->view_user_record( $this->auth_user_id );

			// Determine the role
			$role = $this->authentication->roles[$user_row->user_level];

			// Decrypt any sensitive data for display
			if( isset( $user_row->license_number ) )
			{
				$user_row->license_number = $this->encrypt->decode( $user_row->license_number );
			}

			// Send user data to view
			$view_data['user_data'] = $user_row;

			// Set destination for file storage.
			$view_data['upload_destination'] = config_item('profile_image_destination');

			// Role specific form
			$view_data['role_specific_form'] = $this->load->view( 'user/self_update/self_update_' . $role, $view_data, TRUE );

			$data = array(
				'content' => $this->load->view( 'user/self_update', $view_data, TRUE ),

				// Load the show password script
				'javascripts' => array(
					'js/jquery.passwordToggle-1.1.js',
					'js/jquery.char-limiter-3.0.0.js',
					'js/default-char-limiters.js',
					'js/ajaxupload.js',
					'js/user/self-update.js'
				)
			);

			$this->load->view( $this->template, $data );

		}

	}

	// --------------------------------------------------------------

	/**
	 * Accepts the HTTP request for the deletion of the
	 * user's profile image. Request can be ajax or standard.
	 */
	public function delete_profile_image()
	{
		// Require a logged in user of any level
		if( $this->require_min_level(1) )
		{
			// Load resources
			$this->load->model('user_model');

			// If this is an ajax request
			if( $this->input->is_ajax_request() )
			{
				// If CSRF token match and image deleted
				if( 
					$this->tokens->match && 
					$this->_delete_profile_image()
				)
				{
					// Send success message back
					$response = array(
						'status'        => 'success',
						'token'         => $this->tokens->token(),
						'ci_csrf_token' => $this->security->get_csrf_hash()
					);
				}

				else
				{
					// Token mismatch
					$response = array(
						'status'  => 'error',
						'message' => 'No Token Match - Please Reload Page'
					);
				}

				echo json_encode( $response );
			}

			// Standard request
			else
			{
				$method_response = $this->_delete_profile_image();

				header("Location: " . secure_site_url( 'user/self_update' ) );

				exit;
			}
		}
	}

	// --------------------------------------------------------------

	/**
	 * Delete the profile image from the user's profile,
	 * and the filesystem (if applicable).
	 */
	private function _delete_profile_image()
	{
		if( $model_response = $this->user_model->update_user( 
			$this->auth_role,
			$this->auth_user_id, 
			'profile_image', 
			array(), 
			array( 'profile_image' => '' ) 
		))
		{
			/**
			 * If the profile image is being stored in the filesystem,
			 * we need to delete it, and delete the directory that it was in.
			 */
			$this->config->load('uploads_manager');

			if( config_item('profile_image_destination') == 'filesystem' )
			{
				$this->load->helper('file');

				// Construct a path to the image based on what we know
				$upload_dir    = config_item('upload_dir');
				$secondary_dir = 'profile_images';
				$tertiary_dir  = $this->auth_user_id . '-' . md5( config_item('encryption_key') . $this->auth_user_id );

				$upload_path = FCPATH . $upload_dir . '/' . $secondary_dir . '/' . $tertiary_dir . '/';

				// Delete all profile images for the user
				$files_deleted = delete_files( $upload_path );

				// Remove the user's profile image directory
				return rmdir( $upload_path );
			}

			return TRUE;
		}

		return FALSE;
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

		// Get form from authentication class / log failed login attempt if applicable
		$data = array(
			'title' => WEBSITE_NAME . ' - Login',
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
			'content' => $this->load->view( 'auth/login_form', ( isset( $view_data ) ) ? $view_data : '', TRUE )
		);

		$this->load->view( $this->template, $data );
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

}

/* End of file users.php */
/* Location: /application/controllers/users.php */