<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -----------------------------------------------------------------
| LAST COMMUNITY AUTH REPO TAG
| -----------------------------------------------------------------
| The last tagged version in the repository
| 
*/

	define('TAG', 'v2.0.3');

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
| PHP 5.2 COMPATIBLE PASSWORDS
| -----------------------------------------------------------------
| Set to 1 to use PBKDF2 in a PHP 5.3+ environment.
| Set to 0 to use bcrypt when available (in PHP 5.3+).
|
| You would want to set this to 1 if you are working on a development
| environment that is PHP5.3+, but plan to migrate to a environment 
| that is PHP < 5.3.
| 
*/

	define('PHP52_COMPATIBLE_PASSWORDS', 0);

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

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */