<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Documentation of Configuration View
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
?>

<h1>Documentation of Configuration</h1>
<ul class="std-list">
	<li><?php echo anchor('documentation/configuration', 'Configuration'); ?></li>
	<li><?php echo anchor('documentation/installation', 'Installation'); ?></li>
	<li><?php echo anchor('documentation/usage', 'Usage'); ?></li>
</ul>
<h2>Configuration</h2>
<p>
	Configuration of Community Auth should go fairly quickly for you. We will be editing a handful of files:
</p>
<ul class="std-list">
	<li><?php echo anchor('documentation/configuration#index', '/index.php'); ?></li>
	<li><?php echo anchor('documentation/configuration#authentication', '/application/config/authentication.php'); ?></li>
	<li><?php echo anchor('documentation/configuration#config', '/application/config/config.php'); ?></li>
	<li><?php echo anchor('documentation/configuration#constants', '/application/config/constants.php'); ?></li>
	<li><?php echo anchor('documentation/configuration#database', '/application/config/database.php'); ?></li>
	<li><?php echo anchor('documentation/configuration#db_tables', '/application/config/db_tables.php'); ?></li>
	<li><?php echo anchor('documentation/configuration#email', '/application/config/email.php'); ?></li>
	<li><?php echo anchor('documentation/configuration#uploads_manager', '/application/config/uploads_manager.php'); ?></li>
</ul>
<h2 id="index">/index.php</h2>
<h3>Default Timezone</h3>
<p>
	A PHP function named date_default_timezone_set() is located at the top of <b>/index.php</b>. Go to the <a href="http://php.net/manual/en/timezones.php" rel="external">list of supported timezones</a>, find the one where your server is located, then replace 'America/Los_Angeles' with it.
</p>
<h3>Maintenance Mode</h3>
<p>
	If you set the maintenance mode to TRUE, and add your WAN IP address to the list of IP addresses in the <b>$ca_developer_ips</b> ( a few lines below ), you will be able to work on your site in its normal environment, while regular site visitors will see the maintenance mode page. If you'd like to customize the maintenance mode page, it is located at <b>/maintenance-mode.php</b>.
</p>
<h3>Environment</h3>
<p>
	When in the 'development' environment, Community Auth will log the contents of emails instead of trying to actually send them. The emails are stored in <b>/application/log/email</b>, and are txt files, named as microtime values. This is actually very handy when trying to debug the contents of emails.
</p>
<h2 id="authentication">/application/config/authentication.php</h2>
<h3>Disable Installer</h3>
<p>
	Set this configuration option to FALSE to enable access to the Community Auth installer. Make sure to disable the installer immediately after using it.
</p>
<h3>Levels and Roles</h3>
<p>
	This is where you create the user levels with associated role names that will be used by your application. Unless you have a basic e-commerce type website, your specific application will probably have different roles, but if you are just testing Community Auth, and intend to use the example controllers, you should leave the default levels and roles as-is for now.
</p>
<p>
	If you are going to change the levels and roles, <b>admin always needs to be called admin</b>, but you can create as many different user levels and roles as you want. Using some functions of the authentication library, privileges are linear in nature, so keep that in mind as you create your levels and roles. Also, if you want to create user levels that are numbered higher than 99, make sure to adjust the user_level field in the users table to accomodate the larger number.
</p>
<h3>Groups</h3>
<p>
	If you've got a bunch of user roles, you may group them. This may mean less typing for you as you develop your application, because you can refer to multiple user roles as a group.
</p>
<h3>Maximum Allowed Login Attempts</h3>
<p>
	This setting controls how many attempts a person, recognized by IP address and the username or email they are trying to login with, should be able to have before being locked out for a period of time.
</p>
<h3>Deny Access</h3>
<p>
	If for some reason login attempts exceed the Maximum Allowed Login Attempts value, then when they reach the number held in this setting, the IP address associated with the login is added to the deny list in the local Apache configuration file. Set to 0 (zero) to disable.
</p>
<h3>Apache Config File Location</h3>
<p>
	The location, including filename, or your Apache config file should be set here. Please see the provided .htaccess file for two lines of code that MUST be in your Apache config file if you want Community Auth to manage the deny list for you:
</p>
<p style="font-family:monospace;">
	# BEGIN DENY LIST --<br />
	# END DENY LIST --
</p>
<h3>Seconds on Hold</h3>
<p>
	This setting controls how long a person is locked out when they exceed the maximum allowed login attempts. The default setting is 600 seconds, or 10 minutes. When locked out, password recovery is also disabled.
</p>
<p>
	This setting has nothing to do with IP adresses that are added to the deny list by the Deny Access functionality. Those IP addresses are permanently blocked until an admin removes the block.
</p>
<h3>Disallow Multiple Logins</h3>
<p>
	This setting, TRUE by default, disallows a user being logged in on multiple devices, or with multiple browsers on the same device. When a person logs in, their user agent string, and the time they logged in is stored in the database, and these values are verified on subsequent page loads.
</p>
<p>
	It is my opinion that this setting is critical to application security. Please understand that if you set this option to FALSE, the Authentication library will no longer compare the user agent or login time stored in the database to the supplied user agent and login time stored in the session. The reason for this is that multiple devices would have multiple user agents and login times.
</p>
<h3>Allow Remember Me</h3>
<p>
	This setting allows you to turn on and off the ability to have a persistant login where users may choose to stay logged in even after the browser has closed. When turned on, the login form shows a "Remember Me" checkbox. Remember Me is off by default.
</p>
<h3>Remember Me Cookie Name</h3>
<p>
	This setting allows you to choose the name of the Remember Me cookie. Some versions of Internet Explorer don't like underscores, so don't use them if you change the provided name.
