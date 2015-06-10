<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Generate Password Library
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

/**
 * EXAMPLES
 * ----------------------
 *
 * A Random 10 character password:
 * --------------------------------------
 * $this->generate_password->random_string(10)->show();
 *
 *
 * A Password with 5 letters followed by 3 numbers:
 * ------------------------------------------------------
 * $this->generate_password->letter_string(5)
 *                         ->number_string(3)
 *                         ->show();
 *
 *
 * Generate a random password, but use lowercase 
 * letters instead of lowercase and uppercase:
 * -------------------------------------------------
 * $this->generate_password->set_options( array( 'letters' => 'abcdefghjkmnpqrstuvwxyz' ) )
 *                         ->random_string(10)
 *                         ->show();
 *
 *
 * Generate a random password, but exclude special chars:
 * -----------------------------------------------------------
 * $this->generate_password->set_options( array( 'exclude' => array( 'char' ) ) )
 *                         ->random_string(10)
 *                         ->show();
 */

class Generate_password {

	/**
	 * The letters that will be used in password creation
	 *
	 * @var string
	 * @access private
	 */
	private $letters   = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';

	/**
	 * The special chars that will be used in password creation
	 *
	 * @var string
	 * @access private
	 */
	private $chars     = '~#$%^&*+=?';

	/**
	 * Any character types to exclude
	 *
	 * @var array
	 * @access private
	 */
	private $exclude   = array();

	/**
	 * The created password
	 *
	 * @var string
	 * @access private
	 */
	private $password = '';

	// --------------------------------------------------------------

	/**
	 * Set Options
	 *
	 * @param   array   Allows for changing the letters and 
	 *                  characters used in password creation.
	 */
	public function set_options( $config = array() )
	{
		foreach( $config as $key => $val )
		{
			$this->$key = $val;
		}

		// Return object for method chaining
		return $this;
	}

	// --------------------------------------------------------------

	/**
	 * Get a letter or series of letters
	 *
	 * @param   int   The desired length of the string of letters
	 */
	public function letter_string( $length = 5 )
	{
		// Get random letters
		$this->_create_letter_string( $length );

		// Return object for method chaining
		return $this;
	}

	// --------------------------------------------------------------

	/**
	 * Get a number or series of numbers
	 *
	 * @param   int   The desired length of the string of numbers
	 */
	public function number_string( $length = 3 )
	{
		// Get random numbers
		$this->_create_number_string( $length );

		// Return object for method chaining
		return $this;
	}

	// --------------------------------------------------------------

	/**
	 * Get a special character or series of special characters
	 *
	 * @param   int   The desired length of the string of special characters
	 */
	public function char_string( $length = 1 )
	{
		// Get random chars
		$this->_create_char_string( $length );

		// Return object for method chaining
		return $this;
	}

	// --------------------------------------------------------------

	/**
	 * Generate a random set of letters, numbers, and special characters
	 *
	 * @param   int    The desired length of the string
	 */
	public function random_string( $length = 1 )
	{
		// Initialize the types of characters
		$types = array(
			'letter',
			'number',
			'char'
		);

		// Remove characters if present in the $exclude argument
		if( ! empty( $this->exclude ) )
		{
			foreach( $types as $k => $v )
			{
				if( in_array( $v, $this->exclude ) )
				{
					unset( $types[$k] );
				}
			}
		}

		$i = 0;

		while( $i < $length )
		{
			// Pick a random key from the $types array
			$numeric_key = array_rand( $types );

			// Get the value of the chosen key
			$method_prefix = $types[$numeric_key];

			// Combine that value with _string
			$method = $method_prefix . '_string';

			// Run the method for a single character of the password
			$this->$method(1);

			$i++;
		}

		return $this;
	}

	// --------------------------------------------------------------

	/**
	 * Generate a letter or series of letters
	 *
	 * @param   int   The desired length of the string of letters
	 */
	private function _create_letter_string( $length = 5 )
	{
		$pass = '' ;

		$i = 0;
		while( $i < $length )
		{
				// Get a single number between zero and the length of the letters string
				$num = $this->_get_random_number('letters');

				// Get that letter
				$tmp = substr( $this->letters, $num, 1 );

				// Add the character to the password
				$pass = $pass . $tmp;

				$i++;
		}

		// If the length argument was greater than zero
		if( $length > 0 )
		{
			// Return the letter string
			return $this->password .= $pass;
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Generate a number or series of numbers
	 *
	 * @param   int   The desired length of the string of numbers
	 */
	private function _create_number_string( $length = 3 )
	{
		// Start and end points for mt_rand are initially set at 1 thru 9
		$start = 1;
		$end = 9;

		// The length argument determines how long the number string will end up being
		$i = 1;
		while( $i < $length )
		{
			$start .= 0;
			$end .= 9;
			$i++;
		}

		// If the length argument was greater than zero
		if( $length > 0 )
		{
			// Return the random number
			return $this->password .= mt_rand( $start, $end );
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Generate a special character or series of special characters
	 *
	 * @param   int   The desired length of the string of special characters
	 */
	private function _create_char_string( $length = 1 )
	{
		$pass = '' ;

		$i = 0;
		while ( $i < $length )
		{
				// Get a single number between zero and the length of the chars string
				$num = $this->_get_random_number('chars');

				// Get that char
				$tmp = substr($this->chars, $num, 1);

				// Add the char to the password
				$pass = $pass . $tmp;

				$i++;
		}

		// If the length argument was greater than zero
		if( $length > 0 )
		{
			// Return the char string
			return $this->password .= $pass;
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Get a number representing a character position in the letters or chars
	 *
	 * @param   string   Either 'letters' or 'chars'
	 */
	private function _get_random_number( $member = 'letters' )
	{
		// Get length of member string
		$member_length = strlen( $this->$member ) - 1;

		// Return a random number between zero and the length of the member
		return mt_rand( 0, $member_length );
	}

	// --------------------------------------------------------------

	/**
	 * Output the temp password
	 */
	public function show()
	{
		return $this->password;
	}

	// --------------------------------------------------------------

	/**
	 * Reset the class so we can make another password
	 */
	public function reset()
	{
		$this->letters  = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
		$this->chars    = '~#$%^&*+=?';
		$this->exclude  = array();
		$this->password = '';
	}

	// --------------------------------------------------------------
}

/* End of file generate_password.php */
/* Location: /application/libraries/generate_password.php */