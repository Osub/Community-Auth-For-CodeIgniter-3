<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Recover Form View
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
?>

<h1>Account Recovery</h1>

<?php
if( isset( $disabled ) )
{
	echo '
		<div style="border:1px solid red;">
			<p>
				Account Recovery is Disabled.
			</p>
			<p>
				If you have exceeded the maximum login attempts, or exceeded
				the allowed number of password recovery attempts, account recovery 
				will be disabled for a short period of time. 
				Please wait ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' 
				minutes, or contact us if you require assistance gaining access to your account.
			</p>
		</div>
	';
}
else if( isset( $user_banned ) )
{
	echo '
		<div style="border:1px solid red;">
			<p>
				Account Locked.
			</p>
			<p>
				You have attempted to use the password recovery system using 
				an email address that belongs to an account that has been 
				purposely denied access to the authenticated areas of this website. 
				If you feel this is an error, you may contact us  
				to make an inquiry regarding the status of the account.
			</p>
		</div>
	';
}
else if( isset( $confirmation ) )
{
	echo '
		<div style="border:1px solid green;">
			<p>
				We have sent you an email with instructions on how 
				to recover your username and/or password. Because this email did not 
				really get sent, your link is shown below:
			</p>
			<p>' . $special_link . '</p>
		</div>
	';
}
else if( isset( $no_match ) )
{
	echo '
		<div  style="border:1px solid red;">
			<p class="feedback_header">
				Supplied email did not match any record.
			</p>
		</div>
	';

	$show_form = 1;
}
else
{
	echo '
		<p>
			If you\'ve forgotten your password and/or username, 
			enter the email address used for your account, 
			and we will send you an e-mail 
			with instructions on how to access your account.
		</p>
	';

	$show_form = 1;
}
if( isset( $show_form ) )
{
	?>

		 <?php echo form_open( '' ); ?>
			<div>
				<fieldset>
					<legend>Enter your account's email address:</legend>
					<div>

						<?php
							// EMAIL ADDRESS *************************************************
							echo form_label('Email Address','user_email',array('class'=>'form_label'));

							$input_data = array(
								'name'		=> 'user_email',
								'id'		=> 'user_email',
								'class'		=> 'form_input',
								'maxlength' => 255
							);
							echo form_input($input_data);
						?>

					</div>
				</fieldset>
				<div>
					<div>

						<?php
							// SUBMIT BUTTON **************************************************************
							$input_data = array(
								'name'  => 'submit',
								'id'    => 'submit_button',
								'value' => 'Send Email'
							);
							echo form_submit($input_data);
						?>

					</div>
				</div>
			</div>
		</form>

	<?php
}
/* End of file recover_form.php */
/* Location: /application/views/recover_form.php */