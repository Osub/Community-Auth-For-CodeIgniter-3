<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Form Validation Rules for User Updates
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

// CUSTOMER SPECIFIC UPDATE RULES --------------------------
$config['customer_update_rules'] = array(
	array(
		'field' => 'user_pass',
		'label' => 'PASSWORD',
		'rules' => 'trim|matches[user_pass_confirm]|external_callbacks[model,formval_callbacks,_check_password_strength,FALSE]'
	),
	array(
		'field' => 'user_pass_confirm',
		'label' => 'CONFIRMED PASSWORD',
		'rules' => 'trim'
	),
	array(
		'field' => 'last_name',
		'label' => 'LAST NAME',
		'rules' => 'trim|required|xss_clean'
	),
	array(
		'field' => 'first_name',
		'label' => 'FIRST NAME',
		'rules' => 'trim|required|xss_clean'
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

// MANAGER SPECIFIC UPDATE RULES --------------------------
$config['manager_update_rules'] = array(
	array(
		'field' => 'user_pass',
		'label' => 'PASSWORD',
		'rules' => 'trim|matches[user_pass_confirm]|external_callbacks[model,formval_callbacks,_check_password_strength,FALSE]'
	),
	array(
		'field' => 'user_pass_confirm',
		'label' => 'CONFIRMED PASSWORD',
		'rules' => 'trim'
	),
	array(
		'field' => 'last_name',
		'label' => 'LAST NAME',
		'rules' => 'trim|required|xss_clean'
	),
	array(
		'field' => 'first_name',
		'label' => 'FIRST NAME',
		'rules' => 'trim|required|xss_clean'
	),
	array(
		'field' => 'license_number',
		'label' => 'LICENSE NUMBER',
		'rules' => 'trim|required|alpha_numeric|max_length[8]'
	),
	array(
		'field' => 'phone_number',
		'label' => 'PHONE NUMBER',
		'rules' => 'trim|required|xss_clean|max_length[20]'
	)
);

// ADMIN SPECIFIC UPDATE RULES --------------------------
$config['admin_update_rules'] = array(
	array(
		'field' => 'user_pass',
		'label' => 'PASSWORD',
		'rules' => 'trim|matches[user_pass_confirm]|external_callbacks[model,formval_callbacks,_check_password_strength,FALSE]'
	),
	array(
		'field' => 'user_pass_confirm',
		'label' => 'CONFIRMED PASSWORD',
		'rules' => 'trim'
	),
	array(
		'field' => 'last_name',
		'label' => 'LAST NAME',
		'rules' => 'trim|required|xss_clean'
	),
	array(
		'field' => 'first_name',
		'label' => 'FIRST NAME',
		'rules' => 'trim|required|xss_clean'
	)
);


// SELF UPDATE SPECIFIC RULES ---------------------------
$config['self_update_rules'] = array(
	array(
		'field' => 'user_email',
		'label' => 'EMAIL ADDRESS',
		'rules' => 'trim|required|max_length[255]|valid_email|external_callbacks[model,formval_callbacks,_update_email,self_update]'
	)
);

// UPDATE USER SPECIFIC RULES ---------------------------
$config['update_user_rules'] = array(
	array(
		'field' => 'user_banned',
		'label' => 'BANNED',
		'rules' => 'trim|integer'
	),
	array(
		'field' => 'user_email',
		'label' => 'EMAIL ADDRESS',
		'rules' => 'trim|required|max_length[255]|valid_email|external_callbacks[model,formval_callbacks,_update_email,update_user]'
	)
);

// PROFILE IMAGE -------------------------
$config['profile_image'] = array(
	array(
		'field' => 'profile_image',
		'label' => 'PROFILE IMAGE',
		'rules' => 'trim'
	)
);

/**
 * In all cases where a user is being updated, the form validation rules
 * are a combined set, specific to the type of update AND the role
 */
$config['self_update_admin']    = array_merge( $config['self_update_rules'], $config['admin_update_rules'] );
$config['self_update_manager']  = array_merge( $config['self_update_rules'], $config['manager_update_rules'] );
$config['self_update_customer'] = array_merge( $config['self_update_rules'], $config['customer_update_rules'] );
$config['update_user_manager']  = array_merge( $config['update_user_rules'], $config['manager_update_rules'] );
$config['update_user_customer'] = array_merge( $config['update_user_rules'], $config['customer_update_rules'] );

/* End of file user_update.php */
/* Location: /application/config/form_validation/user/user_update.php */