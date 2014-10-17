<?php echo form_open( '', array( 'class' => 'std-form', 'style' => 'margin-top:24px;' ) ); ?>
	<div class="form-column-left">
		<fieldset>
			<legend>Manager Information:</legend>
			<div class="form-row">

				<?php
					// USERNAME LABEL AND INPUT ***********************************
					echo form_label('Username','user_name',array('class'=>'form_label'));

					echo input_requirement('*');

					$input_data = array(
						'name'		=> 'user_name',
						'id'		=> 'user_name',
						'class'		=> 'form_input alpha_numeric',
						'value'		=> set_value('user_name'),
						'maxlength'	=> MAX_CHARS_4_USERNAME,
					);

					echo form_input( $input_data );

				?>

			</div>
			<div class="form-row">

				<?php
					// PASSWORD LABEL AND INPUT ***********************************
					echo form_label('Password','user_pass',array('class'=>'form_label'));

					echo input_requirement('*');

					$input_data = array(
						'name'		=> 'user_pass',
						'id'		=> 'user_pass',
						'class'		=> 'form_input password',
						'value'		=> set_value('user_pass'),
					);

					echo form_password( $input_data );
				?>

			</div>
			<div class="form-row">

				<?php
					// SHOW PASSWORD CHECKBOX
					echo form_label('Show Password','show-password',array('class'=>'form_label'));

					echo input_requirement();

					$checkbox_data = array(
						'id' => 'show-password'
					);

					echo form_checkbox( $checkbox_data );
				?>

			</div>
			<div class="form-row">

				<?php
					// EMAIL ADDRESS LABEL AND INPUT ******************************
					echo form_label('Email Address','user_email',array('class'=>'form_label'));

					echo input_requirement('*');

					$input_data = array(
						'name'		=> 'user_email',
						'id'		=> 'user_email',
						'class'		=> 'form_input max_chars',
						'value'		=> set_value('user_email'),
						'maxlength'	=> '255',
					);

					echo form_input( $input_data );
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
					// LICENSE NUMBER LABEL AND INPUT ***********************************
					echo form_label('License Number','license_number',array('class'=>'form_label'));

					echo input_requirement('*');

					$input_data = array(
						'name'		=> 'license_number',
						'id'		=> 'license_number',
						'class'		=> 'form_input alpha_numeric',
						'value'		=> set_value('license_number'),
						'maxlength'	=> '8',
					);

					echo form_input($input_data);

				?>

			</div>
			<div class="form-row">

				<?php
					// PHONE NUMBER LABEL AND INPUT ***********************************
					echo form_label('Phone Number','phone_number',array('class'=>'form_label'));

					echo input_requirement('*');

					$input_data = array(
						'name'		=> 'phone_number',
						'id'		=> 'phone_number',
						'class'		=> 'form_input max_chars',
						'value'		=> set_value('phone_number'),
						'maxlength'	=> '20',
					);

					echo form_input($input_data);

				?>

			</div>
		</fieldset>
		<div class="form-row">
			<div id="submit_box">

				<?php
					// SUBMIT BUTTON **************************************************************
					$input_data = array(
						'name'		=> 'form_submit',
						'id'		=> 'submit_button',
						'value'		=> 'Create User'
					);
					echo form_submit($input_data);
				?>

			</div>
		</div>
	</div>
</form>

<?php

/* End of file create_manager.php */
/* Location: /application/views/administration/create_user/create_manager.php */