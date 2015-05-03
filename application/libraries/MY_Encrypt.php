<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - MY_Encrypt Library
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class MY_Encrypt extends CI_Encrypt {

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	// -----------------------------------------------------------------------

	/**
	 * Get Mcrypt cipher Value
	 *
	 * This method is extended only to set the default encryption to blowfish.
	 * This has only been chosen to cut down on the encrypted string length, as 
	 * the default, which is MCRYPT_RIJNDAEL_256 creates strings that are roughly 
	 * 10 times the length of the original string.
	 *
	 * @access	private
	 * @return	string
	 */
	function _get_cipher()
	{
		if ($this->_mcrypt_cipher == '')
		{
			$this->_mcrypt_cipher = MCRYPT_BLOWFISH;
		}

		return $this->_mcrypt_cipher;
	}

	// -----------------------------------------------------------------------

	/**
	 * Save the current encryption settings
	 */
	public function save_settings()
	{
		$this->saved_settings = array(
			'encryption_key' => $this->encryption_key,
			'_mcrypt_cipher' => $this->_mcrypt_cipher,
			'_mcrypt_mode'   => $this->_mcrypt_mode
		);
	}
	
	// -----------------------------------------------------------------------

	/**
	 * Restore the saved encryption settings
	 */
	public function restore_settings()
	{
		if( ! empty( $this->saved_settings ) )
		{
			foreach( $this->saved_settings as $k => $v )
			{
				$this->$k = $v;
			}
		}
	}
	
	// -----------------------------------------------------------------------

	/**
	 * Use a set of default encryption settings
	 */
	public function use_defaults()
	{
		$this->encryption_key = $this->CI->config->item('encryption_key');
		$this->_mcrypt_cipher = MCRYPT_BLOWFISH;
		$this->_mcrypt_mode   = MCRYPT_MODE_CBC;
	}
	
	// -----------------------------------------------------------------------

}

// END MY_Encrypt class

/* End of file MY_Encrypt.php */
/* Location: ./application/libraries/MY_Encrypt.php */