<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Form Validation Rules for Login
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

// LOGIN ---------------------------
$config['login_rules'] = array(
	array(
		'field' => 'login_string',
		'label' => 'USERNAME OR EMAIL ADDRESS',
		'rules' => 'trim|required|max_length[255]|xss_clean'
	),
	array(
		'field' => 'login_pass',
		'label' => 'PASSWORD',
		'rules' => 'trim|required|external_callbacks[model,formval_callbacks,_check_password_strength,TRUE]'
	)
);

/* End of file login.php */
/* Location: /application/config/form_validation/auth/login.php */