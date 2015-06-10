<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Form Validation Rules for Recovery Verification
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

$config['recovery_verification'] = array(
	array(
		'field' => 'user_pass',
		'label' => 'NEW PASSWORD',
		'rules' => 'trim|required|matches[user_pass_confirm]|external_callbacks[model,formval_callbacks,_check_password_strength,TRUE]'
	),
	array(
		'field' => 'user_pass_confirm',
		'label' => 'CONFIRM NEW PASSWORD',
		'rules' => 'trim|required'
	),
	array(
		'field' => 'user_name'
	),
	array(
		'field' => 'recovery_code'
	),
	array(
		'field' => 'user_identification'
	)
);

/* End of file recovery_verification.php */
/* Location: /application/config/form_validation/user/recovery_verification.php */