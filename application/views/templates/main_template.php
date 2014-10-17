<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Template Content View
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
?><!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title><?php echo ( isset( $title ) ) ? $title : WEBSITE_NAME; ?></title>
<?php
	// Add any keywords
	echo ( isset( $keywords ) ) ? meta('keywords', $keywords) : '';

	// Add a discription
	echo ( isset( $description ) ) ? meta('description', $description) : '';

	// Add a robots exclusion
	echo ( isset( $no_robots ) ) ? meta('robots', 'noindex,nofollow') : '';
?>
<base href="<?php echo if_secure_base_url(); ?>" />
<?php
	// Always add the main stylesheet
	echo link_tag( array( 'href' => 'css/style.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";

	// Add any additional stylesheets
	if( isset( $style_sheets ) )
	{
		foreach( $style_sheets as $href => $media )
		{
			echo link_tag( array( 'href' => $href, 'media' => $media, 'rel' => 'stylesheet' ) ) . "\n";
		}
	}

	// jQuery is always loaded
	echo script_tag( '//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js' ) . "\n";

	// Add any additional javascript
	if( isset( $javascripts ) )
	{
		for( $x=0; $x<=count( $javascripts )-1; $x++ )
		{
			echo script_tag( $javascripts["$x"] ) . "\n";
		}
	}

	// Add anything else to the head
	echo ( isset( $extra_head ) ) ? $extra_head : '';

	// Add Google Analytics code if available in config
	if( ! empty( $tracking_code ) ) echo $tracking_code; 
?>
</head>
<body id="<?php echo $this->router->fetch_class() . '-' . $this->router->fetch_method(); ?>" class="<?php echo $this->router->fetch_class(); ?>-controller <?php echo $this->router->fetch_method(); ?>-method">
<div id="alert-bar">&nbsp;</div>
<div class="wrapper">
	<div id="indicator">
		<div>
			<?php
				// Check if the user is logged in and on HTTPS
				if( isset( $auth_first_name ) )
				{
					$_user_first_name = $auth_first_name;
				}

				// Show the login / logout ...
				echo ( isset( $_user_first_name ) ) ? 'Welcome, ' . $_user_first_name . ' &bull; ' . secure_anchor('user','User Index') . ' &bull; ' . secure_anchor('user/logout','Logout') : secure_anchor('register','Register') . ' &bull; ' . secure_anchor( LOGIN_PAGE,'Login');
			?>
		</div>
	</div>
	<div class="width-limiter">
		<div id="logo">
			<?php echo anchor('', img( array( 'src' => 'img/logo.jpg', 'alt' => WEBSITE_NAME ) ) )  . "\n"; ?>
		</div>
		<div id="two-left" class="content">
			<?php echo ( isset( $content ) ) ? $content : ''; ?>
		</div>
		<div id="two-right">
			<?php
				if( ! $this->uri->segment(1) )
				{
			?>
				<div id="downloads">
					<h3>Downloads</h3>
					<ul>
						<li><a href="https://bitbucket.org/skunkbad/community-auth/get/tip.zip">Tip <span>(Installed in CodeIgniter)</span></a></li>
						<li><a href="https://bitbucket.org/skunkbad/community-auth/get/<?php echo TAG; ?>.zip"><?php echo TAG; ?> <span>(Installed in CodeIgniter)</span></a></li>
						<li><a href="https://bitbucket.org/skunkbad/community-auth/downloads/community-auth-<?php echo TAG; ?>-isolated.zip"><?php echo TAG; ?> Files Only <span>(Without CodeIgniter)</span></a></li>
					</ul>
				</div>
			<?php
				}
			?>
			<div id="menu">
				<h3>Site Menu</h3>
				<ul>
					<li>
						<?php 
							echo ( $this->uri->segment(1) ) ? anchor('/', 'Home') : anchor('/', 'Home', array( 'id' => 'active' ) );
						?>
					</li>
					<li>
						<a href="https://bitbucket.org/skunkbad/community-auth/downloads">Download</a>
					</li>
					<li>
						<?php 
							echo ( $this->uri->segment(1) == 'license' ) ? anchor('license', 'License', array( 'id' => 'active' ) ) : anchor('license', 'License');
						?>
					</li>
					<li>
						<?php 
							echo ( $this->uri->segment(1) == 'documentation' ) ? anchor('documentation', 'Documentation', array( 'id' => 'active' ) ) : anchor('documentation', 'Documentation');
						?>
					</li>
					<li>
						<?php 
							echo ( $this->uri->segment(1) == 'screenshots' ) ? anchor('screenshots', 'Screenshots', array( 'id' => 'active' ) ) : anchor('screenshots', 'Screenshots');
						?>
					</li>
					<li>
						<?php 
							echo ( $this->uri->segment(1) == 'contact' ) ? secure_anchor('contact', 'Contact', array( 'id' => 'active' ) ) : secure_anchor('contact', 'Contact');
						?>
					</li>
					<?php 
						// If any user is logged in
						if( isset( $auth_level ) )
						{
							echo '<li>';
							echo ( $this->uri->segment(2) == 'self_update' ) ? secure_anchor('user/self_update', 'My Profile', array( 'id' => 'active' ) ) : secure_anchor('user/self_update', 'My Profile');
							echo '</li>';
						}

						// If a manager or admin is logged in
						if( isset( $auth_level ) && $auth_level >= 6 )
						{
							echo '<li>';
							echo ( $this->uri->segment(2) == 'create_user' ) ? secure_anchor('administration/create_user', 'Create User', array( 'id' => 'active' ) ) : secure_anchor('administration/create_user', 'Create User');
							echo '</li>';

							echo '<li>';
							echo ( $this->uri->segment(2) == 'manage_users' OR $this->uri->segment(2) == 'update_user' ) ? secure_anchor('administration/manage_users', 'Manage Users', array( 'id' => 'active' ) ) : secure_anchor('administration/manage_users', 'Manage Users');
							echo '</li>';

							echo '<li>';
							echo ( $this->uri->segment(2) == 'pending_registrations' ) ? secure_anchor('register/pending_registrations', 'Pending Registrations', array( 'id' => 'active' ) ) : secure_anchor('register/pending_registrations', 'Pending Registrations');
							echo '</li>';
						}

						// If an admin is logged in
						if( isset( $auth_level ) && $auth_level == 9 )
						{
							echo '<li>';
							echo ( $this->uri->segment(2) == 'settings' ) ? secure_anchor('register/settings', 'Registration Mode', array( 'id' => 'active' ) ) : secure_anchor('register/settings', 'Registration Mode');
							echo '</li>';

							echo '<li>';
							echo ( $this->uri->segment(2) == 'deny_access' ) ? secure_anchor('administration/deny_access', 'Deny Access', array( 'id' => 'active' ) ) : secure_anchor('administration/deny_access', 'Deny Access');
							echo '</li>';
						}

						// If any user is logged in
						if( isset( $auth_level ) )
						{
							echo '<li>';
							echo ( $this->uri->segment(2) == 'uploader_controls' ) ? secure_anchor('custom_uploader/uploader_controls', 'Custom Uploader', array( 'id' => 'active' ) ) : secure_anchor('custom_uploader/uploader_controls', 'Custom Uploader');
							echo '</li>';

							echo '<li>';
							echo ( $this->uri->segment(1) == 'auto_populate' ) ? secure_anchor('auto_populate', 'Auto Populate', array( 'id' => 'active' ) ) : secure_anchor('auto_populate', 'Auto Populate');
							echo '</li>';

							echo '<li>';
							echo ( $this->uri->segment(1) == 'category_menu' ) ? secure_anchor('category_menu', 'Category Menu', array( 'id' => 'active' ) ) : secure_anchor('category_menu', 'Category Menu');
							echo '</li>';
						}
					?>
				</ul>
			</div>
		</div>
		<div class="push">&nbsp;</div>
	</div>
</div>
<div class="footer">
	<p>Copyright (c) 2011 - <?php echo date('Y') . ' &bull; Robert B. Gottier &bull; ' . anchor('http://brianswebdesign.com','Brian\'s Web Design - Temecula, CA') . ' &bull; ' . anchor('privacy','Privacy Policy'); ?></p>
</div>
<?php
	// Insert any HTML before the closing body tag if desired
	if( isset( $final_html ) )
	{
		echo $final_html;
	}

	// Add the cookie checker
	if( isset( $cookie_checker ) )
	{
		echo $cookie_checker;
	}

	// Add any javascript before the closing body tag
	if( isset( $dynamic_extras ) )
	{
		echo '<script>
		';
		echo $dynamic_extras;
		echo '</script>
		';
	}
?>
</body>
</html>
<?php

/* End of file main_template.php */
/* Location: /application/views/templates/main_template.php */