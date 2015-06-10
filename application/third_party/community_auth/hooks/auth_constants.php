<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Auth Constants
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

function auth_constants(){

/*
| -----------------------------------------------------------------
| WEBSITE_NAME
| -----------------------------------------------------------------
| Set to the human readable name of your business or website.
| By default this is set to the domain name of your website, 
| but you will probably change this to a string value.
| 
*/

	define('WEBSITE_NAME', $_SERVER['HTTP_HOST']);

/*
| -----------------------------------------------------------------
| USE_SSL
| -----------------------------------------------------------------
| Set to 1 for standard SSL certificate.
| Set to 0 for no SSL.
| 
*/

	define('USE_SSL', 0);

/*
| -----------------------------------------------------------------
| LOGIN_PAGE
| -----------------------------------------------------------------
| This is the uri string to the hidden login route. 
| We can change this if there is a brute force attack on the login. 
| 
*/

	define('LOGIN_PAGE', 'login');

/*
| -----------------------------------------------------------------
| REDIRECT_TO_HTTPS
| -----------------------------------------------------------------
| Set to 1 to allow redirection to an HTTPS page when forcing SSL.
| Set to 0 to show a 404 error if not HTTPS.
| 
*/

	define('REDIRECT_TO_HTTPS', 0);

/*
| -----------------------------------------------------------------
| MIN_CHARS_4_USERNAME
| -----------------------------------------------------------------
| Sets the minimum character length to enforce for usernames.
| 
*/

	define('MIN_CHARS_4_USERNAME',8);

/*
| -----------------------------------------------------------------
| MAX_CHARS_4_USERNAME
| -----------------------------------------------------------------
| Sets the maximum character length to enforce for usernames.
| If a change is made, the database needs to be adjusted!
| 
*/

	define('MAX_CHARS_4_USERNAME',12);

/*
| -----------------------------------------------------------------
| MIN_CHARS_4_PASSWORD
| -----------------------------------------------------------------
| Sets the minimum character length to enforce for passwords.
| 
*/

	define('MIN_CHARS_4_PASSWORD',8);

/*
| -----------------------------------------------------------------
| MAX_CHARS_4_PASSWORD
| -----------------------------------------------------------------
| Sets the maximum character length to enforce for passwords.
| The default of 256 is more than generous, but if for some reason
| you think you need to allow bigger passwords, remember that
| the password hashing functions can be expensive in terms of 
| CPU usage. For most sites, this setting could be in the 
| range of 32 to 64.
| 
*/

	define('MAX_CHARS_4_PASSWORD',256);

}

/* End of file constants.php */
/* Location: ./application/config/constants.php */