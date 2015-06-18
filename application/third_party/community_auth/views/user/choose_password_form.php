<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Choose Password Form View
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

<h1>Account Recovery - Stage 2</h1>

<?php

$showform = 1;

if( isset( $validation_errors ) )
{
	echo '
		<div style="border:1px solid red;">
			<p>
				The following error occurred while changing your password:
			</p>
			<ul>
				' . $validation_errors . '
			</ul>
			<p>
				PASSWORD NOT UPDATED
			</p>
		</div>
	';
}
else
{
	$display_instructions = 1;
}

if( isset( $validation_passed ) )
{
	echo '
		<div style="border:1px solid green;">
			<p>
				You have successfully changed your password.
			</p>
			<p>
				You can now ' . secure_anchor(LOGIN_PAGE, 'login') . '.
			</p>
		</div>
	';

	$showform = 0;
}
if( isset( $recovery_error ) )
{
	echo '
		<div style="border:1px solid red;">
			<p>
				No usable data for account recovery.
			</p>
			<p>
				Account recovery links expire after 
				' . ( (int) config_item('recovery_code_expiration') / ( 60 * 60 ) ) . ' 
				hours.<br />You will need to use the 
				' . secure_anchor('user/recover','Account Recovery') . ' form 
				to send yourself a new link.
			</p>
		</div>
	';

	$showform = 0;
}
if( isset( $disabled ) )
{
	echo '
		<div style="border:1px solid red;">
			<p>
				Account recovery is disabled.
			</p>
			<p>
				You have exceeded the maximum login attempts or exceeded the 
				allowed number of password recovery attempts. 
				Please wait ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' 
				minutes, or contact us if you require assistance gaining access to your account.
			</p>
		</div>
	';

	$showform = 0;
}
if( $showform == 1 )
{
	if( isset( $user_name, $recovery_code, $user_id ) )
	{
		if( isset( $display_instructions ) )
		{
			echo '
				<p>
					Your login user name is <i>' . $user_name . '</i><br />
					Please write this down, and change your password now:
				</p>
			';
		}

		?>
			<div id="form">
				<?php echo form_open( '' ); ?>
					<fieldset>
						<legend>Step 2 - Choose your new password</legend>
						<div>

							<?php
								// PASSWORD LABEL AND INPUT ********************************
								echo form_label('Password','user_pass',array('class'=>'form_label'));

								$input_data = array(
									'name'       => 'user_pass',
									'id'         => 'user_pass',
									'class'      => 'form_input password',
									'max_length' => config_item('max_chars_for_password')
								);
								echo form_password($input_data);
							?>

						</div>
						<div>

							<?php
								// CONFIRM PASSWORD LABEL AND INPUT ******************************
								echo form_label('Confirm Password','user_pass_confirm',array('class'=>'form_label'));

								$input_data = array(
									'name'       => 'user_pass_confirm',
									'id'         => 'user_pass_confirm',
									'class'      => 'form_input password',
									'max_length' => config_item('max_chars_for_password')
								);
								echo form_password($input_data);
							?>

						</div>
					</fieldset>
					<div>
						<div>

							<?php
								// USER NAME *****************************************************************
								echo form_hidden('user_name',$user_name);

								// OLD PASS *****************************************************************
								echo form_hidden('recovery_code',$recovery_code);

								// USER ID *****************************************************************
								echo form_hidden('user_identification',$user_id);

								// SUBMIT BUTTON **************************************************************
								$input_data = array(
									'name'  => 'form_submit',
									'id'    => 'submit_button',
									'value' => 'Change Password'
								);
								echo form_submit($input_data);
							?>

						</div>
					</div>
				</form>
			</div>
		<?php
	}
}
/* End of file choose_password_form.php */
/* Location: /application/views/choose_password_form.php */