<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - MY_form_helper
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
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
 * Modified to accomodate HTTPS actions, and also auto 
 * injects a hidden input for a token. (See Tokens library)
 *
 * @param  string  the URI segments of the form destination
 * @param  array   a key/value pair of attributes
 * @param  array   a key/value pair hidden data
 */
function form_open($action = '', $attributes = array(), $hidden = array())
{
	$CI =& get_instance();

	// If no action is provided then set to the current url
	if ( ! $action)
	{
		$action = current_url($action);
	}
	// If an action is not a full URL then turn it into one
	elseif (strpos($action, '://') === FALSE)
	{
		$action = if_secure_site_url($action);
	}

	$attributes = _attributes_to_string($attributes);

	if (stripos($attributes, 'method=') === FALSE)
	{
		$attributes .= ' method="post"';
	}

	if (stripos($attributes, 'accept-charset=') === FALSE)
	{
		$attributes .= ' accept-charset="'.strtolower(config_item('charset')).'"';
	}

	$form = '<form action="'.$action.'"'.$attributes.">\n";

	// Add CSRF field if enabled, but leave it out for GET requests and requests to external websites
	if ($CI->config->item('csrf_protection') === TRUE && strpos($action, if_secure_base_url()) !== FALSE && ! stripos($form, 'method="get"'))
	{
		$hidden[$CI->security->get_csrf_token_name()] = $CI->security->get_csrf_hash();
	}

	// Add MY CSRF token if MY CSRF library is loaded
	if( $CI->load->is_loaded('tokens') && strpos($action, if_secure_base_url()) !== FALSE  && ! stripos($form, 'method="get"') )
	{
		$hidden[ $CI->tokens->name ] = $CI->tokens->token();
	}

	if (is_array($hidden))
	{
		foreach ($hidden as $name => $value)
		{
			$form .= '<input type="hidden" name="'.$name.'" value="'.html_escape($value).'" style="display:none;" />'."\n";
		}
	}

	return $form;
}

// --------------------------------------------------------------

/* End of file MY_form_helper.php */
/* Location: /application/helpers/MY_form_helper.php */