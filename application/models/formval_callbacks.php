<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Formval_callbacks Model
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Formval_callbacks extends CI_Model {

	/**
	 * Check the supplied password strength
	 * 
	 * @param   string  the supplied password 
	 * @param   bool    whether or not this is a required field
	 * @return  mixed   bool or the password
	 */
	public function _check_password_strength( $password, $argument )
	{
		// (?=.{' . MIN_CHARS_4_PASSWORD . ',}) means string should be at least length specified in site definitions hook

		// (?=.*\d) means string should have at least one digit

		// (?=.*[a-z]) means string should have at least one lower case letter

		// (?=.*[A-Z]) means string should have at least one upper case letter

		// (?!.*\s) means no space, tab, or other whitespace chars allowed

		// (?!.*[\\\\\'"]) means no backslash, apostrophe or quote chars are allowed

		// (?=.*[@#$%^&+=]) means there has to be at least one of these characters in the password @ # $ % ^ & + =

		if( $argument[0] === 'FALSE' && empty( $password ) )
		{
			// If the password is not required, and if it is empty, no reason to proceed
			return TRUE;
		}
		else if( preg_match( '/^(?=.{' . MIN_CHARS_4_PASSWORD . ',' . MAX_CHARS_4_PASSWORD . '})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s)(?!.*[\\\\\'"]).*$/', $password, $matches ) )
		{
			return $password;
		}
		else
		{
			$this->form_validation->set_message(
				'external_callbacks', 
				'<span class="redfield">%s</span> must contain:
					<ol>
						<li>At least ' . MIN_CHARS_4_PASSWORD . ' characters</li>
						<li>Not more than ' . MAX_CHARS_4_PASSWORD . ' characters</li>
						<li>One number</li><li>One lower case letter</li>
						<li>One upper case letter</li>
						<li>No space characters</li>
						<li>No backslash, apostrophe or quote characters</li>
					</ol>
				</span>'
			);

			return FALSE;
		}
	}

	// --------------------------------------------------------------

	/**
	 * Make sure an email address is not
	 * already in use by any user during 
	 * user creation or registration.
	 * 
	 * @param   string  the supplied email address
	 * @return  mixed   either the email address or FALSE
	 */
	public function _email_exists_check( $email )
	{
		$query = $this->db->get_where( 
			config_item('user_table'), 
			array( 
				'user_email' => $email 
			) 
		);

		if ($query->num_rows() > 0)
		{ 
			//if user email already exists
			$this->form_validation->set_message(
				'external_callbacks', 
				'Supplied <span class="redfield">%s</span> already exists.'
			);

			return FALSE;
		}

		return $email;
	}

	// --------------------------------------------------------------

	/**
	 * Make sure an email address is not
	 * already in use by another user during account update.
	 * 
	 * @param   string  the supplied email address
	 * @return  mixed   either the email address or FALSE
	 */
	public function _update_email( $email, $argument )
	{
		/**
		 * If a self update, the user ID comes from the logged in user's auth data,
		 * but if a user update, the user ID comes from the 3rd URI segment.
		 */
		$user_id = ( $argument[0] == 'update_user' ) ? $this->uri->segment(3) : config_item('auth_user_id');

		$query = $this->db->get_where( 
			config_item('user_table'), 
			array( 
				'user_email' => $email, 
				'user_id !=' => $user_id 
			) 
		);

		if ($query->num_rows() > 0)
		{ 
			//if user email already exists
			$this->form_validation->set_message(
				'external_callbacks', 
				'Supplied <span class="redfield">%s</span> already exists.'
			);

			return FALSE;
		}

		return $email;
	}

	// --------------------------------------------------------------

	/**
	 * Make sure a username does not already exist during user creation.
	 * 
	 * @param   string  the supplied username
	 * @return  mixed   either the username or FALSE
	 */
	public function _username_check( $user_name )
	{
		$query = $this->db->get_where( 
			config_item('user_table'), 
			array( 
				'user_name' => $user_name 
			) 
		);

		if ($query->num_rows() > 0)
		{ 
			//if user name already exists
			$this->form_validation->set_message(
				'external_callbacks', 
				'Supplied <span class="redfield">%s</span> already exists.'
			);

			return FALSE;
		}

		return $user_name;
	}

	// --------------------------------------------------------------

	/**
	 * Make sure that the user level of an account being created or updated is 
	 * not maliciously increased to give the account a higher ranking account.
	 * 
	 * @param   int  the new account level 
	 * @return  bool
	 */
	public function _stop_level_up( $ulevel )
	{
		// If this is anyone updating an account lesser than their account level
		if( $ulevel < config_item('auth_level') )
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('external_callbacks', 'Admin notified of "Level Up" attempt');

			// Email the admin
			$this->load->library('email');
			$this->config->load('email');
			$admin_email_config = config_item('admin_email_config');

			$this->email->quick_email( array(
				'subject'        => WEBSITE_NAME . ' - Level Up Warning - ' . date("M j, Y"),
				'email_template' => 'email_templates/level-up-warning',
				'from_name'      => 'admin_email_config',
				'to'             => $admin_email_config['from_email']
			) );

			return FALSE;
		}
	}

	// --------------------------------------------------------------

}

/* End of file formval_callbacks.php */
/* Location: /application/models/formval_callbacks.php */