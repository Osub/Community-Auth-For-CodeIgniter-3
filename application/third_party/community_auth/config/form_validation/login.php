<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Form Validation Rules for Login
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2016, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

// CI not normally available in config files
$CI =& get_instance();

// Login ---------------------------
$config['login_rules'] = array(
	array(
		'field' => 'login_string',
		'label' => 'USERNAME OR EMAIL ADDRESS',
		'rules' => 'trim|required|max_length[255]' /* Replace max_length w/ valid_email is site not using usernames */
	),
	array(
		'field' => 'login_pass',
		'label' => 'PASSWORD',
		'rules' => array(
            'trim',
            'required',
            array( 
                '_check_password_strength', 
                array( $CI->validation_callables, '_check_password_strength' ) 
            )
        )
	)
);

/* End of file login.php */
/* Location: /community_auth/config/form_validation/login.php */