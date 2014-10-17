<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Auto Populate View
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

<h1>Auto Population of Form Dropdowns</h1>
<p>
	This is just a simple example to show how to dynamically populate form dropdowns using jQuery. There are a lot of questions in the CodeIgniter forum asking how to do this, and for my own projects I felt the need to perfect this task. While there is no form validation for this example, most everything else is complete so you can have a solid working example for your own needs.
</p>
<?php echo form_open( '', array( 'class' => 'std-form', 'style' => 'margin-top:24px;' ) ); ?>
	<div class="form-column-left">
		<fieldset>
			<legend>Select Vehicle:</legend>
			<div class="form-row">

				<?php
					// VEHICLE TYPE LABEL AND INPUT ***********************************
					echo form_label('Vehicle Type','type',array('class'=>'form_label'));

					echo input_requirement();

					// Default option
					$vehicle_types[] = '-- Select --';

					// Options from query
					foreach( $types as $row )
					{
						$vehicle_types[$row->type] = $row->type;
					}

					echo form_dropdown( 'type', $vehicle_types, set_value('type'), 'id="type" class="form_select"' );

				?>

			</div>
			<div class="form-row">

				<?php
					// VEHICLE MAKE LABEL AND INPUT ***********************************
					echo form_label('Vehicle Make','make',array('class'=>'form_label'));

					echo input_requirement();

					// If POST, there may be vehicle makes
					if( isset( $makes ) )
					{
						// Default option
						$vehicle_makes[] = '-- Select --';

						// Options from query
						foreach( $makes as $row )
						{
							$vehicle_makes[$row['make']] = $row['make'];
						}
					}
					else
					{
						// Default option if not POST request
						$vehicle_makes[] = '-- Select Type --';
					}

					echo form_dropdown( 'make', $vehicle_makes, set_value('make'), 'id="make" class="form_select"' );

				?>

			</div>
			<div class="form-row">

				<?php
					// VEHICLE MODEL LABEL AND INPUT ***********************************
					echo form_label('Vehicle Model','model',array('class'=>'form_label'));

					echo input_requirement();

					// If POST, there may be vehicle models
					if( isset( $models ) && ! empty( $models ) )
					{
						// Default option
						$vehicle_models[] = '-- Select --';

						// Options from query
						foreach( $models as $row )
						{
							$vehicle_models[$row['model']] = $row['model'];
						}
					}

					// If POST and makes not empty
					else if( isset( $makes ) && ! empty( $makes ) )
					{
						$vehicle_models[] = '-- Select Make --';
					}
					else
					{
						// Default option if not POST request
						$vehicle_models[] = '-- Select Type --';
					}

					echo form_dropdown( 'model', $vehicle_models, set_value('model'), 'id="model" class="form_select"' );

				?>

			</div>
			<div class="form-row">

				<?php
					// VEHICLE COLOR LABEL AND INPUT ***********************************
					echo form_label('Vehicle Color','color',array('class'=>'form_label'));

					echo input_requirement();

					// If POST, there may be vehicle models
					if( isset( $colors ) && ! empty( $colors ) )
					{
						// Default option
						$vehicle_colors[] = '-- Select --';

						// Options from query
						foreach( $colors as $row )
						{
							$vehicle_colors[$row['color']] = $row['color'];
						}
					}

					// If POST and models not empty
					else if( isset( $models ) && ! empty( $models ) )
					{
						$vehicle_colors[] = '-- Select Model --';
					}
					// If POST and makes not empty
					else if( isset( $makes ) && ! empty( $makes ) )
					{
						$vehicle_colors[] = '-- Select Make --';
					}
					else
					{
						// Default option if not POST request
						$vehicle_colors[] = '-- Select Type --';
					}

					echo form_dropdown( 'color', $vehicle_colors, set_value('color'), 'id="color" class="form_select"' );

				?>

			</div>
			<input type="hidden" id="ci_csrf_token_name" value="<?php echo config_item('csrf_token_name'); ?>" />
			<input type="hidden" id="ajax_url" value="<?php echo if_secure_site_url('auto_populate/process_request/example'); ?>" />
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

/* End of file auto_populate.php */
/* Location: /application/views/auto_populate/auto_populate.php */