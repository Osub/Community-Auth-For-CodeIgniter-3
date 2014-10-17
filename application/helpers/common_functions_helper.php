<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Common Functions Helper
 *
 * Since it is not possible to extend core/Common.php, this helper can be loaded
 * to provide more common functions that we will use frequently. I'm not currently
 * autoloading this, but if we wanted to we could add "common_functions" to the 
 * autoloaded helpers. We could also could have made this a pre-system hook.
 * Whatever floats yer boat.
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

// ------------------------------------------------------------------------

/**
 * Check value to find if it is serialized data.
 *
 * Function borrowed from Wordpress.
 *
 * @param mixed $data Value to check to see if was serialized.
 * @return bool False if not serialized and true if it was.
 */
if( ! function_exists('is_serialized') )
{
	function is_serialized( $data ) {
		// if it isn't a string, it isn't serialized
		if ( ! is_string( $data ) )
			return false;
		$data = trim( $data );
	 	if ( 'N;' == $data )
			return true;
		$length = strlen( $data );
		if ( $length < 4 )
			return false;
		if ( ':' !== $data[1] )
			return false;
		$lastc = $data[$length-1];
		if ( ';' !== $lastc && '}' !== $lastc )
			return false;
		$token = $data[0];
		switch ( $token ) {
			case 's' :
				if ( '"' !== $data[$length-2] )
					return false;
			case 'a' :
			case 'O' :
				return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
			case 'b' :
			case 'i' :
			case 'd' :
				return (bool) preg_match( "/^{$token}:[0-9.E-]+;\$/", $data );
		}
		return false;
	}
}

/* End of file common_functions_helper.php */
/* Location: ./application/helpers/common_functions_helper.php */