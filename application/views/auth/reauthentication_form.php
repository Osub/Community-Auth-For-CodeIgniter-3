<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Reauthentication Form View
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

echo form_open( '', array( 'class' => 'std-form', 'style' => 'margin-top:20px;' ) ); ?>

	<div class="form-column-left">
		<div class="form-row">
			<label for="login_string" class="form_label">Username or Email</label>
			<input type="text" name="login_string" id="login_string" class="form_input" autocomplete="off" maxlength="255" value="<?php echo set_value('login_string'); ?>" />
		</div>
		<div class="form-row">
			<label for="login_pass" class="form_label">Password</label>
			<input type="password" name="login_pass" id="login_pass" class="form_input password" autocomplete="off" maxlength="<?php echo MAX_CHARS_4_PASSWORD; ?>" value="<?php echo set_value('login_pass'); ?>" />
		</div>
		<div class="form-row">
			<label for="show-password" class="form_label">Show Passwords</label>
			<input type="checkbox" id="show-password" />
		</div>
		<div class="form-row">
			<div id="submit_box">
				<input type="submit" name="submit" value="Submit" id="submit_button"  />
			</div>
		</div>
	</div>
</form>

<?php

/* End of file reauthentication_form.php */
/* Location: /application/views/auth/reauthentication_form.php */ 