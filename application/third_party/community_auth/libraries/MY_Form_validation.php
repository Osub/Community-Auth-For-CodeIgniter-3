<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - MY_Form_validation
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2016, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class MY_Form_validation extends CI_Form_validation {

	/**
	 * Access to protected $_error_array
	 */
	public function get_error_array()
	{
		return $this->_error_array;
	}

	// --------------------------------------------------------------

	/**
	 * If there are form validation errors, you can unset them so they
	 * don't display when calling validation_errors().
	 *
	 * @param  string  the field name to unset
	 */
	public function unset_error( $field )
	{
		if( isset( $this->_error_array[$field] ) ) 
			unset( $this->_error_array[$field] );
	}

	// --------------------------------------------------------------

	/**
	 * Unset an element in the protected $_field_data
	 *
	 * @param  mixed  either an array of elements to unset or a string with name of element to unset
	 */
	public function unset_field_data( $element )
	{
		if( is_array( $element ) )
		{
			foreach( $element as $x )
			{
				$this->unset_field_data( $x );
			}
		}
		else
		{
			if( $element == '*' )
			{
				unset( $this->_field_data );
			}
			else
			{
				if( isset( $this->_field_data[$element] ) )
				{
					unset( $this->_field_data[$element] );
				}
			}
		}
	}

	// --------------------------------------------------------------

}

/* End of file MY_Form_validation.php */
/* Location: /application/libraries/MY_Form_validation.php */ 