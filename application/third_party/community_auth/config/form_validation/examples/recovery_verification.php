<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Form Validation Rules for Recovery Verification
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

// Recovery verification
$config['recovery_verification'] = array(
	array(
		'field' => 'passwd',
		'label' => 'NEW PASSWORD',
		'rules' => array(
            'trim',
            'required',
            'matches[passwd_confirm]',
            array( 
                '_check_password_strength', 
                array( $CI->validation_callables, '_check_password_strength' ) 
            )
        )
	),
	array(
		'field' => 'passwd_confirm',
		'label' => 'CONFIRM NEW PASSWORD',
		'rules' => 'trim|required'
	),
	array(
		'field' => 'recovery_code'
	),
	array(
		'field' => 'user_identification'
	)
);

/* End of file recovery_verification.php */
/* Location: /community_auth/config/form_validation/examples/recovery_verification.php */