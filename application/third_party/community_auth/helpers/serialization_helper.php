<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Common Functions Helper
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
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

// ------------------------------------------------------------------------

/**
 * Serialize some data.
 * 
 * @param mixed Random variable, array, object, etc.
 * @return string The serialized data.
 */
if( ! function_exists('serialize_data') )
{
	function serialize_data($data)
	{
		if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				if (is_string($val))
				{
					$data[$key] = str_replace('\\', '{{slash}}', $val);
				}
			}
		}
		else
		{
			if (is_string($data))
			{
				$data = str_replace('\\', '{{slash}}', $data);
			}
		}

		return serialize($data);
	}
}

// ------------------------------------------------------------------------

/**
 * Unserialize some data.
 * 
 * @param string The serialized data.
 * @return mixed Whatever was unserialized, or whatever was passed to 
 *               this function if it was not serialized.
 */
if( ! function_exists('unserialize_data') )
{
	function unserialize_data($data)
	{
		if( is_serialized($data) )
		{
			$data = unserialize(stripslashes($data));

			if (is_array($data))
			{
				foreach ($data as $key => $val)
				{
					if (is_string($val))
					{
						$data[$key] = str_replace('{{slash}}', '\\', $val);
					}
				}

				return $data;
			}

			$data = is_string($data) 
				? str_replace('{{slash}}', '\\', $data) 
				: $data;
		}

		return $data;
	}
}

// ------------------------------------------------------------------------

/* End of file serialization_helper.php */
/* Location: ./application/helpers/serialization_helper.php */