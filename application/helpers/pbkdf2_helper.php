<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - PBKDF2 Helper
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
 * PBKDF2 key derivation function as defined by RSA's PKCS #5: https://www.ietf.org/rfc/rfc2898.txt
 * Test vectors can be found here: https://www.ietf.org/rfc/rfc6070.txt
 * This implementation of PBKDF2 was originally created by https://defuse.ca
 * With improvements by http://www.variations-of-shadow.com
 *
 * @param   string   $algorithm - The hash algorithm to use. Recommended: SHA256
 * @param   string   $password - The password.
 * @param   string   $salt - A salt that is unique to the password.
 * @param   integer  $count - Iteration count. Higher is better, but slower. Recommended: At least 1024.
 * @param   integer  $key_length - The length of the derived key in bytes.
 * @param   boolean  $raw_output - If true, the key is returned in raw binary format. Hex encoded otherwise.
 * @return  mixed    A $key_length-byte key derived from the password and salt.
 */
function pbkdf2( $algorithm, $password, $salt, $count, $key_length, $raw_output = FALSE )
{
	$algorithm = strtolower($algorithm);

	if( ! in_array( $algorithm, hash_algos(), TRUE ) )
	{
		die('PBKDF2 ERROR: Invalid hash algorithm.');
	}

	if( $count <= 0 || $key_length <= 0 )
	{
		die('PBKDF2 ERROR: Invalid parameters.');
	}

	// number of blocks = ceil(key length / hash length)
	$hash_length = strlen( hash( $algorithm, "", TRUE ) );

	$block_count = 1 + ( ( $key_length - 1 ) / $hash_length );

	$output = "";

	for( $i = 1; $i <= $block_count; $i++ )
	{
		// $i encoded as 4 bytes, big endian.
		$last = $salt . pack( "N", $i );

		// first iteration
		$last = $xorsum = hash_hmac( $algorithm, $last, $password, TRUE );

		// perform the other $count - 1 iterations
		for( $j = 1; $j < $count; $j++ )
		{
			$xorsum ^= ( $last = hash_hmac( $algorithm, $last, $password, TRUE ) );
		}

		$output .= $xorsum;
	}

	return ( $raw_output ) ? substr( $output, 0, $key_length ) : bin2hex( substr( $output, 0, $key_length ) );
}

// --------------------------------------------------------------

/* End of file pbkdf2_helper.php */
/* Location: /application/helpers/pbkdf2_helper.php */