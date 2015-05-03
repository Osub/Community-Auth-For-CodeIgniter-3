<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - FTP Library Extension
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

/*
 * This class extends the CodeIgniter FTP class for use with SSL.
 * Modifications have also been made so that errors can be retreived
 * in a way that allows for a better user experience. A timeout has 
 * also been added so that the connection doesn't seem to hang.
 * No other changes have been made.
 */
class MY_FTP extends CI_FTP {

	public $ssl_mode = FALSE;
	public $error_stack = array();
	private $timeout = 8;

	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	function connect($config = array())
	{
		if (count($config) > 0)
		{
			$this->initialize($config);
		}

		if( $this->ssl_mode == TRUE )
		{
			if( function_exists('ftp_ssl_connect') )
			{
				$this->conn_id = @ftp_ssl_connect($this->hostname, $this->port, $this->timeout );
			}
			else
			{
				$this->_error('ftp_unable_to_connect');
			}
		}

		else
		{
			$this->conn_id = @ftp_connect($this->hostname, $this->port, $this->timeout );
		}

		if ( $this->conn_id === FALSE )
		{
			if ($this->debug == TRUE)
			{
				$this->_error('ftp_unable_to_connect');
			}
			return FALSE;
		}

		if ( ! $this->_login())
		{
			if ($this->debug == TRUE)
			{
				$this->_error('ftp_unable_to_login');
			}
			return FALSE;
		}

		// Set passive mode if needed
		if ($this->passive == TRUE)
		{
			ftp_pasv($this->conn_id, TRUE);
		}

		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Display error message
	 *
	 * @access	private
	 * @param	string
	 */
	function _error($line)
	{
		$CI =& get_instance();
		$CI->lang->load('ftp');
		$this->error_stack[] = $CI->lang->line($line);
	}
}

/* End of file MY_Ftp.php */
/* Location: ./application/libraries/MY_Ftp.php */