<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Registration Settings View
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

<h1>Registration Settings</h1>

<?php
	$reg_mode = array(
		'0' => 'ALL REGISTRATION CAPABILITY OFF',
		'1' => 'REGISTRATION ALLOWED W/O VERIFICATION',
		'2' => 'REGISTRATION ALLOWED W/ EMAIL VERIFICATION',
		'3' => 'REGISTRATION ALLOWED BY ADMIN APPROVAL'
	);

	if( isset( $confirmation ) )
	{
		echo '<div class="feedback confirmation">
				<p class="feedback_header">
					The registration setting has been updated. Setting is now:
				</p>
				<p style="margin:.6em 0 0 0;">
					<b>' . $reg_mode["$reg_setting"] . '</b>
				</p>
			</div>';
	}
?>

<?php echo form_open( '', array( 'class' => 'std-form', 'style' => 'margin-top:24px;' ) ); ?>
	<div class="form-column-left">
		<fieldset>
			<legend>Registration Mode:</legend>
			<div class="form-row">
				<div class="radio-header">Modes:</div>
				<div class="radio_set">
					<div class="form-row">
						<div class="form_radio_container">

							<?php
								// FIRST RADIO
								$radio_data = array(
									'name'		=> 'reg_setting',
									'id'		=> 'setting-off',
									'value'		=> '0',
									'checked'	=> (isset($reg_setting) && $reg_setting == '0')? 'checked' : '',
									'class'		=> 'form_radio'
								);
								echo form_radio($radio_data);
							?>

						</div>

						<?php
							echo form_label('Off', 'setting-off', array('class'=>'radio_label'));
						?>

					</div>
					<div class="form-row">
						<div class="form_radio_container">

							<?php
								// SECOND RADIO
								$radio_data = array(
									'name'		=> 'reg_setting',
									'id'		=> 'setting-instant',
									'value'		=> '1',
									'checked'	=> (isset($reg_setting) && $reg_setting == '1')? 'checked' : '',
									'class'		=> 'form_radio'
								);

								echo form_radio($radio_data);
							?>

						</div>

						<?php
							echo form_label('Instant', 'setting-instant', array('class'=>'radio_label'));
						?>

					</div>
					<div class="form-row">
						<div class="form_radio_container">

							<?php
								// THIRD RADIO
								$radio_data = array(
									'name'		=> 'reg_setting',
									'id'		=> 'setting-email',
									'value'		=> '2',
									'checked'	=> (isset($reg_setting) && $reg_setting == '2')? 'checked' : '',
									'class'		=> 'form_radio'
								);

								echo form_radio($radio_data);
							?>

						</div>

						<?php
							echo form_label('Email Verification', 'setting-email', array('class'=>'radio_label'));
						?>

					</div>
					<div class="form-row">
						<div class="form_radio_container">

							<?php
								// THIRD RADIO
								$radio_data = array(
									'name'		=> 'reg_setting',
									'id'		=> 'setting-admin',
									'value'		=> '3',
									'checked'	=> (isset($reg_setting) && $reg_setting == '3')? 'checked' : '',
									'class'		=> 'form_radio'
								);

								echo form_radio($radio_data);
							?>

						</div>

						<?php
							echo form_label('Admin Approval', 'setting-admin', array('class'=>'radio_label'));
						?>

					</div>
				</div>
			</div>
		</fieldset>
		<div class="form-row">
			<div id="submit_box">

				<?php
					// SUBMIT BUTTON **************************************************************
					$input_data = array(
						'name'		=> 'reg_submit',
						'id'		=> 'submit_button',
						'value'		=> 'Save'
					);
					echo form_submit($input_data);
				?>

			</div>
		</div>
	</div>
</form>

<?php
/* End of file settings.php */
/* Location: .system/application/views/register/settings.php */
