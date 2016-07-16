<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Password Strength Config
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2016, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

/*
| -----------------------------------------------------------------
|				MIN CHARS FOR PASSWORD						
| -----------------------------------------------------------------
| The least amount of characters for a valid password
*/

$config['min_chars_for_password'] = 8;

/*
| -----------------------------------------------------------------
|				MAX CHARS FOR PASSWORD						
| -----------------------------------------------------------------
| The maximum amount of characters for a valid password.
| Set to 0 for unlimited length.
| 
| Because Community Auth uses CRYPT_BLOWFISH to hash passwords,
| any password over 72 characters in length is truncated. You 
| could certainly allow more characters, but only the first 
| 72 characters are used for the resulting hash.
*/

$config['max_chars_for_password'] = 0;

/*
| -----------------------------------------------------------------
|				DIGIT(S) REQUIRED FOR PASSWORD						
| -----------------------------------------------------------------
| The minimum amount of numeric characters for a valid password.
| Set to 0 to require none.
*/

$config['min_digits_for_password'] = 1;

/*
| -----------------------------------------------------------------
|			LOWERCASE LETTER(S) REQUIRED FOR PASSWORD						
| -----------------------------------------------------------------
| The minimum amount of lowercase alpha characters for a valid password.
| Set to 0 to require none.
*/

$config['min_lowercase_chars_for_password'] = 1;

/*
| -----------------------------------------------------------------
|			UPPERCASE LETTER(S) REQUIRED FOR PASSWORD						
| -----------------------------------------------------------------
| The minimum amount of uppercase alpha characters for a valid password.
| Set to 0 to require none.
*/

$config['min_uppercase_chars_for_password'] = 1;

/*
| -----------------------------------------------------------------
|			NON-ALPHANUMERIC CHAR(S) REQUIRED FOR PASSWORD						
| -----------------------------------------------------------------
| The minimum amount of non-alphanumeric characters for a valid password.
| Set to 0 to require none.
*/

$config['min_non_alphanumeric_chars_for_password'] = 0;

/* End of file password_strength.php */
/* Location: /community_auth/config/examples/password_strength.php */