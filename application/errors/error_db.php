<?php

/**
 * Community Auth - Custom DB Error Page.php
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title><?php echo $heading; ?></title>
	<link href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/css/style.css" media="screen" rel="stylesheet" />
</head>
	<body>
		<div id="alert-bar">&nbsp;</div>
		<div class="wrapper">
			<div id="indicator">&nbsp;</div>
			<div class="width-limiter">
				<div id="logo">
					<a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>"><img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/img/logo.jpg" alt="Community Auth"/></a>
				</div>
				<div id="two-left" class="content">
					<h1><?php echo $heading; ?></h1>
					<?php 
						/**
						 * If for some reason database errors are being shown in
						 * the production environment, we don't want to reveal 
						 * too much to the world. Normally, the only error that 
						 * might be seen would be a connection error, and there's
						 * no reason why a site visitor should see the filename
						 * and line number information about the error.
						 */
						if( ENVIRONMENT != 'production' )
						{
							echo $message;
						}
						else
						{
							echo '
								<p>
									Please be patient. We are experiencing technical difficulties, and are working hard to resolve the issue. Service will be restored as soon as possible. Please check back soon.
								</p>
							';
						}
					?>
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
</html><?php

/* End of file error_db.php */
/* Location: ./application/errors/error_db.php */