<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Self Update View
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

<h1>My Profile</h1>

<?php

	if( isset( $validation_errors ) )
	{
		echo '
			<div class="feedback error_message" style="margin-bottom:10px;">
				<p class="feedback_header">
					Profile Update Contained The Following Errors:
				</p>
				<ul>
					' . $validation_errors . '
				</ul>
				<p>
					PROFILE NOT UPDATED
				</p>
			</div>
		';
	}
	else if( isset( $validation_passed ) )
	{
		echo '
			<div class="feedback confirmation" style="margin-bottom:10px;">
				<p class="feedback_header">
					Your profile has been successfully updated.
				</p>
			</div>
		';
	}

	if( $user_data !== FALSE )
	{

?>
<div class="profile_image">
	<?php
		// PROFILE IMAGE
		echo img(
			( ! empty( $user_data->profile_image ) ) ? $user_data->profile_image : 'img/default-profile-image.jpg',
			FALSE,
			( $upload_destination == 'database' && ! empty( $user_data->profile_image ) ) ? TRUE : FALSE
		);
	?>
</div>
<div id="user_info">
	<h3><?php echo $user_data->user_name; ?></h3>
	<ul class="std-list">
		<li>
			Registration Date: <?php echo  date('F j, Y, g:i a',$user_data->user_date); ?>
		</li>
		<li>
			Last Modified: <?php echo  date('F j, Y, g:i a',$user_data->user_modified); ?>
		</li>
		<li>
			Last Login: <?php echo ($user_data->user_last_login != FALSE)? date('F j, Y, g:i a',$user_data->user_last_login) : '<span class="redfield">NEVER LOGGED IN</span>'; ?>
		</li>
	</ul>
</div>

<?php 
	echo form_open_multipart( 'user/self_update', array( 'class' => 'std-form' ) ); 

	echo $role_specific_form;

	echo form_close();

	}
	else
	{
		echo 'Error: No user data available.';
	}

/* End of file self_update.php */
/* Location: /application/views/self_update.php */