<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Form Validation Rules for Contact Form
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

$config['contact'] = array(
	array(
		'field' => 'first_name',
		'label' => 'FIRST NAME',
		'rules' => 'trim|required|max_length[20]|xss_clean'
	),
	array(
		'field' => 'last_name',
		'label' => 'LAST NAME',
		'rules' => 'trim|required|max_length[20]|xss_clean'
	),
	array(
		'field' => 'email',
		'label' => 'EMAIL ADDRESS',
		'rules' => 'trim|required|max_length[255]|valid_email'
	),
	array(
		'field' => 'message',
		'label' => 'MESSAGE',
		'rules' => 'trim|required|xss_clean'
	)
);

/* End of file contact.php */
/* Location: /application/config/form_validation/contact/contact.php */