<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Notify Admin of Level Up Attempt
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

<h1>Level Up Warning</h1>
<p>
	This email is a notification that a user has tried 
	to maliciously create a user or update a user in a 
	way that would cause that user to gain access to 
	areas of the website that they should not have access to.
	<br />
	<br />
	IP Address associated with the incident: <?php echo $this->input->ip_address(); ?>
	<br />
	Time: <?php echo date('r'); ?>
	<br />
	POST data:
	<br />
	<pre>
		<?php
			foreach( $this->input->post() as $k => $v )
			{
				echo html_escape( $k ) . ' => ' . html_escape( $v ) . '<br />';
			}
		?>
	</pre>
</p>

<?php

/* End of file level-up-warning.php */
/* Location: /application/views/email_templates/level-up-warning.php */