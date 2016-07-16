<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Authentication Config
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
|						LEVELS AND ROLES							
| -----------------------------------------------------------------
| This definition sets the levels and roles that will be used for authentication.
| 
| Keep in mind that if you use key numbering higher than 255,  
| the auth_level field of the users table will need to be changed
| to smallint, or another integer datatype that handles larger numbers.
|
| No user level should ever be set with a key of 0.
|
*/

$config['levels_and_roles'] = [
	'1' => 'customer',
	'6' => 'manager',
	'9' => 'admin'
];

/*
| -----------------------------------------------------------------
|							GROUPS							
| -----------------------------------------------------------------
| This definition sets grouped roles that will be used for authentication.
|
*/

$config['groups'] = [
	'employees' => 'manager,admin'
];

/*
| -----------------------------------------------------------------
|				ADD ACL QUERY TO AUTH FUNCTIONS							
| -----------------------------------------------------------------
| This config option turns on an additional query to retreive a logged
| in user's ACL records when they login or when login status is checked. 
| If you're not going to implement your own ACL categories, actions, 
| and take the time to create an interface to manage the ACL, then 
| you would leave this set to FALSE. Furthermore, basic ACL usage doesn't
| require that this option be set to true, because usage of the 
| Auth_model->acl_permits method will query the database if it hasn't
| already been done.
|
*/

$config['add_acl_query_to_auth_functions'] = FALSE;

/*
| -----------------------------------------------------------------
|						MAX_ALLOWED_ATTEMPTS						
| -----------------------------------------------------------------
| This definition sets the maximum amount of failed login attempts
| or failed password recovery attempts before the IP or username is
| placed on hold.
| 
*/

$config['max_allowed_attempts'] = 5;

/*
| -----------------------------------------------------------------
|						DENY_ACCESS	AT					
| -----------------------------------------------------------------
| If for some reason login attempts exceed the max_login_attempts
| value, then when they reach the number held in this definition,
| their IP address is added to the deny list in the local Apache
| configuration file.
|
| SET TO ZERO TO DISABLE THIS FUNCTIONALITY
| 
*/

$config['deny_access_at'] = 10;

/*
| -----------------------------------------------------------------
|					DENIED ACCESS REASON						
| -----------------------------------------------------------------
| The reasons why an IP address may be in the deny list
| 
*/

$config['denied_access_reason'] = [
	'0' => 'Not Specified',
	'1' => 'Login Attempts',
	'2' => 'Malicious User',
	'3' => 'Hacking Attempt',
	'4' => 'Spam',
	'5' => 'Obscene Language',
	'6' => 'Threatening Language'
];

/*
| -----------------------------------------------------------------
|					APACHE CONFIG FILE LOCATION						
| -----------------------------------------------------------------
| The location, including filename, or your Apache config file.
| 
*/

$config['apache_config_file_location'] = FCPATH . '.htaccess';

/*
| -----------------------------------------------------------------
|							SECONDS_ON_HOLD							
| -----------------------------------------------------------------
| This definition sets the amount of time an IP or username is on 
| hold if the maximum amount of failed login attempts or failed
| password recovery attempts is reached.
| 
| 600 seconds is 10 minutes
|
*/

$config['seconds_on_hold'] = 600;

/*
| -----------------------------------------------------------------
|						DISALLOW_MULTIPLE_LOGINS					
| -----------------------------------------------------------------
| This setting attempts to either allow or disallow an account to be 
| logged in by the same user on more than one device, or with more 
| than one browser on the same device.
|
*/

$config['disallow_multiple_logins'] = FALSE;

/*
| -----------------------------------------------------------------
|						ENCRYPT AUTH IDENTIFIERS					
| -----------------------------------------------------------------
| This setting will encrypt the authentication identifiers, which are
| stored in the session. CodeIgniter removed session encryption in
| CodeIgniter 3, so we have to do the encyption if we want (or not).
|
*/

$config['encrypt_auth_identifiers'] = FALSE;

/*
| -----------------------------------------------------------------
|						ENCRYPT ALL COOKIES					
| -----------------------------------------------------------------
| This setting allows you to encrypt all of the cookies that 
| Community Auth sets. Be aware that the tokens cookie encryption 
| is turned off/on in the Tokens library. This setting does not 
| affect the session contents.
|
*/

$config['encrypt_all_cookies'] = TRUE;

/*
| -----------------------------------------------------------------
|						ALLOW REMEMBER ME							
| -----------------------------------------------------------------
| This setting allows you to turn on and off the ability to have 
| a persistant login where users may choose to stay logged in 
| even after the browser has closed.
|
*/

$config['allow_remember_me'] = FALSE;

/*
| -----------------------------------------------------------------
|					REMEMBER ME COOKIE NAME							
| -----------------------------------------------------------------
| This setting allows you to choose the name of the remember me cookie.
| Remember that Internet Explorer doesn't like underscores.
|
*/

$config['remember_me_cookie_name'] = 'rememberMe';

/*
| -----------------------------------------------------------------
|					REMEMBER ME EXPIRATION							
| -----------------------------------------------------------------
| How long (in seconds) the remember me funcationality allows the session to last.
|
*/

$config['remember_me_expiration'] = 93062220;

/*
| -----------------------------------------------------------------
|					HTTP USER COOKIE NAME							
| -----------------------------------------------------------------
| This setting allows you to choose the name of the http user cookie.
| While the authentication cookie is handled in the session, the 
| http user cookie allows for the user data to be stored so that 
| the user is semi-identifiable, or for other general purpose use 
| related to the logged in user. DO NOT USE FOR AUTHENTICATION!
|
*/

