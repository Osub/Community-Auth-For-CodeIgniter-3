<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Documentation of Installation View
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

<h1>Documentation of Installation</h1>
<ul class="std-list">
	<li><?php echo anchor('documentation/configuration', 'Configuration'); ?></li>
	<li><?php echo anchor('documentation/installation', 'Installation'); ?></li>
	<li><?php echo anchor('documentation/usage', 'Usage'); ?></li>
</ul>
<h2>Installation</h2>
<p>
	<b>Installation of Community Auth is super easy.</b> Most of the installation of Community Auth is the same as <a href="http://codeigniter.com/user_guide/installation/index.html" rel="external">installing CodeIgniter</a>. The main difference is that you must create and populate a MySQL database for Community Auth, while CodeIgniter does not require this during installation. Because CodeIgniter installation by itself can be problematic, please go to the <a href="http://codeigniter.com/forums/" rel="external">CodeIgniter Forum</a> and ask for help if are having problems.
</p>
<p>
	Note: You will not need to create a session table when you install CodeIgniter. The Community Auth installer creates the session table whether you use it or not. Also, having tables in an existing database will cause the Community Auth installer to fail. If you have an existing application and existing tables, you would need to backup your database, wipe it clean, run the Community Auth installer, then add your tables back to the database. It sounds like a pain, but your decision to install an authentication library or application as an afterthought has brought about this unfortunate scenario.
</p>
<h3>Run Init Controller</h3>
<p>
	If you haven't done so already, edit the authentication config file located at <b>/application/config/authentication.php</b> in your text editor. Set the <b>Disable Installer</b> setting to FALSE.
</p>
<p>
	Browse to the <?php echo secure_anchor('init', 'init controller'); ?>, and use the interface to populate your database with tables, create your admin, or create a set of test users.
</p>
<p style="color:red;font-weight:bold;">
	Please disable or remove the init controller <br />and the sql file when you are finished.<br />Change the Disable Installer setting back to TRUE.
</p>
<p style="font-size:85%;">
	You might consider running the installer in maintenance mode. By doing this, regular site visitors will not see the warning in the alert bar.
<p>
<h3>Final Installation Notes</h3>
<p>
	The <b>/application/config/config.php</b> configuration file distributed with Community Auth has the 'index_page' setting set as blank, which is not the default setting for CodeIgniter, but is set that way because of Community Auth's attempt to utilize mod_rewrite for the removal of 'index.php' from all URLs. If you don't have mod_rewrite enabled and you experience problems with installation or usage, you should change the value of 'index_page' back to 'index.php'.
</p>
<p>
	Support for Apache configuration settings, mod_rewrite, or other server configuration is out of the scope of the documentation for Community Auth. Again, please go to the <a href="http://codeigniter.com/forums/" rel="external">CodeIgniter Forum</a> and ask for help if are having problems, since these types of problems are more related to CodeIgniter installation than Community Auth installation. 
</p>
<p>
	Proceed to <?php echo anchor('documentation/usage', 'usage'); ?> of Community Auth.
</p>

<?php

/* End of file installation.php */
/* Location: /application/views/documentation/installation.php */