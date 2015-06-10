<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - MY_Encryption Library
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class MY_Encryption extends CI_Encryption {

	public $saved_settings = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// Set cipher to blowfish by default
		$this->_cipher = 'blowfish';
	}

	// -----------------------------------------------------------------------

	/**
	 * Save the current encryption settings
	 */
	public function save_settings()
	{
		$this->saved_settings = array(
			'_key'    => $this->_key,
			'_cipher' => $this->_cipher,
			'_mode'   => $this->_mode
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
		$this->_key    = config_item('encryption_key');
		$this->_cipher = 'blowfish';
		$this->_mode   = 'cbc';
	}
	
	// -----------------------------------------------------------------------

}

// END MY_Encryption class

/* End of file MY_Encryption.php */
/* Location: ./application/libraries/MY_Encryption.php */