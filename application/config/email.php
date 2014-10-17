<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Example of setting up an SMTP config set
 * ( not used anywhere in the example application )
 */
$config['smtp_example_config'] = array(
	'protocol'     => 'smtp',
	'smtp_host'    => 'mail.example.com',
	'smtp_user'    => 'admin+example.com',
	'smtp_pass'    => 'Gre2&2!289*$V',
	'smtp_port'    => '26',
	'smtp_timeout' => '5',
	'from_email'   => 'admin@example.com',
	'from_name'    => 'Admin',
	'to'           => 'example@gmail.com' 
);

/**
 * Admin Email Config Set
 */
$config['admin_email_config'] = array(
	'from_email' => 'admin@example.com',
	'from_name'  => WEBSITE_NAME
);

/**
 * No Reply Config Set
 */
$config['no_reply_email_config'] = array(
	'from_email' => 'no-reply@example.com',
	'from_name'  => WEBSITE_NAME
);

$config['contact_form_recipient_email_address'] = 'customer-service@example.com';

$config['registration_review_email_address']    = 'registration@example.com';

/* End of file email.php */
/* Location: /application/config/email.php */