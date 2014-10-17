<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Installation Template View
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
<title>Community Auth Installation</title>
<meta name="robots" content="noindex,nofollow" />
<base href="<?php echo if_secure_base_url(); ?>" />
<?php
	// Always add the main stylesheet
	echo link_tag( array( 'href' => 'css/style.css', 'media' => 'screen', 'rel' => 'stylesheet' ) ) . "\n";

	// jQuery is always loaded
	echo script_tag( '//ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js' ) . "\n";

	// Add any additional javascript
	if( isset( $javascripts ) )
	{
		for( $x=0; $x<=count( $javascripts )-1; $x++ )
		{
			echo script_tag( $javascripts["$x"] ) . "\n";
		}
	}
?>
</head>
<body>
<div id="alert-bar">&nbsp;</div>
<div class="wrapper">
	<div id="indicator">
		<div>
			<?php
				echo ( isset( $auth_user_name ) ) ? 'Welcome, ' . $auth_user_name . ' &bull; ' . secure_anchor('user','User Index') . ' &bull; ' . secure_anchor('user/logout','Logout') : secure_anchor('register','Register') . ' &bull; ' . secure_anchor(LOGIN_PAGE,'Login');
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
			<div id="menu">
				<h3 style="text-align:left;border-bottom:2px solid #fff;margin-bottom:1em;">INSTALLATION NOTES</h3>
				<p style="padding-bottom:5px;border-bottom:dashed 1px #555;margin-bottom:5px;">
					Please make sure to disable or remove the Init Controller immediately after installation.
				</p>
				<p style="padding-bottom:5px;border-bottom:dashed 1px #555;margin-bottom:5px;">
					More than one admin may be created, but they must have different usernames and email addresses.
				</p>
				<p style="padding-bottom:5px;">
					Test users should be created before any other users because their user IDs are not checked for duplicates.
				</p>
			</div>
		</div>
		<div class="push">&nbsp;</div>
	</div>
</div>
<div class="footer">
	<p>Copyright (c) 2011 - <?php echo date('Y'); ?> &bull; Robert B. Gottier &bull; <?php echo anchor('http://brianswebdesign.com','Brian\'s Web Design - Temecula, CA'); ?></p>
</div>
</body>
</html>
<?php

/* End of file installation_template.php */
/* Location: /application/views/templates/installation_template.php */