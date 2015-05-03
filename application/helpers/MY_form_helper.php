<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - MY_form_helper
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

/**
 * Form Declaration
 *
 * Creates the opening portion of the form.
 *
 * Modified to accomodate HTTPS actions
 *
 * @param  string  the URI segments of the form destination
 * @param  array   a key/value pair of attributes
 * @param  array   a key/value pair hidden data
 */
function form_open($action = '', $attributes = '', $hidden = array())
{
	$CI =& get_instance();

	if ($attributes == '')
	{
		$attributes = 'method="post"';
	}

	// If an action is not a full URL then turn it into one
	if ($action && strpos($action, '://') === FALSE)
	{
		$action = if_secure_site_url($action);
	}

	// If no action is provided then set to the current url
	$action OR $action = if_secure_site_url($CI->uri->uri_string());

	$form = '<form action="'.$action.'"';

	$form .= _attributes_to_string($attributes, TRUE);

	$form .= '>';

	// Add CSRF field if enabled, but leave it out for GET requests and requests to external websites
	if ($CI->config->item('csrf_protection') === TRUE AND ! (strpos($action, if_secure_base_url()) === FALSE OR strpos($form, 'method="get"')))	
	{
		$hidden[$CI->security->get_csrf_token_name()] = $CI->security->get_csrf_hash();
	}

	// Add MY CSRF token if MY CSRF library is loaded
	if( $CI->load->is_loaded('tokens') AND ! ( strpos( $action, if_secure_base_url() ) === FALSE OR strpos( $form, 'method="get"' ) ) )
	{
		$hidden[ $CI->tokens->name ] = $CI->tokens->token();
	}

	if (is_array($hidden) AND count($hidden) > 0)
	{
		$form .= sprintf("<div style=\"display:none\">%s</div>", form_hidden($hidden));
	}

	return $form;
}

// --------------------------------------------------------------

/**
 * Form Element Requirement Indicator
 *
 * Creates the indicator that shows if a form element is required
 *
 * @param  string  the character or characters to show as the indication of requirement
 * @param  array   a key/value pair of attributes
 */
function input_requirement( $indicator = '', $data = '' )
{
	// ID isn't really a default. It's just here so it is applied before class, which is my preference.
	if( ! empty( $data['id'] ) )
	{
		$defaults['id'] = $data['id'];
	}

	$defaults['class'] = 'input-requirement';

	if( empty( $indicator ) )
	{
		$indicator = '&nbsp;';
	}

	return '<div ' . rtrim( _parse_form_attributes( $data, $defaults ) ) . ">" . $indicator . '</div>';
}

// --------------------------------------------------------------

/* End of file MY_form_helper.php */
/* Location: /application/helpers/MY_form_helper.php */