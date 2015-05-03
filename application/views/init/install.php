<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Installation View
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

<h1>Installation</h1>
<?php
	// Show errors if there were any
	if( isset( $error_message_stack ) OR isset( $validation_errors ) )
	{
		echo '
			<div class="feedback error_message">
				<p class="feedback_header">
					Sorry, the following errors occured during the installation procedure:
				</p>
				<ul>';

		// Errors might be in an array or a string
		if( isset( $error_message_stack ) && is_array( $error_message_stack ) )
		{
			foreach( $error_message_stack as $error )
			{
				echo $error;
			}
		}
		else if( isset( $validation_errors ) )
		{
			echo $validation_errors;
		}
		else
		{
			echo $error_message_stack;
		}

		echo '	</ul>
			</div>
		';
	}
?>
<p style="margin-top:2em;">
	<?php
		// Show the status of table population in the database
		if( $tables_installed )
		{
			echo '<img src="img/green-check.jpg" alt="Green Check" /> Database populated with tables.';
		}
		else
		{
			echo '<img src="img/red-x.jpg" alt="Red X" /> Database not populated with tables.';
		}
	?>
</p>
<p>
	<?php
		// Show the status of the existance of Admin
		if( $admin_created )
		{
			echo '<img src="img/green-check.jpg" alt="Green Check" /> Admin Created.';
		}
		else
		{
			echo '<img src="img/red-x.jpg" alt="Red X" /> Admin not created.';
		}
	?>
</p>
<p>
	<?php
		// Show the number of non-admin users
		if( isset( $basic_user_count ) && $basic_user_count > 0 )
		{
			$s = ( $basic_user_count == 1 ) ? '' : 's';

			echo '<img src="img/green-check.jpg" alt="Green Check" /> ' . $basic_user_count . ' user' . $s . ' have been created ( admin not included in count ).';
		}
		else
		{
			echo '<img src="img/red-x.jpg" alt="Red X" /> 0 users have been created ( admin not included in count ).';
		}
	?>
</p>
<?php
	echo form_open( '', array( 'class' => 'std-form' ) );
?>
	<div class="form-column-left" style="margin-top:2em;">
		<fieldset>
			<legend>Installation Controls:</legend>
			<?php
				/**
				 * If tables are not already installed, they must 
				 * be installed first to avoid validation errors 
				 * because the username and email address are checked
				 * to ensure that they don't already exist.
				 */
				if( ! $tables_installed )
				{
			?>
				<div class="form-row">

					<?php
						// POPULATE DATABASE TABLES
						echo form_label('Populate Database w/ Tables','populate_database',array('class'=>'form_label','style'=>'width: 300px;'));

						$input_data = array(
							'name'		=> 'populate_database',
							'id'		=> 'populate_database',
							'style'		=> 'float:right;width:20px;margin-top:4px;',
							'value'		=> '1'
						);

						if( $tables_installed )
						{
							$input_data['disabled'] = 'disabled';
						}

						echo form_checkbox($input_data);

					?>

				</div>
			<?php
				}

				// The tables already exist, so allow admin or test users to be created
				else
				{
			?>
				<div class="form-row">

					<?php
						// CREATE ADMIN
						echo form_label('Create Admin','admin',array('class'=>'form_label','style'=>'width: 300px;'));

						$input_data = array(
							'name'		=> 'admin',
							'id'		=> 'admin',
							'style'		=> 'float:right;width:20px;margin-top:4px;',
							'value'		=> '1'
						);

						echo form_checkbox($input_data);

					?>

				</div>
				<div id="admin_fields">
					<div class="fieldset-divider"></div>
					<div class="form-row">

						<?php
							// ADMIN USERNAME LABEL AND INPUT ***********************************
							echo form_label('Admin Username','user_name',array('class'=>'form_label','style'=>'width: 181px;'));

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
							// ADMIN PASSWORD LABEL AND INPUT ***********************************
							echo form_label('Admin Password','user_pass',array('class'=>'form_label','style'=>'width: 181px;'));

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
							// ADMIN EMAIL ADDRESS LABEL AND INPUT ******************************
							echo form_label('Admin Email Address','user_email',array('class'=>'form_label','style'=>'width: 181px;'));

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
							// ADMIN FIRST NAME LABEL AND INPUT ***********************************
							echo form_label('Admin First Name','first_name',array('class'=>'form_label','style'=>'width: 181px;'));

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
							// ADMIN LAST NAME LABEL AND INPUT ***********************************
							echo form_label('Admin Last Name','last_name',array('class'=>'form_label','style'=>'width: 181px;'));

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
					<div class="fieldset-divider"></div>
				</div>
				<?php
					// Test users can only be created once
					if( ! isset( $basic_user_count ) OR empty( $basic_user_count ) )
					{
				?>
					<div class="form-row">

						<?php
							// CREATE TEST USERS
							echo form_label('Create Test Users','users',array('class'=>'form_label','style'=>'width: 300px;'));

							$input_data = array(
								'name'		=> 'users',
								'id'		=> 'users',
								'style'		=> 'float:right;width:20px;margin-top:4px;',
								'value'		=> '1'
							);

							echo form_checkbox($input_data);

						?>

					</div>
					<div id="test_users_fields">
						<div class="fieldset-divider"></div>
						<div class="form-row">

							<?php
								// TEST USERS PASSWORD LABEL AND INPUT ***********************************
								echo form_label('Test Users Password','test_users_pass',array('class'=>'form_label','style'=>'width: 181px;'));

								echo input_requirement('*');

								$input_data = array(
									'name'		=> 'test_users_pass',
									'id'		=> 'test_users_pass',
									'class'		=> 'form_input password',
									'value'		=> set_value('test_users_pass'),
								);

								echo form_password( $input_data );
							?>

						</div>
					<div>
			<?php
					}
				}
			?>
		</fieldset>
		<div class="form-row">
			<div id="submit_box" style="width:100%;">

				<?php
					// SUBMIT BUTTON
					$input_data = array(
						'name'		=> 'form_submit',
						'id'		=> 'submit_button',
						'value'		=> 'Run'
					);
					echo form_submit($input_data);
				?>

			</div>
		</div>
	</div>

</form>

<?php
/* End of file install.php */
/* Location: /application/views/administration/install.php */