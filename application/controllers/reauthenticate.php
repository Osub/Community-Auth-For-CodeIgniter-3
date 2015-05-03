<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Reauthentication Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Reauthenticate extends MY_Controller{
	
	public function __construct()
	{
		parent::__construct();

		// Force SSL
		$this->force_ssl();
	}

	// -----------------------------------------------------------------------

	/**
	 * An example of reauthentication. You might require a logged in
	 * user to provide their username and password to get past a
	 * certain point, or to make an important change. Whatever the 
	 * case may be, reauthentication makes the logged in user provide
	 * a good username and password to proceed.
	 */
	public function index()
	{
		if( $this->require_min_level(1) )
		{
			// Valid form submission
			if( $this->tokens->match )
			{
				/**
				 * If we were processing a form that contained form
				 * elements other than the login form's username
				 * and password, we would set the first parameter
				 * of reauthenticate() to FALSE. We would load that
				 * form's form validation rules here. 
				 *
				 * EXAMPLE:
				 *   $this->config->load('form_validation/special_rules');
				 *   $this->auth_model->validation_rules( config_item('special_rules') );
				 *   $this->auth_model->reauthenticate( FALSE );
				 */
				$this->auth_model->reauthenticate();
			}

			// Use the normal login form as a nested view
			$view_data['login_form'] = $this->load->view('auth/reauthentication_form', '', TRUE );

			$data = array(
				'title' => WEBSITE_NAME . ' - Reauthentication Example',
				'javascripts' => array(
					'js/jquery.passwordToggle-1.1.js'
				),
				'extra_head' => '
					<script>
						$(document).ready(function(){
							$("#show-password").passwordToggle({target:"#login_pass"});
						});
					</script>
				',
				'content' => $this->load->view( 'auth/reauthentication_example', $view_data, TRUE )
			);

			$this->load->view( $this->template, $data );
		}
	}
	
	// -----------------------------------------------------------------------
}

/* End of file reauthenticate.php */
/* Location: /application/controllers/reauthenticate.php */