<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Custom Error Page Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Custom_error_page extends MY_Controller {

	/**
	 * Display a custom error page
	 */
	function error_404()
	{
		/*
		 * Since this method is called more than one way, we need to check 
		 * if $_GET is not empty to determine which way it was called. As documented 
		 * the controller/method called by 404_override does not get called when
		 * using show_404(), and because of that, we still need to use 
		 * file_get_contents() in the top of /application/errors/error_404.php.
		 */
		if( count( $_GET ) == 0 )
		{
			// If user is logged in, show logout link, etc.
			$this->is_logged_in();

			// Set 404 status header
			$this->output->set_status_header('404');
		}

		$view_data['heading'] = ( isset( $_GET['heading'] ) ) ? $_GET['heading'] : '404 Page Not Found';
		$view_data['message'] = ( isset( $_GET['message'] ) ) ? $_GET['message'] : 'The page you requested was not found.';

		$data = array(
			'title' => WEBSITE_NAME . ' - ' . $view_data['heading'],
			'content' => $this->load->view( 'custom_error', $view_data, TRUE )
		);

		$this->load->view( $this->template, $data );
	}

	// --------------------------------------------------------------
}

/* End of file custom_error_page.php */
/* Location: ./application/controllers/custom_error_page.php */