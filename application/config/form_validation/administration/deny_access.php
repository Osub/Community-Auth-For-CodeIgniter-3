<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Form Validation Rules for Managing Deny List
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

$config['deny_access_rules'] = array(
	// Validation of IP address added to deny list
	array(
		'field' => 'ip_address',
		'label' => 'IP ADDRESS',
		'rules' => 'trim|valid_ip'
	),
	array(
		'field' => 'reason_code',
		'label' => 'REASON CODE',
		'rules' => 'trim|integer'
	),
	// Validation of any IP addresses being removed from deny list
	array(
		'field' => 'ip_removals[]',
		'label' => 'IP ADDRESSES TO REMOVE',
		'rules' => 'trim|valid_ip'
	)
);

/* End of file deny_access.php */
/* Location: /application/config/form_validation/administration/deny_access.php */