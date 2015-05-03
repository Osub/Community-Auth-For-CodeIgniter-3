<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Login Form View
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

if( ! isset( $optional_login ) )
{
	echo '<h1>Login</h1>';
}

if( ! isset( $on_hold_message ) )
{
	if( isset( $login_error_mesg ) )
	{
		echo '
			<div class="feedback error_message">
				<p class="feedback_header">
					Login Error: Invalid Username, Email Address, or Password.
				</p>
				<p style="margin:.4em 0 0 0;">
					Username, email address and password are all case sensitive.
				</p>
			</div>
		';
	}

	if( $this->input->get('logout') )
	{
		echo '
			<div class="feedback confirmation">
				<p class="feedback_header">
					You have successfully logged out.
				</p>
			</div>
		';
	}

	// Redirect to specified page
	$redirect = $this->input->get('redirect')
		? '?redirect=' . $this->input->get('redirect') 
		: '';

	// Redirect to optional login's page
	if( $redirect == '' && isset( $optional_login ) )
	{
		$redirect = '?redirect=' . urlencode( $this->uri->uri_string() );
	}

	$login_url = USE_SSL === 1 
		? secure_site_url( LOGIN_PAGE . $redirect ) 
		: site_url( LOGIN_PAGE . $redirect );

	echo form_open( $login_url, array( 'class' => 'std-form', 'style' => 'margin-top:20px;' ) ); 
?>

	<div class="form-column-left">
		<div class="form-row">
			<label for="login_string" class="form_label">Username or Email</label>
			<input type="text" name="login_string" id="login_string" class="form_input" autocomplete="off" maxlength="255" />
		</div>
		<div class="form-row">
			<label for="login_pass" class="form_label">Password</label>
			<input type="password" name="login_pass" id="login_pass" class="form_input password" autocomplete="off" maxlength="<?php echo MAX_CHARS_4_PASSWORD; ?>" />
		</div>
		<div class="form-row">
			<label for="show-password" class="form_label">Show Passwords</label>
			<input type="checkbox" id="show-password" />
		</div>

		<?php
			if( config_item('allow_remember_me') )
			{
		?>

		<div class="form-row">
			<label for="remember_me" class="form_label">Remember Me</label>
			<input type="checkbox" id="remember_me" name="remember_me" value="yes" />
		</div>

		<?php
			}
		?>

		<div class="form-row">
			<p>
				<a href="<?php echo secure_site_url('user/recover'); ?>">
					Can't access your account?
				</a>
			</p>
		</div>
		<div class="form-row">
			<div id="submit_box">
				<input type="submit" name="submit" value="Login" id="submit_button"  />
			</div>
		</div>
	</div>
</form>

<?php

	}
	else
	{
		// EXCESSIVE LOGIN ATTEMPTS ERROR MESSAGE
		echo '
			<div class="feedback error_message">
				<p class="feedback_header">
					Excessive Login Attempts
				</p>
				<p style="margin:.4em 0 0 0;">
					You have exceeded the maximum number of failed login<br />
					attempts that the ' . WEBSITE_NAME . ' website will allow.
				<p>
				<p style="margin:.4em 0 0 0;">
					Your access to login and account recovery has been blocked for ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' minutes.
				</p>
				<p style="margin:.4em 0 0 0;">
					Please use the ' . secure_anchor('user/recover','Account Recovery') . ' after ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' minutes has passed,<br />
					or ' . secure_anchor('contact','Contact') . ' ' . WEBSITE_NAME . '  if you require assistance gaining access to your account.
				</p>
			</div>
		';
	}

/* End of file login_form.php */
/* Location: /application/views/auth/login_form.php */ 