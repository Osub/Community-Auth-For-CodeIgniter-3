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
| You can set this to almost anything except "user/login", unless 
| you modify the login method in the User controller.
| 
*/

	define('LOGIN_PAGE', 'login');

}

/* End of file constants.php */
/* Location: ./application/config/constants.php */