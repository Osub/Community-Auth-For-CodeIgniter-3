<?php
/**
 * Community Auth - Maintenance Mode Page
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
 
header('HTTP/1.1 503 Service Temporarily Unavailable');
header('Status: 503 Service Temporarily Unavailable');
header('Retry-After: 7200');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Site Down For Maintenance</title>
	<link href="http://<?php echo $public_root; ?>/css/style.css" media="screen" rel="stylesheet" />
</head>
	<body>
		<div id="alert-bar">&nbsp;</div>
		<div class="wrapper">
			<div id="indicator">&nbsp;</div>
			<div class="width-limiter">
				<div id="logo">
					<a href="http://<?php echo $public_root; ?>"><img src="http://<?php echo $public_root; ?>/img/logo.jpg" alt="Community Auth"/></a>
				</div>
				<div id="two-left" class="content">
					<h1>Site Down For Maintenance</h1>
					<p>
						The site is currently down for maintenance, and should be back up shortly. Thank you for your patience.
					</p>
				</div>
				<div id="two-right">
					&nbsp;
				</div>
				<div class="push">&nbsp;</div>
			</div>
		</div>
		<div class="footer">
			<p>Copyright (c) 2011 - <?php echo date('Y'); ?> &bull; Robert B. Gottier &bull; <a href="http://brianswebdesign.com">Brian's Web Design - Temecula, CA</a></p>
		</div>
	</body>
</html>
<?php

/* End of file maintenance-mode.php */
/* Location: /maintenance-mode.php */
