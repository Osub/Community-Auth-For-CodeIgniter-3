<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Community Auth - MY_html_helper
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

/**
 * Link
 *
 * Generates link to a CSS file, but tests for HTTPS so link can be made secure if necessary
 *
 * @access	public
 * @param	mixed	stylesheet hrefs or an array
 * @param	string	rel
 * @param	string	type
 * @param	string	title
 * @param	string	media
 * @param	boolean	should index_page be added to the css path
 * @return	string
 */
function link_tag($href = '', $rel = 'stylesheet', $type = '', $title = '', $media = '', $index_page = FALSE)
{
	$CI =& get_instance();

	$link = '<link ';

	if( is_array( $href ) )
	{
		foreach( $href as $k => $v )
		{
			if( $k == 'href' AND strpos( $v, '://' ) === FALSE )
			{
				if( $index_page === TRUE )
				{
					$site_url = $CI->config->site_url( $href );

					if( ! empty( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) !== 'off' )
					{
						if( parse_url( $site_url, PHP_URL_SCHEME ) == 'http' )
						{
							$site_url = substr( $site_url, 0, 4 ) . 's' . substr( $site_url, 4 );
						}
					}

					$link .= 'href="' . $site_url . '" ';
				}
				else
				{
					$base_url = $CI->config->slash_item('base_url');

					if( ! empty( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) !== 'off' )
					{
						if( parse_url( $base_url, PHP_URL_SCHEME ) == 'http' )
						{
							$base_url = substr( $base_url, 0, 4 ) . 's' . substr( $base_url, 4 );
						}
					}

					$link .= 'href="' . $base_url . $v .'" ';
				}
			}
			else
			{
				$link .= "$k=\"$v\" ";
			}
		}

		$link .= "/>";
	}
	else
	{
		if( strpos( $href, '://' ) !== FALSE )
		{
			$link .= 'href="'.$href.'" ';
		}
		else if( $index_page === TRUE )
		{
			$site_url = $CI->config->site_url( $href );

			if( ! empty( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) !== 'off' )
			{
				if( parse_url( $site_url, PHP_URL_SCHEME ) == 'http' )
				{
					$site_url = substr( $site_url, 0, 4 ) . 's' . substr( $site_url, 4 );
				}
			}

			$link .= 'href="' . $site_url . '" ';
		}
		else
		{
			$base_url = $CI->config->slash_item('base_url');

			if( ! empty( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) !== 'off' )
			{
				if( parse_url( $base_url, PHP_URL_SCHEME ) == 'http' )
				{
					$base_url = substr( $base_url, 0, 4 ) . 's' . substr( $base_url, 4 );
				}
			}

			$link .= 'href="' . $base_url . $href . '" ';
		}

		$link .= 'rel="'.$rel.'" ';

		if ($type	!= '')
		{
			$link .= 'type="'.$type.'" ';
		}
		if ($media	!= '')
		{
			$link .= 'media="'.$media.'" ';
		}

		if ($title	!= '')
		{
			$link .= 'title="'.$title.'" ';
		}

		$link .= '/>';
	}


	return $link;
}

// --------------------------------------------------------------

/**
 * Script
 *
 * Generates a script tage to load javascript
 *
 * @access	public
 * @param	string	javascript location
 * @return	string
 */
if ( ! function_exists('script_tag'))
{
	function script_tag( $src )
	{
		$CI =& get_instance();

		$script = '<script ';

		if( strpos( $src, '//' ) !== FALSE )
		{
			$script .= 'src="'.$src.'"></script>';
		}
		else
		{
			$base_url = $CI->config->slash_item('base_url');

			if( ! empty( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) !== 'off' )
			{
				if( parse_url( $base_url, PHP_URL_SCHEME ) == 'http' )
				{
					$base_url = substr( $base_url, 0, 4 ) . 's' . substr( $base_url, 4 );
				}
			}

			$script .= 'src="' . $base_url . $src . '"></script>';
		}

		return $script;
	}
}

// ------------------------------------------------------------------------

/**
 * Image
 *
 * Generates an <img /> element, and allows for HTTPS
 *
 * @access	public
 * @param	mixed
 * @return	string
 */
function img( $src = '', $index_page = FALSE, $base64_encoded = FALSE )
{
	$CI =& get_instance();

	if ( ! is_array($src) )
	{
		$src = array('src' => $src);
	}

	// If there is no alt attribute defined, set it to an empty string
	if ( ! isset($src['alt']))
	{
		$src['alt'] = '';
	}

	$img = '<img';

	foreach ($src as $k=>$v)
	{

		if ($k == 'src' AND strpos($v, '://') === FALSE)
		{
			if( $base64_encoded !== FALSE )
			{
				$img .= ' src="data:image/jpg;base64,'. $v .'"';
			}
			else if ($index_page === TRUE)
			{
				$site_url = $CI->config->site_url( $v );

				if( ! empty( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) !== 'off' )
				{
					if( parse_url( $site_url, PHP_URL_SCHEME ) == 'http' )
					{
						$site_url = substr( $site_url, 0, 4 ) . 's' . substr( $site_url, 4 );
					}
				}

				$img .= ' src="'. $site_url .'"';
			}
			else
			{
				$base_url = $CI->config->slash_item('base_url');

				if( ! empty( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) !== 'off' )
				{
					if( parse_url( $base_url, PHP_URL_SCHEME ) == 'http' )
					{
						$base_url = substr( $base_url, 0, 4 ) . 's' . substr( $base_url, 4 );
					}
				}

				$img .= ' src="'. $base_url . $v .'"';
			}
		}
		else
		{
			// If we are on SSL
			if( $k == 'src' && ! empty( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) !== 'off' )
			{
				// If the image is called via http scheme
				if( stripos( $v, 'https://' ) === FALSE )
				{
					$img .= ' ' . $k . '="' . substr( $v, 0, 4 ) . 's' . substr( $v, 4 ) . '"';
				}
				else
				{
					$img .= " $k=\"$v\"";
				}
			}
			else
			{
				$img .= " $k=\"$v\"";
			}
		}
	}

	$img .= '/>';

	return $img;
}

// ------------------------------------------------------------------------

/* End of file MY_html_helper.php */
/* Location: /application/helpers/MY_html_helper.php */