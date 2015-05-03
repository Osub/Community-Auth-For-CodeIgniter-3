<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Contact Email View
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

<table border='0'>
	<tr style='background-color:#efefef;'>
		<td style='width:100px; padding:8px; color:#bf1e2e;'><b>From:</b></td>
		<td style='padding:8px;'><b><?php echo $first_name . ' ' . $last_name; ?></b></td>
	</tr>
	<tr style='background-color:#e5dfff;'>
		<td style='padding:8px; color:#bf1e2e;'><b>Email address:</b></td>
		<td style='padding:8px;'><b><?php echo $email; ?></b></td>
	</tr>
	<tr style='background-color:#efefef;'>
		<td style='padding:8px; color:#bf1e2e;' valign='top'><b>Message:</b></td>
		<td style='padding:8px;'><b><?php echo nl2br( $message ); ?></b></td>
	</tr>
	<tr style='background-color:#e5dfff;'>
		<td>&nbsp;</td>
		<td style='padding:8px;'><b><?php echo $this->input->server('REMOTE_ADDR') . "<br />" . $this->input->server('HTTP_USER_AGENT') ?></b></td>
	</tr>
</table>

<?php

/* End of file contact.php */
/* Location: /application/views/email_templates/contact.php */