$config['http_user_cookie_name'] = 'httpUser';

/*
| -----------------------------------------------------------------
|					HTTP USER COOKIE ELEMENTS							
| -----------------------------------------------------------------
| This setting allows you to customize the data that is stored 
| in the HTTP user cookie. By default, only the username is stored, 
| but any element returned in the auth data (when a user logs in)
| can be added to the array.
| 
| DO NOT ADD ELEMENTS THAT ARE CONSIDERED SENSITIVE, 
| ESPECIALLY IF YOU ARE NOT ENCRYPTING ALL COOKIE CONTENTS!
|
*/

$config['http_user_cookie_elements'] = ['username'];

/*
| -----------------------------------------------------------------
|				      TOKEN COOKIES CONFIG						
| -----------------------------------------------------------------
| This setting allows you to choose the name of the http token cookie,
| and also the name of the https token cookie.
|
| The token jar size is the amount of tokens that can be held in each cookie.
| 
| The token name is the name of the form element holding the token value.
|
*/

$config['http_tokens_cookie']  = 'httpTokens';
$config['https_tokens_cookie'] = 'httpsTokens';
$config['token_jar_size']      = 32;
$config['token_name']          = 'token';

/*
| -----------------------------------------------------------------
|					RECOVERY CODE EXPIRATION							
| -----------------------------------------------------------------
| How long (in seconds) the password recovery code is good for.
| The default is two hours.
|
*/

$config['recovery_code_expiration'] = 60 * 60 * 2;

/*
| -----------------------------------------------------------------
|				DELETE SESSION COOKIE ON LOGOUT							
| -----------------------------------------------------------------
| When the user logs out, their session cookie can either have
| the userdata unset, or you can choose to have the cookie completely
| deleted. Set to FALSE to keep the cookie, TRUE to delete it.
| By default, CodeIgniter just deletes the userdata, so set to 
| FALSE if you want to maintain this behavior.
|
| Note: unless you set 'show_login_form_on_logout' to FALSE,
| the session cookie is immediately re-created.
*/

$config['delete_session_cookie_on_logout'] = FALSE;

/*
| -----------------------------------------------------------------
|				SHOW LOGIN FORM ON LOGOUT							
| -----------------------------------------------------------------
| When the user logs out, they can be presented with a login form
| on the logout page, or else just show the logout confirmation page.
| The default (TRUE) is to show the login form.
*/

$config['show_login_form_on_logout'] = TRUE;

/*
| -----------------------------------------------------------------
|				DEFAULT LOGIN REDIRECT							
| -----------------------------------------------------------------
| When the user logs in, they will usually be redirected back to 
| the page they were trying to access, but if for some reason they
| reached the login page and have no redirect, this URI STRING is where 
| they will be redirected to. The default is to be redirected to the home page.
*/

$config['default_login_redirect'] = '';

/*
| -----------------------------------------------------------------
|				ALLOWED PAGES FOR LOGIN							
| -----------------------------------------------------------------
| Logins may only happen from specified pages on the website.
| So, for instance, we don't want somebody posting directly to 
| an old LOGIN_PAGE, or some random page. LOGIN_PAGE is automatically 
| added, so you just put in optional login pages here.
*/

$config['allowed_pages_for_login'] = [];

/*
| -----------------------------------------------------------------
|				REDIRECT TO HTTPS						
| -----------------------------------------------------------------
| If a page is supposed to be viewed using an encrypted connection, 
| you can either redirect to the HTTPS version, or serve up a 404 error.
*/

$config['redirect_to_https'] = FALSE;

/*
| -----------------------------------------------------------------
|				LOGIN FORM VALIDATION FILE						
| -----------------------------------------------------------------
| The config file that handles the form validation for login attempts.
| The file must be located in application/config, or in community_auth/config.
| Make sure to remove any file extension, as this string is passed to config->load().
*/

$config['login_form_validation_file'] = 'form_validation/examples/login';

/*
| -----------------------------------------------------------------
|				DECLARED AUTH MODEL						
| -----------------------------------------------------------------
| Community Auth makes it easy to extend it's Auth model by allowing 
| you declare your own model. You will still autoload auth_model, 
| but you would also autoload your own model AFTER auth_model.
|
| When creating your own model, make sure it extends Auth_model,
| unless you intend to replace the entire Auth model with your own.
*/

$config['declared_auth_model'] = 'auth_model';

#
# -----------------------------------------------------------------
#				HANDLE AUTH SESSIONS GC ON LOGOUT						
# -----------------------------------------------------------------
# Unless you create a cron job that calls the auth_sessions_gc 
# method in the auth model, you'll want to leave this setting 
# set to TRUE so that orphaned and expired records in the 
# auth_sessions table are deleted.
#
# If you do have a cron to handle garbage collection, set 
# this setting to FALSE.
#
# Example cron to run once every 10 minutes:
#     */10 * * * * php /path/to/project/index.php crons auth_sessions_gc > /dev/null 2>&1
#
# Example cront to run once every 10 minutes (using wget):
#     */10 * * * * /usr/bin/wget http://<YOUR DOMAIN>/crons/auth_sessions_gc -O /dev/null
#

$config['auth_sessions_gc_on_logout'] = TRUE;


/* End of file authentication.php */
/* Location: /community_auth/config/authentication.php */