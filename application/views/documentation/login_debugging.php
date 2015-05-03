<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Documentation for Login Debugging View
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
?>

<h1>Documentation for Login Debugging</h1>
<p>
	Before pulling your hair out, or banging your head against the wall, there are easy ways to help narrow down why you might not be able to login.
</p>
<h2>Set Error Logging Threshold</h2>
<p>
	You will first need to open up <b>/application/config/config.php</b>. Find the log_threshold setting and change it to 2 or greater. 2 is the lowest level that allows for debug level error messages to be logged.
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false; first-line:209;">
		$config['log_threshold'] = 2;</pre>
</div>
<h2>Try to Login</h2>
<p>
	Every time you try to login or request a page on your website, CodeIgniter will be writing to a log file, located at <b>/application/logs/</b>. Open the log that includes today's date as the filename.
</p>
<p>
	For each login attempt, you should see something like this:
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false;">
DEBUG - 2012-09-15 10:54:17 --> 
 string      = &lt;your username or email address>
 password    = &lt;the password you entered>
 form_token  = &lt;the value of the posted form token>
 flash_token = &lt;the value of the csrf token></pre>
</div>
<h3>Now what?</h3>
<p>
	First, unless you changed the name attributes of the form elements in the login form, the string, password, and form_token values should always be set. If they aren't then you're going to need to revert to the original name attributes.
</p>
<p>
	A more likely problem to see here is that the form_token doesn't match the flash_token. If this is the case, you need to look for 404 errors in your server access logs (not the CodeIgniter log file). Your server access logs could contain a 404 for an image or some other asset that doesn't exist. Since a 404 error "uses up" the flash token, you'll never be able to login until you fix that. Also, every once-in-a-while, I've found that FirePHP being enabled won't allow me to login or stay logged in. If your form_token doesn't match your flash_token and you're using FirePHP, turn off FirePHP and try again.
</p>
<h2>Everything Seems Fine, What's Next?</h2>
<p>
	If your string (which is either a username or email address), password, form_token and flash_token look good, then you'll want to look for other debug message logged by Community Auth's Authentication library.
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false;">
DEBUG - 2012-09-15 10:54:17 --> 
 user is banned             = no
 password in database       = $2a$09 ...
 posted/hashed password     = $2a$09 ...
 required level             = 1
 user level in database     = 9
 user level equivalant role = admin</pre>
</div>
<p>
	Your likely to see something like this if the password in the database isn't the same as the one you are trying to use to login with. If this is the case, the values for the passwords won't match. If you've migrated to another server, and you are sure that the password you are trying to login with is correct, then it could be that the password was created on a server that used a different hashing algorithm than the new server. All of your users would need to go through the user recovery process.
</p>
<h2>Still Not Working?</h2>
<h3>NO MATCH FOR USERNAME OR EMAIL DURING LOGIN ATTEMPT</h3>
<p>
	This log message is pretty obvious. There's simply no match for the username or email address that you used in your login attempt.
</p>
<h3>IP, USERNAME, OR EMAIL ADDRESS ON HOLD</h3>
<p>
	Just like it says, this log message indicates that the IP address, username, or email address is on hold.
</p>
<h3>LOGIN ATTEMPT DID NOT PASS FORM VALIDATION</h3>
<p>
	This log message indicates that some part of the login attempt didn't pass validation. It might even be a bad password if the password used in the login attempt wasn't strong enough.
</p>
<h2>What About a Problem With Users Getting Logged Out?</h2>
<p>
	So you can login just fine, but the user doesn't stay logged in? Don't worry, there's error message logging for that too.
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false;">
DEBUG - 2012-09-15 11:27:17 --> 
 user is banned             = no 
 disallowed multiple logins = true 
 hashed user agent          = &lt;hash value>
 user agent from database   = &lt;hash value>
 required level             = 1
 user level in database     = 9
 user level equivalant role = admin</pre>
</div>
<p>
	As I mentioned above, if you use FirePHP, turn it off before going further. I've learned the hard way that when things go nuts that disabling FirePHP is the key to success. Disabling FirePHP is done in Firefox, not in Community Auth.
</p>
<p>
	If you still can't stay logged in, take a look at the values in the debug error logging. You're sure to find something that doesn't look right. Most obvious would probably be the hashed user agent not matching the user agent from the database. While very rare, you might find instances where a browser changes it's user agent for no good reason. If it is a modern browser that you must support, CodeIgniter and Community Auth have session and authentication settings that would need to be changed in order to accomodate that browser. In all of my own testing, I've never run into this problem, so I'm fairly confident you won't run into it either.
</p>
<h2>Debugging Summary</h2>
<p>
	I hope I've provided enough information for you to debug your application. In my daily use of applications built on Community Auth, the error messages explained above are all I would need to quickly diagnose a problem logging in or staying logged in. If you need extra help, please ask for help on the <a href="http://forum.codeigniter.com" rel="external">CodeIgniter Forum</a>.
</p>
<?php

/* End of file login_debugging.php */
/* Location: /application/views/documentation/login_debugging.php */