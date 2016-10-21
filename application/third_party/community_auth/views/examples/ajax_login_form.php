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

<?php
if( ! isset( $on_hold_message ) )
{
?>

	<p>Open up your javascript console to see what is happening when you try to login</p>

	<?php

	echo form_open( 'examples/ajax_attempt_login', ['class' => 'std-form'] );

	?>

		<div>

			<label for="login_string" class="form_label">Username or Email</label>
			<input type="text" name="login_string" id="login_string" class="form_input" autocomplete="off" maxlength="255" />

			<br />

			<label for="login_pass" class="form_label">Password</label>
			<input type="password" name="login_pass" id="login_pass" class="form_input password" <?php 
				if( config_item('max_chars_for_password') > 0 )
					echo 'maxlength="' . config_item('max_chars_for_password') . '"'; 
			?> autocomplete="off" readonly="readonly" onfocus="this.removeAttribute('readonly');" />


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
}

// EXCESSIVE LOGIN ATTEMPTS ERROR MESSAGE
$error_display = ! isset( $on_hold_message )
	? 'display:none;'
	: '';

echo '
	<div id="on-hold-message" style="border:1px solid red;' . $error_display . '">
		<p>
			Excessive Login Attempts
		</p>
		<p>
			You have exceeded the maximum number of failed login<br />
			attempts that this website will allow.
		<p>
		<p>
			Your access to login and account recovery has been blocked for ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' minutes.
		</p>
		<p>
			Please use the <a href="/examples/recover">Account Recovery</a> after ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' minutes has passed,<br />
			or contact us if you require assistance gaining access to your account.
		</p>
	</div>
';

/* End of file login_form.php */
/* Location: /community_auth/views/examples/ajax_login_form.php */ 