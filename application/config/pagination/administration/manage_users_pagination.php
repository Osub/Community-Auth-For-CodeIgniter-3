<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Pagination Config for User Management
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

$config['manage_users_pagination_settings'] = array(

	'base_url' => secure_site_url( 'administration/manage_users' ),
	'per_page' => 8,
	'use_page_numbers' => TRUE,
	'anchor_class' => 'class="std-link" ',
	'cur_tag_open' => '&nbsp;<span id="active-set">',
	'cur_tag_close' => '</span>',
	'first_link' => FALSE,
	'last_link' => FALSE,
	'num_links' => 3
);

$config['manage_users_search_options'] = array(
	'u.user_name' => 'username',
	'u.user_email' => 'email address'
);

/* End of file manage_users_pagination.php */
/* Location: /application/config/pagination/administration/manage_users_pagination.php */