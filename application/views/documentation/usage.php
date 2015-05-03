<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Documentation of Usage View
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

<h1>Documentation of Usage</h1>
<ul class="std-list">
	<li><?php echo anchor('documentation/configuration', 'Configuration'); ?></li>
	<li><?php echo anchor('documentation/installation', 'Installation'); ?></li>
	<li><?php echo anchor('documentation/usage', 'Usage'); ?></li>
</ul>
<h2>Usage</h2>
<p>
	Congratulations, if you've made it this far in the installation, you should be able to login and browse through the admin area of the example application. <?php echo anchor('documentation/login_debugging', 'Learn how to debug login if you cannot.'); ?> Since we both know that you have your own usage needs, you will need to know how to enforce authentication in your controllers, and how to detect who is who in views.
</p>
<h3>Enforcing Authentication by Role</h3>
<p>
	This is probably the most useful, and easiest way to make sure a certain role is logged in. Check the example controllers, and you will see that inside a method that needs authentication, the entire contents of the method is wrapped inside an if statement like this:
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false;">
		if( $this->require_role('admin,manager') )
		{
			// Method contents ...	
		}</pre>
</div>
<p>
	If a user of an appropriate role is not logged in, the login form will automatically appear.
</p>
<h3>Enforcing Authentication by Role Group</h3>
<div class="doc_code">
	<pre class="brush: php; toolbar: false;">
		if( $this->require_group('employees') )
		{
			// Method contents ...	
		}</pre>
</div>
<p>
	If a user of an appropriate group is not logged in, the login form will automatically appear.
</p>
<h3>Enforcing Authentication by Account Level Number</h3>
<p>
	If your user levels have been created in such a way that permissions are linear in nature, such as admin who can alter managers who can alter customers, and the admin is level 9, the managers are level 6, and the customers are level 1, then we can authenticate and allow access to the admin and managers by using the following inside the method of one of your controllers:
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false;">
		if( $this->require_min_level(6) )
		{
			// Method contents ...	
		}</pre>
</div>
<p>
	If a user of the appropriate level is not logged in, the login form will automatically appear.
</p>
<p>
	If you just want to make sure a user of any level is logged in:
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false;">
		if( $this->require_min_level(1) )
		{
			// Do something ...	
		}</pre>
</div>
<p>
	In this case, if a user of any level is not logged in, the login form will automatically appear.
</p>
<h3>Check if User Logged In</h3>
<p>
	Most of the time, if you have a page that does not require login, but want to show a logout link or other information specific to a logged in user, you will use the following in the appropriate method of your controller:
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false;">
		$this->is_logged_in();</pre>
</div>
<p>
	Calling is_logged_in() loads the variables shown below. Please note: the variables shown below will be set when enforcing authentication. You don't need to call is_logged_in() if you are already using require_role(), require_min_level(), etc.
</p>
<p>
	Also note: If you have set "cookie_secure" to TRUE in config/config, is_logged_in() will never return anything on a standard HTTP page. You can still see if somebody is logged in by testing for the <b>$_user_name, $_first_name or $_last_name</b> variables in your views. This allows for customization but <span style="color:red;">should not be used to authenticate the user</span>. As an example, the main template uses $_first_name.
</p>
<h3>Variables Accessible in Views</h3>
<p>
	When a user is logged in, certain variables will be available to the views, because they are loaded by MY_Controller.
</p>
<ul class="std-list">
	<li><b>$auth_user_id</b> - The logged in user's user ID.</li>
	<li><b>$auth_user_name</b> - The logged in user's username.</li>
	<li><b>$auth_level</b> - The logged in user's account level by number.</li>
	<li><b>$auth_role</b> - The logged in user's account level by name.</li>
	<li><b>$auth_email</b> - The logged in user's email address.</li>
</ul>
<p>
	The following variables are set in the example application because the fields are represented in the selected profile columns array:
</p>
<ul class="std-list">
	<li><b>$auth_first_name</b> - The logged in user's first name.</li>
	<li><b>$auth_last_name</b> - The logged in user's last name.</li>
</ul>
<h3>Variables Accessible in Controller</h3>
<p>
	When a user is logged in, the same variables that are available in views are set as CI_Controller class members. The exception would be the <b>auth_first_name</b> and <b>auth_last_name</b>. Class members are simply not set from the selected profile columns. This was preferred to using magic methods to get and set class members that aren't hard coded into MY_Controller, which is better for performance, and translates into better flexibility for the application because adding or subtracting fields from the selected profile columns only requires changes to the corresponding array in config/authentication.php.
</p>
<ul class="std-list">
	<li><b>$this->auth_user_id</b> - The logged in user's user ID.</li>
	<li><b>$this->auth_user_name</b> - The logged in user's username.</li>
	<li><b>$this->auth_level</b> - The logged in user's account level by number.</li>
	<li><b>$this->auth_role</b> - The logged in user's account level by name.</li>
	<li><b>$this->auth_email</b> - The logged in user's email address.</li>
</ul>
<h3>Variables Accessible as Config Items</h3>
<p>
	When a user is logged in, the same variables that are available in views and controllers are available as config items. This is handy because they can be accessed in any model or library.
</p>
<ul class="std-list">
	<li><b>config_item('auth_user_id')</b> - The logged in user's user ID.</li>
	<li><b>config_item('auth_user_name')</b> - The logged in user's username.</li>
	<li><b>config_item('auth_level')</b> - The logged in user's account level by number.</li>
	<li><b>config_item('auth_role')</b> - The logged in user's account level by name.</li>
	<li><b>config_item('auth_email')</b> - The logged in user's email address.</li>
</ul>
<p>
	The following config items are set in the example application because the fields are represented in the selected profile columns array:
</p>
<ul class="std-list">
	<li><b>config_item('auth_first_name')</b> - The logged in user's first name.</li>
	<li><b>config_item('auth_last_name')</b> - The logged in user's last name.</li>
</ul>
<h3>More!</h3>
<p>
	There are more ways to use Community Auth, and if you look at MY_Controller.php you will see some functions that you may or may not use. For now, I hope you will have learned enough to get you going. Usage really is quite simple. If you can't figure something out, please ask questions in the <a href="http://codeigniter.com/forums/" rel="external">CodeIgniter Forum</a>.
</p>
<p>
	For paid support, you may also contact me directly at <a href="http://brianswebdesign.com" rel="external">http://brianswebdesign.com</a>.
</p>

<?php

/* End of file usage.php */
/* Location: /application/views/documentation/usage.php */