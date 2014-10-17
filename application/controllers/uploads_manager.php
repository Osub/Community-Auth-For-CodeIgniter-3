<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Uploads Manager Controller ( For AJAX Uploads )
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Uploads_manager extends MY_Controller {

	/**
	 * The bridge type holds the name of the Upload
	 * class method that is called, and is based
	 * on the final destination of the file being uploaded.
	 */
	private $bridge_type = '';

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load->library('upload');
		$this->config->load('uploads_manager');
	}

	// --------------------------------------------------------------

	/**
	 * BRIDGE FILESYSTEM method does most of the work behind
	 * uploading a file whos destination is the server's file system.
	 *
	 * @param   string   used as a suffix to the upload config set.
	 */
	public function bridge_filesystem( $type )
	{
		$this->bridge_type = 'filesystem';

		$this->_facilitate_upload( $type );
	}

	// --------------------------------------------------------------

	/**
	 * BRIDGE DATABASE method does most of the work behind
	 * uploading a file whos destination is the database.
	 *
	 * @param   string   used as a suffix to the upload config set.
	 */
	public function bridge_database( $type )
	{
		$this->bridge_type = 'database';

		$this->_facilitate_upload( $type );
	}

	// --------------------------------------------------------------

	/**
	 * BRIDGE FTP method connects to the FTP_UPLOAD method in the upload class.
	 *
	 * @param   string   used as a suffix to the upload config set.
	 */
	public function bridge_ftp( $type )
	{
		$this->bridge_type = 'ftp';

		$this->_facilitate_upload( $type );
	}

	// --------------------------------------------------------------

	/**
	 * _FACILITATE_UPLOAD calls the upload class for all three
	 * upload types after authentication of the user, and making 
	 * sure the CSRF token(s) matched.
	 *
	 * @param   string   used as a suffix to the upload config set.
	 */
	private function _facilitate_upload( $type )
	{
		$auth_roles =  config_item( 'authentication_' . $type );

		if( $auth_roles !== FALSE && $this->require_role( $auth_roles ) )
		{
			// Check if a valid form submission has been made
			if( $this->tokens->match )
			{
				$bridge_type = $this->bridge_type;

				$response = $this->upload->upload_bridge( $type, $this->bridge_type );
			}
			else
			{
				// Error: No Token Match
				$response['status'] = 'error';
				$response['issue']  = 'No Token Match. Please reload the page. ' . $this->tokens->posted_value . ' != ' . $this->tokens->current_token;
			}

			$response['token'] = $this->tokens->token();
			$response['ci_csrf_token'] = $this->security->get_csrf_hash();

			echo json_encode( $response );
		}
	}

}
/* End of file uploads_manager.php */
/* Location: /application/controllers/uploads_manager.php */