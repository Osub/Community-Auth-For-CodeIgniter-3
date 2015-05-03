<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Excessive Login Attempts Email View
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

<h1>Excessive Login Attempts Warning</h1>
<p>
	You are advised to login to the <?php echo WEBSITE_NAME; ?> website 
	to investigate excessive login attempts, which may have caused an IP 
	address to be added to the deny list.
	<br />
	<br />
	The associated IP address is: <?php echo $this->input->ip_address(); ?> 
	<br />
	<br />
	The following data was posted: 
	<br />
	<br />
	<?php
		echo '<pre>' . htmlentities( print_r( $_POST, TRUE ) ) . '</pre>';
	?>
</p>

<?php

/* End of file excessive-login-attempts.php */
/* Location: /application/views/email_templates/excessive-login-attempts.php */