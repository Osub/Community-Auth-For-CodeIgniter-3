<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - MY_url_helper
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
 * Secure Site URL
 *
 * If USE_SSL is set to 1, creates a HTTPS version of site_url(),
 * else just creates a standard site URL.
 *
 * @param  mixed either a string reprenting the path or an array of path elements
 */
if( ! function_exists('secure_site_url') )
{
	function secure_site_url( $uri = '' )
	{
		return ( USE_SSL === 1 )
			? site_url( $uri, 'https' )
			: site_url( $uri );
	}
}

// --------------------------------------------------------------

/**
 * If Secure Site URL
 *
 * If USE_SSL is set to 1 AND current request is on HTTPS,
 * creates a HTTPS version of site_url(), else a standard HTTP version.
 *
 * @param  mixed either a string reprenting the path or an array of path elements
 */
if( ! function_exists('if_secure_site_url') )
{
	function if_secure_site_url( $uri = '' )
	{
		return ( USE_SSL === 1 && is_https() )
			? site_url( $uri, 'https' )
			: site_url( $uri );
	}
}

// --------------------------------------------------------------

/**
 * Secure Base URL
 *
 * If USE_SSL is set to 1, creates a HTTPS version of base_url().
 *
 * @param  mixed  either a string reprenting the path, an array of path elements or a URL to a file
 */
if( ! function_exists('secure_base_url') )
{
	function secure_base_url( $uri = '' )
	{
		return ( USE_SSL === 1 )
			? base_url( $uri, 'https' )
			: base_url( $uri );
	}
}

// --------------------------------------------------------------

/**
 * If Secure Base URL
 *
 * If current request is HTTPS, creates a HTTPS version of base_url().
 *
 * @param  mixed  either a string reprenting the path, an array of path elements or a URL to a file
 */
if( ! function_exists('if_secure_base_url') )
{
	function if_secure_base_url( $uri = '' )
	{
		return ( USE_SSL === 1 && is_https() )
			? base_url( $uri, 'https' )
			: base_url( $uri );
	}
}

// --------------------------------------------------------------

/**
 * Current URL
 *
 * Returns the full URL (including segments) of the page where this
 * function is placed
 *
 * Modified so that current_url() allows for HTTPS. Also modified
 * so that a specific host (domain) can replace the current one.
 * This is important if you want to be able to have somebody
 * switch the current page to another language using i18n domains.
 *
 * @param  string  the requested language.
 */
if( ! function_exists('current_url') )
{
	function current_url()
	{
		$CI =& get_instance();

		$url = $CI->config->site_url( $CI->uri->uri_string() );

		if( is_https() )
		{
			if( parse_url( $url, PHP_URL_SCHEME ) == 'http' )
			{
				$url = substr( $url, 0, 4 ) . 's' . substr( $url, 4 );
			}
		}

		// Return the current URL, making sure to attach any query string that may exist
		return ( $_SERVER['QUERY_STRING'] )
			? $url . '?' . $_SERVER['QUERY_STRING']
			: $url;
	}
}

// --------------------------------------------------------------

/**
 * Secure Anchor Link
 *
 * Creates a secure anchor based on the local URL, and if USE_SSL is 'on'.
 *
 * @param  string  the URL
 * @param  string  the link title
 * @param  mixed   any attributes
 */
if( ! function_exists('secure_anchor') )
{
	function secure_anchor( $uri = '', $title = '', $attributes = '' )
	{
		$title = (string) $title;

		$site_url = is_array($uri)
			? secure_site_url($uri)
			: preg_match('#^(\w+:)?//#i', $uri) ? $uri : secure_site_url($uri);

		if ($title === '')
		{
			$title = $site_url;
		}

		if ($attributes !== '')
		{
			$attributes = _stringify_attributes($attributes);
		}

		return '<a href="'.$site_url.'"'.$attributes.'>'.$title.'</a>';
	}
}

// --------------------------------------------------------------

/* End of file MY_url_helper.php */
/* Location: /application/helpers/MY_url_helper.php */