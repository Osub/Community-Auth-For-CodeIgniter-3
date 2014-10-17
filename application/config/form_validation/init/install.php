<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Form Validation Rules for Community Auth Installation
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

$config['install_rules'] = array(
	array(
		'field' => 'populate_database',
		'label' => 'POPULATE DATABASE WITH TABLES CHECKBOX',
		'rules' => 'trim|integer'
	),
	array(
		'field' => 'admin',
		'label' => 'ADMIN CHECKBOX',
		'rules' => 'trim|integer'
	),
	array(
		'field' => 'user_name',
		'label' => 'ADMIN USERNAME',
		'rules' => 'trim|alpha_numeric|max_length['. MAX_CHARS_4_USERNAME .']|min_length['. MIN_CHARS_4_USERNAME .']|external_callbacks[model,formval_callbacks,_username_check]'
	),
	array(
		'field' => 'user_pass',
		'label' => 'ADMIN PASSWORD',
		'rules' => 'trim|external_callbacks[model,formval_callbacks,_check_password_strength,TRUE]'
	),
	array(
		'field' => 'user_email',
		'label' => 'ADMIN EMAIL ADDRESS',
		'rules' => 'trim|max_length[255]|valid_email|external_callbacks[model,formval_callbacks,_email_exists_check]'
	),
	array(
		'field' => 'last_name',
		'label' => 'ADMIN LAST NAME',
		'rules' => 'trim|xss_clean'
	),
	array(
		'field' => 'first_name',
		'label' => 'ADMIN FIRST NAME',
		'rules' => 'trim|xss_clean'
	),
	array(
		'field' => 'users',
		'label' => 'TEST USERS CHECKBOX',
		'rules' => 'trim|integer'
	),
	array(
		'field' => 'test_users_pass',
		'label' => 'TEST USERS PASSWORD',
		'rules' => 'trim|external_callbacks[model,formval_callbacks,_check_password_strength,TRUE]'
	)
);

/* End of file install.php */
/* Location: /application/config/form_validation/init/install.php */