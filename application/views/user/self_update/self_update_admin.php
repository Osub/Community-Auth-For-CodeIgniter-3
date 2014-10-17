<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Admin's Self Update View
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
<div class="form-column-left">
	<fieldset>
		<legend>Account Details:</legend>
		<div class="form-row">

			<?php
				// FIRST NAME LABEL AND INPUT ***********************************
				echo form_label('First Name','first_name',array('class'=>'form_label'));

				echo input_requirement('*');

				$input_data = array(
					'name'		=> 'first_name',
					'id'		=> 'first_name',
					'class'		=> 'form_input first_name',
					'value'		=> set_value('first_name', $user_data->first_name),
					'maxlength'	=> '20',
				);
				
				echo form_input($input_data);

			?>

		</div>
		<div class="form-row">

			<?php
				// LAST NAME LABEL AND INPUT ***********************************
				echo form_label('Last Name','last_name',array('class'=>'form_label'));

				echo input_requirement('*');

				$input_data = array(
					'name'		=> 'last_name',
					'id'		=> 'last_name',
					'class'		=> 'form_input last_name',
					'value'		=> set_value('last_name', $user_data->last_name),
					'maxlength'	=> '20',
				);

				echo form_input($input_data);

			?>

		</div>
		<div class="form-row">

			<?php
				// EMAIL ADDRESS *************************************************
				echo form_label('Email Address','user_email',array('class'=>'form_label'));

				echo input_requirement('*');

				$input_data = array(
					'name'		=> 'user_email',
					'id'		=> 'user_email',
					'class'		=> 'form_input max_chars',
					'maxlength' => 255,
					'value'		=> set_value('user_email', $user_data->user_email )
				);

				echo form_input($input_data);
			?>

		</div>
		<div class="form-row">
			<h3 style="margin:1em 0;color:#bf1e2e;">Leave Blank To Keep Current Password:</h3>

			<?php
				// PASSWORD LABEL AND INPUT ********************************
				echo form_label('Change Password','user_pass',array('class'=>'form_label'));

				echo input_requirement();

				$input_data = array(
					'name'       => 'user_pass',
					'id'         => 'user_pass',
					'class'      => 'form_input password',
					'max_length' => MAX_CHARS_4_PASSWORD
				);

				echo form_password($input_data);
			?>

		</div>
		<div class="form-row">

			<?php
				// CONFIRM PASSWORD LABEL AND INPUT ******************************
				echo form_label('Confirm New Password','user_pass_confirm',array('class'=>'form_label'));

				echo input_requirement();

				$input_data = array(
					'name'       => 'user_pass_confirm',
					'id'         => 'user_pass_confirm',
					'class'      => 'form_input password',
					'max_length' => MAX_CHARS_4_PASSWORD
				);

				echo form_password($input_data);
			?>

		</div>
		<div class="form-row">

			<?php
				// SHOW PASSWORD CHECKBOX
				echo form_label('Show Passwords','show-password',array('class'=>'form_label'));

				echo input_requirement();

				$checkbox_data = array(
					'id' => 'show-password'
				);

				echo form_checkbox( $checkbox_data );
			?>

		</div>
	</fieldset>
	<fieldset>
		<legend>Profile Image</legend>
		<div class="profile_image">
			<?php
				// PROFILE IMAGE
				echo img(
					( ! empty( $user_data->profile_image ) ) ? $user_data->profile_image : 'img/default-profile-image.jpg',
					FALSE,
					( $upload_destination == 'database' && ! empty( $user_data->profile_image ) ) ? TRUE : FALSE
				);

				// DELETE PROFILE IMAGE LINK
				$attrs['id'] = 'delete-profile-image';

				// If there is no profile image uploaded, hide the delete link
				if( empty( $user_data->profile_image ) )
				{
					$attrs['style'] = 'display:none;';
				}

				echo '<br />' . secure_anchor('user/delete_profile_image','Delete Profile Image', $attrs);

				// Get upload config array for display to user
				$upload_config = config_item('upload_configuration_profile_image');
			?>
		</div>
		<div id="upload_details">
			<table class="simple_table">
				<caption>Upload Requirements</caption>
				<tbody>
					<tr>
						<td>Max Image File Size</td>
						<td class="align-right"><?php echo $upload_config['max_size']; ?> kb</td>
					</tr>
					<tr>
						<td>Max Image Width</td>
						<td class="align-right"><?php echo $upload_config['max_width']; ?> px</td>
					</tr>
					<tr>
						<td>Max Image Height</td>
						<td class="align-right"><?php echo $upload_config['max_height']; ?>px</td>
					</tr>
					<tr>
						<td>Allowed File Types</td>
						<td class="align-right"><?php echo str_replace('|',' &bull; ', $upload_config['allowed_types'] ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="form-row">
			<div class="profile-upload-controls">
				<div class="uploader-button">
					<?php
						// PROFILE IMAGE UPLOAD BUTTON
						$button_data = array(
							'id'   => 'file-input',
							'name' => 'userfile'
						);

						echo form_upload( $button_data );
					?>
				</div>
				<div class="uploader-activity-container">
					<img id="uploader-activity" src="img/network_activity.gif" />
				</div>
			</div>
			<input type="hidden" id="user_id" name="user_id" value="<?php echo $user_data->user_id; ?>" />
			<input type="hidden" id="allowed_types" value="<?php echo $upload_config['allowed_types']; ?>" />
			<input type="hidden" id="ci_csrf_token_name" value="<?php echo config_item('csrf_token_name'); ?>" />
			<input type="hidden" id="upload_url" value="<?php echo secure_site_url('uploads_manager/bridge_' . $upload_destination . '/profile_image'); ?>" />
			<input type="hidden" id="delete_url" value="<?php echo secure_site_url('user/delete_profile_image'); ?>" />
		</div>
	</fieldset>
	<div class="form-row">
		<div id="submit_box">

			<?php
				// SUBMIT BUTTON ***********************
				$input_data = array(
					'name'		=> 'submit',
					'id'		=> 'submit_button',
					'value'		=> 'Update'
				);

				echo form_submit($input_data);
			?>

		</div>
	</div>
</div>

<?php
/* End of file self_update_admin.php */
/* Location: /application/views/user/self_update/self_update_admin.php */