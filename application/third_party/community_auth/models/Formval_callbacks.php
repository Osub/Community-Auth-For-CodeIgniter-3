<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Formval_callbacks Model
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
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
		// (?=.{' . config_item('min_chars_for_password') . ',}) means string should be at least length specified in site definitions hook

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
		else if( preg_match( '/^(?=.{' . config_item('min_chars_for_password') . ',' . config_item('max_chars_for_password') . '})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s)(?!.*[\\\\\'"]).*$/', $password, $matches ) )
		{
			return $password;
		}
		else
		{
			$this->form_validation->set_message(
				'external_callbacks', 
				'<span class="redfield">%s</span> must contain:
					<ol>
						<li>At least ' . config_item('min_chars_for_password') . ' characters</li>
						<li>Not more than ' . config_item('max_chars_for_password') . ' characters</li>
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

}

/* End of file formval_callbacks.php */
/* Location: /application/models/formval_callbacks.php */