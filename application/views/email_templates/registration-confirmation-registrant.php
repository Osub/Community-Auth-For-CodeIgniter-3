<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Registration Confirmation Email View
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

<h1>Registration Confirmation</h1>
<p>
	This email contains a link to confirm your email address, 
	which is part of the registration process for an account 
	at <?php echo WEBSITE_NAME; ?>. Please click the following 
	link below to confirm your registration.
	<br />
	<br />
	<?php 
		echo secure_anchor( 
			'register/email_confirmation/' . $registration_id, 
			secure_base_url() . 'register/email_confirmation/' . $registration_id,
			'target ="_blank" style="color: orange; text-decoration: none;"' 
		); 
	?>
</p>

<?php

/* End of file registration-confirmation-registrant.php */
/* Location: /application/views/email_templates/registration-confirmation-registrant.php */
