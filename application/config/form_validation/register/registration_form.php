<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Form Validation Rules for Registration Form
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

$config['registration_form'] = array(
	array(
		'field' => 'user_name',
		'label' => 'USERNAME',
		'rules' => 'trim|required|alpha_numeric|max_length['. MAX_CHARS_4_USERNAME .']|min_length['. MIN_CHARS_4_USERNAME .']|external_callbacks[model,formval_callbacks,_username_check]'
	),
	array(
		'field' => 'user_pass',
		'label' => 'PASSWORD',
		'rules' => 'trim|required|external_callbacks[model,formval_callbacks,_check_password_strength,TRUE]'
	),
	array(
		'field' => 'user_email',
		'label' => 'EMAIL ADDRESS',
		'rules' => 'trim|required|max_length[255]|valid_email|external_callbacks[model,formval_callbacks,_email_exists_check]'
	),
	array(
		'field' => 'last_name',
		'label' => 'LAST NAME',
		'rules' => 'trim|required|max_length[20]|xss_clean'
	),
	array(
		'field' => 'first_name',
		'label' => 'FIRST NAME',
		'rules' => 'trim|required|max_length[20]|xss_clean'
	),
	array(
		'field' => 'street_address',
		'label' => 'STREET ADDRESS',
		'rules' => 'trim|required|xss_clean|max_length[60]'
	),
	array(
		'field' => 'city',
		'label' => 'CITY',
		'rules' => 'trim|required|xss_clean|max_length[60]'
	),
	array(
		'field' => 'state',
		'label' => 'STATE or PROVINCE',
		'rules' => 'trim|required|alpha|max_length[50]'
	),
	array(
		'field' => 'zip',
		'label' => 'ZIP or POSTAL CODE',
		'rules' => 'trim|required|xss_clean|max_length[10]'
	)
);

/* End of file registration_form.php */
/* Location: /application/config/form_validation/register/registration_form.php */