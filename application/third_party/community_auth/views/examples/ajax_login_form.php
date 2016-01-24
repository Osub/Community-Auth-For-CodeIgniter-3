<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Ajax Login Form View
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2016, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

?>

<h1>Ajax login</h1>
<p>Open up your javascript console to see what is happening when you try to login</p>

<?php

echo form_open( 'examples/ajax_attempt_login', array( 'class' => 'std-form' ) );

?>

	<div>

		<label for="login_string" class="form_label">Username or Email</label>
		<input type="text" name="login_string" id="login_string" class="form_input" autocomplete="off" maxlength="255" />

		<br />

		<label for="login_pass" class="form_label">Password</label>
		<input type="password" name="login_pass" id="login_pass" class="form_input password" maxlength="<?php echo config_item('max_chars_for_password'); ?>" autocomplete="off" readonly="readonly" onfocus="this.removeAttribute('readonly');" />


		<?php
			if( config_item('allow_remember_me') )
			{
		?>

			<br />

			<label for="remember_me" class="form_label">Remember Me</label>
			<input type="checkbox" id="remember_me" name="remember_me" value="yes" />

		<?php
			}
		?>

		<input type="hidden" id="max_allowed_attempts" value="<?php echo config_item('max_allowed_attempts'); ?>" />
		<input type="hidden" id="mins_on_hold" value="<?php echo ( config_item('seconds_on_hold') / 60 ); ?>" />
		<input type="submit" name="submit" value="Login" id="submit_button"  />

	</div>
</form>

<?php

/* End of file login_form.php */
/* Location: /views/examples/login_form.php */ 