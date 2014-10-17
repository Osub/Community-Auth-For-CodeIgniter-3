<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Registration Form View
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

<h1>User Registration Application</h1>

<?php
if( $reg_mode == 0 )
{
	echo '
		<div class="feedback reminder">
			<p class="feedback_header">
				Registrations are not allowed at this time.
			</p>
		</div>
	';
}
else
{
	// ERROR MESSAGE OUTPUT ******************************
	if( isset( $validation_errors ) >= 1 )
	{
		echo '
			<div class="feedback error_message">
				<p class="feedback_header">
					The following user registration form fields contained errors:
				</p>
				<ul>
					' . $validation_errors . '
				</ul>
				<p>
					USER NOT REGISTERED
				</p>
			</div>
		';
	}

	// CONFIRMATION MESSAGE - TYPE 1 *********************
	if( isset( $validation_passed ) && $reg_mode == 1 )
	{
		echo '
			<div class="feedback confirmation">
				<p>
					Thank you for registering. You may now ' . secure_anchor(LOGIN_PAGE, 'login') . '.
				</p>
			</div>
		';
	}

	// CONFIRMATION MESSAGE - TYPE 2 *********************
	if( isset( $validation_passed ) && $reg_mode == 2 )
	{
		echo '
			<div class="feedback confirmation">
				<p>
					Please check your email to confirm your account.<br />
					Click the link contained in the email.
				</p>
			</div>
		';
	}

	// CONFIRMATION MESSAGE - TYPE 3 *********************
	if( isset( $validation_passed ) && $reg_mode == 3 )
	{
		echo '
			<div class="feedback confirmation">
				<p>
					Your registration is pending review.<br />
					Please be patient while we process your application.
				</p>
			</div>
		';
	}

	if( ! isset( $validation_passed ) )
	{
		?>

		<?php echo form_open( '', array( 'class' => 'std-form', 'style' => 'margin-top:18px;' ) ); ?>
			<div class="form-column-left">
				<div class="form-row">

					<?php
						// USERNAME LABEL AND INPUT ***********************************************
						echo form_label('Username','user_name',array('class'=>'form_label'));

						echo input_requirement('*');
						
						$input_data = array(
							'name'		=> 'user_name',
							'id'		=> 'user_name',
							'class'		=> 'form_input alpha_numeric',
							'value'		=> set_value('user_name'),
							'maxlength'	=> MAX_CHARS_4_USERNAME
						);
						
						echo form_input($input_data);

					?>

				</div>
				<div class="form-row">

					<?php
						// PASSWORD LABEL AND INPUT ***********************************************
						echo form_label('Password','user_pass',array('class'=>'form_label'));

						echo input_requirement('*');

						$input_data = array(
							'name'		=> 'user_pass',
							'id'		=> 'user_pass',
							'class'		=> 'form_input password',
							'value'		=> set_value('user_pass'),
							'maxlength'	=> MAX_CHARS_4_PASSWORD
						);

						echo form_password($input_data);
					?>

				</div>
				<div class="form-row">

					<?php
						// SHOW PASSWORD CHECKBOX
						echo form_label('Show Password','show-password',array('class'=>'form_label'));

						$checkbox_data = array(
							'id' => 'show-password'
						);

						echo form_checkbox( $checkbox_data );
					?>

				</div>
				<div class="form-row">

					<?php
						// EMAIL ADDRESS LABEL AND INPUT ******************************************
						echo form_label('Email Address','user_email',array('class'=>'form_label'));

						echo input_requirement('*');

						$input_data = array(
							'name'		=> 'user_email',
							'id'		=> 'user_email',
							'class'		=> 'form_input max_chars',
							'value'		=> set_value('user_email'),
							'maxlength'	=> '255',
						);

						echo form_input($input_data);
					?>

				</div>
				<div class="form-row">

					<?php
						// FIRST NAME LABEL AND INPUT ***********************************
						echo form_label('First Name','first_name',array('class'=>'form_label'));

						echo input_requirement('*');

						$input_data = array(
							'name'		=> 'first_name',
							'id'		=> 'first_name',
							'class'		=> 'form_input first_name',
							'value'		=> set_value('first_name'),
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
							'value'		=> set_value('last_name'),
							'maxlength'	=> '20',
						);

						echo form_input($input_data);

					?>

				</div>
				<div class="form-row">

					<?php
						// STREET ADDRESS LABEL AND INPUT ***********************************
						echo form_label('Street Address','street_address',array('class'=>'form_label'));

						echo input_requirement('*');

						$input_data = array(
							'name'		=> 'street_address',
							'id'		=> 'street_address',
							'class'		=> 'form_input max_chars',
							'value'		=> set_value('street_address'),
							'maxlength'	=> '60',
						);

						echo form_input($input_data);

					?>

				</div>
				<div class="form-row">

					<?php
						// CITY LABEL AND INPUT ***********************************
						echo form_label('City','city',array('class'=>'form_label'));

						echo input_requirement('*');

						$input_data = array(
							'name'		=> 'city',
							'id'		=> 'city',
							'class'		=> 'form_input max_chars',
							'value'		=> set_value('city'),
							'maxlength'	=> '60',
						);

						echo form_input($input_data);

					?>

				</div>
				<div class="form-row">

					<?php
						// STATE LABEL AND INPUT ***********************************
						echo form_label('State','state',array('class'=>'form_label'));

						echo input_requirement('*');

						$input_data = array(
							'name'		=> 'state',
							'id'		=> 'state',
							'class'		=> 'form_input max_chars',
							'value'		=> set_value('state'),
							'maxlength'	=> '50',
						);

						echo form_input($input_data);

					?>

				</div>
				<div class="form-row">

					<?php
						// ZIP LABEL AND INPUT ***********************************
						echo form_label('Zip','zip',array('class'=>'form_label'));

						echo input_requirement('*');

						$input_data = array(
							'name'		=> 'zip',
							'id'		=> 'zip',
							'class'		=> 'form_input max_chars',
							'value'		=> set_value('zip'),
							'maxlength'	=> '10',
						);

						echo form_input($input_data);

					?>

				</div>
				<div class="form-row">
					<div id="submit_box">

						<?php
							// SUBMIT BUTTON **********************************************************
							$input_data = array(
								'name'		=> 'submit',
								'id'		=> 'submit_button',
								'value'		=> 'Register'
							);

							echo form_submit($input_data);
						?>

					</div>
				</div>
			</div>
		</form>

	<?php
	}
}

/* End of file registration_form.php */
/* Location: /application/views/register/registration_form.php */