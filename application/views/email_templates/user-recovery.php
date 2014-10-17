<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - User Recovery Email View
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

<h1>User Account Recovery</h1>
<p>
	This email contains a link to recover your username and 
	password for <?php echo WEBSITE_NAME; ?>. If you are trying to 
	login to <?php echo WEBSITE_NAME; ?>, and cannot remember your 
	username or password, please click on the link below. If you 
	did not request to recover your login information, please 
	respond to this message so we can check your account.
</p>
<p>
	<?php 
		echo secure_anchor( 
			'user/recovery_verification/' . $user_data->user_id . '/' . $recovery_code, 
			secure_base_url() . 'user/recovery_verification/' . $user_data->user_id . '/' . $recovery_code, 
			'target ="_blank" style="color: orange; text-decoration: none;"' 
		);
	?>
</p>

<?php

/* End of file user-recovery.php */
/* Location: /application/views/email_templates/user-recovery.php */