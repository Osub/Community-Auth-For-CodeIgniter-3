<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Input Class Extension
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 *
 * The input class is being extended so that if config/config specifies secure cookies,
 * we can still have the option of setting a regular cookie, because non HTTPS pages
 * may need cookies, and it's stupid to not be able to set them.
 *
 * We are also going to encrypt and decrypt cookies if the session is being encrypted.
 */

class MY_Input extends CI_Input {

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Set cookie
	 *
	 * Accepts six parameter, or you can submit an associative
	 * array in the first parameter containing all the values.
	 *
	 * @access	public
	 * @param	mixed
	 * @param	string	the value of the cookie
	 * @param	string	the number of seconds until expiration
	 * @param	string	the cookie domain.  Usually:  .yourdomain.com
	 * @param	string	the cookie path
	 * @param	string	the cookie prefix
	 * @param	bool	true makes the cookie secure
	 * @return	void
	 */
	public function set_cookie($name, $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = FALSE, $httponly = FALSE)
	{
		if (is_array($name))
		{
			// always leave 'name' in last place, as the loop will break otherwise, due to $$item
			foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'secure', 'httponly', 'name') as $item)
			{
				if (isset($name[$item]))
				{
					$$item = $name[$item];
				}
			}
		}

		if ($prefix === '' && config_item('cookie_prefix') !== '')
		{
			$prefix = config_item('cookie_prefix');
		}

		if ($domain == '' && config_item('cookie_domain') != '')
		{
			$domain = config_item('cookie_domain');
		}

		if ($path === '/' && config_item('cookie_path') !== '/')
		{
			$path = config_item('cookie_path');
		}

		/*
		 * MODIFICATION ------------------------
		 *
		 * The following if statement has been commented out.
		 *
		if ($secure === FALSE && config_item('cookie_secure') === TRUE)
		{
			$secure = config_item('cookie_secure');
		}
		*/

		if ($httponly === FALSE && config_item('cookie_httponly') !== FALSE)
		{
			$httponly = config_item('cookie_httponly');
		}

		if ( ! is_numeric($expire))
		{
			$expire = time() - 86500;
		}
		else
		{
			$expire = ($expire > 0) ? time() + $expire : 0;
		}

		/**
		 *
		 * MODIFICATION ------------------------
		 *
		 * If the session is being encrypted, we may as well encrypt the cookie value too.
		 */
		if( config_item('sess_encrypt_cookie') === TRUE )
		{
			$CI =& get_instance();

			$value = $CI->encryption->encrypt( $value );
		}
		// END MODIFICATION ------------------------

		setcookie($prefix.$name, $value, $expire, $path, $domain, $secure, $httponly);
	}

	// ------------------------------------------------------------------------

	/**
	* Fetch an item from the COOKIE array
	*
	* If the requested cookie is not the 
	* session cookie, we need to decode it.
	*
	* This whole method should be considered modified.
	*
	* @access	public
	* @param	string
	* @param	bool
	* @return	string
	*/
	function cookie($index = NULL, $xss_clean = NULL)
	{
		$value = $this->_fetch_from_array($_COOKIE, $index, $xss_clean);

		if( config_item('sess_encrypt_cookie') === TRUE && $index != config_item('sess_cookie_name') )
		{
			$CI =& get_instance();

			$value = $CI->encryption->decrypt( $value );
		}

		return $value;
	}

	// ------------------------------------------------------------------------

}

/* End of file MY_Input.php */
/* Location: /application/core/MY_Input.php */ 