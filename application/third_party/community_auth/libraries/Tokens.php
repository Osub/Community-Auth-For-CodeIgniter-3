<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Form Tokens Library - V1.0.2
 * 
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class tokens
{
	/**
	 * The name attribute of the form's token element
	 *
	 * @var string
	 * @access public
	 */
	public $name;

	/**
	 * The value of the last generated token
	 *
	 * @var mixed
	 * @access public
	 */
	public $token = FALSE;

	/**
	 * The value of the posted token
	 *
	 * @var mixed
	 * @access public
	 */
	public $posted_value = FALSE;

	/**
	 * An array of valid tokens
	 *
	 * @var array
	 * @access public
	 */
	public $jar = [];

	/**
	 * Whether or not the posted token matches one in the jar
	 *
	 * @var bool
	 * @access public
	 */
	public $match = FALSE;

	/**
	 * The CodeIgniter super object
	 *
	 * @var object
	 * @access private
	 */
	private $CI;

	/**
	 * The current scheme / protocol
	 *
	 * @var string
	 * @access private
	 */
	private $scheme = 'http';

	/**
	 * Whether or not allow CI debug level 
	 * logging for the token jar.
	 *
	 * @var bool
	 * @access public
	 */
	private $debug = FALSE;

	/**
	 * Whether or not to encrypt the tokens.
	 * This may be useful for debugging, but
	 * SHOULD ALWAYS BE LEFT SET TO TRUE FOR 
	 * THE PRODUCTION WEBSITE!
	 *
	 * @var bool
	 * @access private
	 */
	private $encrypted_tokens = TRUE;

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		if( ! empty( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) !== 'off' )
		{
			// Set the current scheme / protocol
			$this->scheme = 'https';
		}

		$this->CI =& get_instance();
		$this->CI->load->library('encryption');

		// Set existing tokens in jar
		$this->_set_jar();

		// Check the default token name for match
		$this->token_check();
	}

	// --------------------------------------------------------------

	/**
	 * Check the token status with a provided token name, or "token" by default
	 */
	public function token_check( $rename = '', $dump_jar_on_match = FALSE )
	{
		// If rename provided, check that token name
		$this->name = ( $rename == '' ) ? config_item('token_name') : $rename;
			
		// If no token jar contents, no reason to proceed
		if( ! empty( $this->jar ) )
		{
			// Set the posted_value variable
			if( $this->posted_value = $this->CI->input->post( $this->name ) )
			{
				// If the posted value matches one in the jar
				if( in_array( $this->posted_value, $this->jar ) )
				{
					// Successful token match !
					$this->match = TRUE;

					// Dump all tokens ?
					if( $dump_jar_on_match )
					{
						$this->jar = [];

						$this->save_tokens_cookie();
					}

					// Just delete the matching token
					else
					{
						// What token jar key was the matching token ?
						$matching_key = array_search( $this->posted_value, $this->jar );

						// Remove the matching token from the jar
						unset( $this->jar[ $matching_key ] );

						// Auto generate a new token
						$this->generate_form_token();
					}

					if( $this->debug )
					{
						log_message( 'debug', count( $this->jar ) . '@token_check' );
						log_message( 'debug', json_encode( $this->jar ) );
					}

					return TRUE;
				}
			}
		}

		return FALSE;
	}
	
	// -----------------------------------------------------------------------

	/**
	 * Generate a form token. (a "singleton" type method)
	 */
	public function generate_form_token()
	{
		if( ! $this->token )
		{
			// Create a unique token
			$this->token = substr(md5(uniqid() . microtime() . rand()), 0, 8);

			// Add the new token to the token jar array
			$this->jar[] = $this->token;

			// The token jar can only hold so many tokens
			while( count( $this->jar ) > config_item('token_jar_size') )
			{
				array_shift( $this->jar );
			}

			if( $this->debug )
			{
				log_message( 'debug', count( $this->jar ) . '@generate_form_token' );
				log_message( 'debug', json_encode( $this->jar ) );
			}

			$this->save_tokens_cookie();
		}

		return $this->token;
	}

	// --------------------------------------------------------------------

	/**
	 * Alias for generate_form_token method (because it's shorter to type).
	 * Anytime we have a need to use a form token, simply call this function to 
	 * set a token and retreive the value for placement in the form.
	 */
	public function token()
	{
		return $this->generate_form_token();
	}
	
	// --------------------------------------------------------------------

	/**
	 * Save the token cookie
	 */
	public function save_tokens_cookie()
	{
		$token_cookie_name = ( $this->scheme == 'http' )
			? config_item('http_tokens_cookie')
			: config_item('https_tokens_cookie');

		$cookie_secure = ( $this->scheme == 'http' ) ? FALSE : TRUE;

		if( $this->debug )
		{
			log_message( 'debug', count( $this->jar ) . '@save_tokens_cookie' );
			log_message( 'debug', json_encode( $this->jar ) );
		}

		setcookie(
			$token_cookie_name,
			$this->pack_tokens(),
			0,
			config_item('cookie_path'),
			config_item('cookie_domain'),
			$cookie_secure
		);
	}
	
	// -----------------------------------------------------------------------

	/**
	 * Fill the token jar with available tokens from cookie
	 */
	protected function _set_jar()
	{
		$token_cookie_name = $this->scheme == 'http' 
			? config_item('http_tokens_cookie') 
			: config_item('https_tokens_cookie');

		/**
		 * If we read in the tokens more than once, it will override
		 * changes that may have been made, such as deleting a token.
		 */
		if( empty( $this->jar ) )
		{
			$this->jar = ( isset( $_COOKIE[ $token_cookie_name ] ) ) 
				? $this->unpack_tokens( $token_cookie_name )
				: [];
		}

		if( $this->debug )
		{
			log_message( 'debug', count( $this->jar ) . '@_set_jar' );
			log_message( 'debug', json_encode( $this->jar ) );
		}

		return $this->jar;
	}
	
	// -----------------------------------------------------------------------

	/**
	 * Unpack the tokens
	 */
	protected function unpack_tokens( $token_cookie_name )
	{
		$tokens = $_COOKIE[ $token_cookie_name ];

		if( $this->encrypted_tokens )
		{
			// Save the current encryption class settings
			$this->CI->encryption->save_settings();

			// Use default encryption settings
			$this->CI->encryption->use_defaults();

			// Decode the tokens
			$tokens = $this->CI->encryption->decrypt( $tokens );

			// Restore the saved encryption class settings
			$this->CI->encryption->restore_settings();
		}

		$tokens = explode( '|', $tokens );

		return $tokens;
	}
	
	// -----------------------------------------------------------------------

	/**
	 * Pack the tokens
	 */
	protected function pack_tokens()
	{
		// If jar contains any unset indexes, remove them
		foreach( $this->jar as $token )
		{
			if( ! empty( $token ) )
			{
				$tokens[] = $token;
			}
		}
		
		// We have tokens to implode or we don't
		$tokens = isset( $tokens ) ? implode( '|', $tokens ) : '';

		if( $this->encrypted_tokens )
		{
			// Save the current encryption class settings
			$this->CI->encryption->save_settings();

			// Use default encryption settings
			$this->CI->encryption->use_defaults();

			// Encode the tokens
			$tokens = $this->CI->encryption->encrypt( $tokens );

			// Restore the saved encryption class settings
			$this->CI->encryption->restore_settings();
		}

		return $tokens;
	}
	
	// -----------------------------------------------------------------------
}

/* End of file tokens.php */
/* Location: /application/libraries/tokens.php */ 