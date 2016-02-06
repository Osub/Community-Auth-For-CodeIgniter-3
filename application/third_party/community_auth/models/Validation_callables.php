<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Validation_callables Model
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2016, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Validation_callables extends CI_Model {

	/**
	 * Check the supplied password strength
	 * 
	 * @param   string  the supplied password 
	 * @return  mixed   bool
	 */
	public function _check_password_strength( $password )
	{
		// Password length
		$regex = '(?=.{' . config_item('min_chars_for_password') . ',' . config_item('max_chars_for_password') . '})';
		$error = '<li>At least ' . config_item('min_chars_for_password') . ' characters</li>
					<li>Not more than ' . config_item('max_chars_for_password') . ' characters</li>';

		// At least one digit required
		$regex .= '(?=.*\d)';
		$error .= '<li>One number</li>';

		// At least one lower case letter required
		$regex .= '(?=.*[a-z])';
		$error .= '<li>One lower case letter</li>';

		// At least one upper case letter required
		$regex .= '(?=.*[A-Z])';
		$error .= '<li>One upper case letter</li>';

		// No space, tab, or other whitespace chars allowed
		$regex .= '(?!.*\s)';
		$error .= '<li>No spaces, tabs, or other unseen characters</li>';

		// No backslash, apostrophe or quote chars are allowed
		$regex .= '(?!.*[\\\\\'"])';
		$error .= '<li>No backslash, apostrophe or quote characters</li>';

		// One of the following characters must be in the password,  @ # $ % ^ & + =
		// $regex .= '(?=.*[@#$%^&+=])';
		// $error .= '<li>One of the following characters must be in the password,  @ # $ % ^ & + =</li>';

		if( preg_match( '/^' . $regex . '.*$/', $password, $matches ) )
		{
			return TRUE;
		}
		
		$this->form_validation->set_message(
			'_check_password_strength', 
			'<span class="redfield">Password</span> must contain:
				<ol>
					' . $error . '
				</ol>
			</span>'
		);

		return FALSE;
	}

	// --------------------------------------------------------------

}

/* End of file Validaton_callables.php */
/* Location: /community_auth/models/Validation_callables.php */