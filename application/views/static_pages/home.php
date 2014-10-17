<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Home View
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

<h1>More Than Just An Authentication Library</h1>

<?php

	if( isset( $auth_level ) )
	{
		echo '
			<p>
				You are seeing this version of the home page because you are logged in. If you look in the view for this home page, you will see how the variable "$auth_level" is being used to test for this condition. $auth_level is only set if a logged in user is making the request.
			</p>
		';
	}
	else
	{
		?>

			<p>
				Community Auth is a user authentication application for <a href="http://codeigniter.com" rel="external">CodeIgniter</a>. It is completely unique, and not based on anyone's previous work. If you've come here looking for a CodeIgniter authentication library, then please keep in mind that Community Auth is more than just a library. Community Auth is distributed with example controllers, models, views, and should be considered a project foundation. All of this is available 3 ways:
			</p>
			<ol class="std-list">
				<li><b>Tip</b> - The most current code in development. Installed in CodeIgniter <?php echo CI_VERSION; ?> for your convenience. May contain code not fully tested.</li>
				<li><b><?php echo TAG; ?></b> - The last tagged version in the repository. Installed in CodeIgniter <?php echo CI_VERSION; ?> for your convenience. Fully tested.</li>
				<li><b><?php echo TAG; ?> Files Only</b> - Isolated code not installed in CodeIgniter. Downloaded files are manually merged with your existing CodeIgniter application. Only suitable for experienced developers.</li>
			</ol>
			<h2>Core Authentication Features</h2>
			<ul class="std-list">
				<li>User Authentication <i>(User Login)</i></li>
				<li>Access Granted by Level / Role</li>
				<li>Access Granted by Role Group</li>
				<li>Content Switching in Views</li>
				<li>Limits Failed Login Attempts</li>
				<li>Limits Login to a Single Device <i>(Default)</i></li>
				<li>Deny Access by IP <i>(Requires Local Apache Configuration File)</i></li>
				<li>Persistent Login <i>(Remember Me) (Turned Off by Default)</i></li>
				<li>Forgotten Password and Username Recovery</li>
				<li>New User Registration
					<ul class="std-list">
						<li>Off by Default</li>
						<li>Instant Registration Mode</li>
						<li>Registration by Email Verification Mode</li>
						<li>Registration by Admin Approval Mode</li>
					</ul>
				</li>
				<li>User Account Update
					<ul class="std-list">
						<li>Self Update</li>
						<li>Update by Superior Role</li>
					</ul>
				</li>
			</ul>
			<h3>Built-in Debugging &amp; Time Saving Examples</h3>
			<p>
				Community Auth also makes for a great learning tool for developers who are new to CodeIgniter. <a href="http://www.firephp.org" rel="external">FirePHP</a> is included and autoloaded in the distribution, and by installing <a href="http://getfirebug.com" rel="external">Firebug</a>, and either the FirePHP or <a href="http://developercompanion.com/" rel="external">DeveloperCompanion</a> extensions for Firefox, you've got an impressive debugging tool at your disposal. For those of you who prefer Chrome, <a href="http://www.chromephp.com/" rel="external">ChromePHP</a> is also loaded in both development and testing environments. Community Auth also shows examples of extending the CodeIgniter controller class, extending other CodeIgniter libraries, overriding CodeIgniter helpers, pagination, and ajax using <a href="http://jquery.com" rel="external">jQuery</a>. An <a href="https://github.com/valums/ajax-upload">ajax uploader</a> shows you how to upload images and store them on the filesystem or in the database. A "custom uploader" shows you how to use jQuery UI's draggable, droppable, and sortable, and all at the same time. There's more too.
			</p>
			<p>
				If you've downloaded Community Auth and are checking it out, thank you for giving it a try. I'm pleased to share this application with you, and hopefully we can work together to make it especially useful for your CodeIgniter projects. It's been something I've worked on over the last couple of years, and you are getting the benefit of hundreds of hours of programming time and experience.
			</p>
			<h3>Repositories</h3>
			<p>
				Community Auth currently has repositories at <a href="https://bitbucket.org/skunkbad/community-auth" rel="external">Bitbucket</a> and <a href="https://github.com/skunkbad/Community-Auth">GitHub</a>.
			</p>
			<h3>Quick Start</h3>
			<p>If you're an experienced user of CodeIgniter, you can get started quite easily.</p>
			<ul class="std-list">
				<li>
					Make sure to look through all of the config files, and set all configuration options according to the description in the comments. Community Auth uses some config files that are not part of CodeIgniter's standard set, but also has custom additions to the constants.php config file.
				</li>
				<li>Create a database.</li>
				<li>
					Edit then run the <?php echo secure_anchor('init', 'init controller'); ?>. This populates the database and creates the admin. You may optionally create a set of test users while running this installer. Please make sure to disable or remove this controller and the db.sql file once completed.
				</li>
			</ul>
			<p>For more detailed instructions on how to install, configure, and use Community Auth, please check out the <?php echo anchor('documentation','documentation'); ?> for Community Auth, which takes you step by step through the process of getting up and running.</p>
			<h3>Support</h3>
			<p>
				If you need help, feel free to start a new thread in the <a href="http://codeigniter.com/forums/" rel="external">CodeIgniter Forum</a>. I'm "skunkbad" in the forum, and I'll help whenever possible. Due to my busy work schedule, I may not always be able to help. Work does come first, and that said, if you are willing to pay for support, please feel free to contact me on my website, <a href="http://brianswebdesign.com" rel="external">http://brianswebdesign.com</a>.
			</p>
			<h3>License</h3>
			<p>
				Community Auth is licensed under the <a href="http://www.opensource.org/licenses/BSD-3-Clause" rel="external">BSD License</a>.
			</p>
			<p>
				Read the license <?php echo anchor('license', 'HERE'); ?>.
			</p>

		<?php
	}

?>

<p style="padding-top:18px;font-size:85%;color:#777;">
	Page rendered in {elapsed_time} seconds
</p>

<?php

/* End of file home.php */
/* Location: /application/views/home/home.php */