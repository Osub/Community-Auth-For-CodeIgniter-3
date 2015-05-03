<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Contact View
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

<h1>Contact</h1>

<?php

	if( isset( $confirmation ) )
	{
		echo '
			<div class="feedback confirmation">
				<p class="feedback_header">
					Thank you for sending your message.<br />
					' . WEBSITE_NAME . ' will respond if required.
				</p>
			</div>
		';
	}
	else if( isset( $offline ) )
	{
		echo '
			<div class="feedback reminder">
				<p class="feedback_header">
					The message form is temporarily offline, and not functional.
				</p>
				<p style="margin:.6em 0 .3em 0;">
					If you need help with Community Auth, have comments, or would like to make a suggestion, please either submit a new issue at the Community Auth repository on <a href="https://bitbucket.org/skunkbad/community-auth-git-version/issues/new">Bitbucket</a>, or post to the <a href="http://forum.codeigniter.com">CodeIgniter forum</a>.
				</p>
			</div>
		';
	}
	else if( isset( $error_message_stack ) )
	{
		echo '
			<div class="feedback error_message">
				<p class="feedback_header">
					Your Contact Form Submission Contained The Following Errors:
				</p>
				<ul>
					' . $error_message_stack . '
				</ul>
				<p>
					YOUR MESSAGE WAS NOT SENT
				</p>
			</div>
		';
	}

	echo form_open( '', array( 'class' => 'std-form', 'style' => 'margin-top:24px;' ) ); ?>
	<div class="form-column-left">
		<fieldset>
			<legend>Please complete all fields:</legend>
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
					
					echo form_input( $input_data );

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

					echo form_input( $input_data );

				?>

			</div>
			<div class="form-row">

				<?php
					// EMAIL ADDRESS *************************************************
					echo form_label('Email Address','email',array('class'=>'form_label'));

					echo input_requirement('*');

					$input_data = array(
						'name'		=> 'email',
						'id'		=> 'email',
						'class'		=> 'form_input max_chars',
						'maxlength' => 255,
						'value'		=> set_value('email')
					);

					echo form_input( $input_data );
				?>

			</div>
			<div class="form-row">

				<?php
					// MESSAGE LABEL AND INPUT **************************
					echo form_label('Your Message','message',array('class'=>'textarea_label'));

					echo input_requirement('*');

					$textarea_data = array(
						'name'		=> 'message',
						'id'		=> 'message',
						'class'		=> 'form_textarea max_chars',
						'value'		=> set_value('message'),
						'rows'		=> '8',
						'cols'		=> '14'
					);
					echo form_textarea( $textarea_data );
				?>

			</div>
		</fieldset>
		<div class="form-row">
			<div id="submit_box">

				<?php
					// SUBMIT BUTTON ***********************
					$input_data = array(
						'name'		=> 'submit',
						'id'		=> 'submit_button',
						'value'		=> 'Submit'
					);

					echo form_submit($input_data);
				?>

			</div>
		</div>
	</div>
</form>

<?php

/* End of file contact.php */
/* Location: /application/views/contact/contact.php */