</p>
<h3>Remember Me Expiration</h3>
<p>
	How long (in seconds) the Remember Me functionality allows the session to last. Based on the needs of your website, you might choose a duration that is shorter or longer than the provided setting, which is just under 3 years. You might not need Community Auth's Remember Me functionality if you configure CodeIgniter's session to be persistent by default, but Remember Me allows you to have both a session that ends when the browser closes, and a session that ends at the time specified in this setting.
</p>
<h3>HTTP User Cookie Name</h3>
<p>
	While the authentication cookie is handled in the session, the http user cookie allows for some user data to be stored so that the user is semi-identifiable, or for other general purpose use related to the logged in user. <b>DO NOT USE FOR AUTHENTICATION!</b>
</p>
<h3>Selected Profile Columns</h3>
<p>
	An array of profile data to select when logging in or checking login. Anything in this array should exist as a field in all user profile tables. The data is made available in the HTTP user cookie, in views, and in config items. Leave the array empty if you don't want to select any of the logged in user's profile data.
</p>
<h2 id="config">/application/config/config.php</h2>
<p>
	There are a lot of things in this file that need to be examined, not only for Community Auth, but for CodeIgniter in general. It's beyond the scope of this documentation to cover all aspects of this configuration file, but there are a few settings that are essential or helpful, and require your review.
</p>
<h3>Encryption Key</h3>
<p>
	Make sure your encryption key is unique and 32 characters long. I like using GRC's Perfect Password generator so I get a truly random string, which is available <a href="https://www.grc.com/passwords.htm" rel="external">HERE</a>.
</p>
<h3>Session Cookie Name</h3>
<p>
	You should probably change this to something that doesn't reveal the technology associated with the authentication. Be careful what you name the cookie, because you may have problems with logging in with Internet Explorer if you do.
</p>
<h2 id="constants">/application/config/constants.php</h2>
<h3>Website Name</h3>
<p>
	The human readable name of the website or company.
</p>
<h3>Use SSL</h3>
<p>
	If SSL is not available, set this setting to 0. If SSL is available, set this setting to 1. Community Auth does not work with shared SSL.
</p>
<h3>PHP 5.2 Compatible Passwords</h3>
<p>
	By default, the authentication library uses bcrypt in environments where PHP 5.3+ is running. Although bcrypt seems to be considered the best, you might need to migrate your users table to a testing or production environment where PHP is less than 5.3. If that is the case, before creating any users (including admins), change this setting to 1. By doing so, you will force Community Auth to create passwords hashed with PBKDF2.
</p>
<p>
	I know the above scenario is probably rare, but after spending quite a bit of time helping somebody debug their inability to login, I wanted to find a way to prevent others from having the same problem.
</p>
<h3>Redirect to HTTPS</h3>
<p>
	A strong security policy does not allow for HTTP pages to be redirected to HTTPS. If set to 1, an attempt to access a page where SSL is forced will redirect to the HTTPS version. If set to 0, a 404 error is generated.
</p>
<h3>Minimum Characters For Username</h3>
<p>
	This setting enforces the minimum number of characters for a username.
</p>
<h3>Maximum Characters For Username</h3>
<p>
	This setting enforces the maximum number of characters for a username.
</p>
<h3>Minimum Characters For Password</h3>
<p>
	This setting enforces the minimum number of characters for a password. Other aspects of password strength are enforced by the _check_password_strength method inside the <b>/application/models/formval_callbacks.php</b> model.
</p>
<h3>Maximum Characters For Password</h3>
<p>
	This setting enforces the maximum number of characters for a password, and the default setting of 256 is more than generous. Depending on the type of website you will be creating, you could safely reduce this to a much smaller value.
</p>
<h2 id="database">/application/config/database.php</h2>
<h3>Create Your MySQL Database &amp; User</h3>
<p>
	Before you can configure your database connection, you will need to create a mysql database. Community Auth's installer doesn't create the database, but only populates it with tables, so you'll need to use the command line, phpMyAdmin or another MySQL client to create the database before proceeding. Likewise, your actual database user needs to be created, because the Community Auth installer doesn't do this either.
</p>
<p>
	Once you have your database and database user, please review the CodeIgniter documentation for <a href="http://codeigniter.com/user_guide/database/configuration.html">database configuration</a>, and configure your database connection for each ENVIRONMENT. Please note that at this time, the Community Auth installer only works with MySQL and MySQLi.
</p>
<h2 id="db_tables">/application/config/db_tables.php</h2>
<p>
	If you would like to change the name of the database tables so your database is unique, this is where you do it.
</p>
<h2 id="email">/application/config/email.php</h2>
<p>
	Emails are sent out by Community Auth when certain actions are performed in the example controllers. Make sure to set each of the email addresses located in this configuration file. You may set all to the same email address if you want, but then all emails generated by Community Auth will be sent with that email address in the from field.
</p>
<h2 id="uploads_manager">/application/config/uploads_manager.php</h2>
<p>
	Profile image uploads don't require any configuration if you want the files to be stored in their default location, which is in the empty <b>/uploads_directory/</b>. If you would like to change the location, rename the location, or store the files in the database, this is where you would make your changes. The commenting in the file makes configuration obvious and easy.
</p>
<p>
	Although the datatype of the `profile_images` field in the user profiles table is set to accomodate image paths or image storage, if you know that you won't be storing the profile images in the database, you can optimize the field by changing the datatype to something like varchar(255). Do this before installation by editing the view located at <b>/application/views/sql/db.php</b>, or do it after installation by using the command line, phpMyAdmin, or your MySQL client.
</p>
<p>
	Proceed to <?php echo anchor('documentation/installation', 'installation'); ?> of Community Auth.
</p>

<?php

/* End of file configuration.php */
/* Location: /application/views/documentation/configuration.php */