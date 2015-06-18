<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Login Form View
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
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
			<div style="border:1px solid red;">
				<p>
					Login Error: Invalid Username, Email Address, or Password.
				</p>
				<p>
					Username, email address and password are all case sensitive.
				</p>
			</div>
		';
	}

	if( $this->input->get('logout') )
	{
		echo '
			<div style="border:1px solid green">
				<p>
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

	echo form_open( $login_url, array( 'class' => 'std-form' ) ); 
?>

	<div>

		<label for="login_string" class="form_label">Username or Email</label>
		<input type="text" name="login_string" id="login_string" class="form_input" autocomplete="off" maxlength="255" />

		<label for="login_pass" class="form_label">Password</label>
		<input type="password" name="login_pass" id="login_pass" class="form_input password" autocomplete="off" maxlength="<?php echo config_item('max_chars_for_password'); ?>" />


		<?php
			if( config_item('allow_remember_me') )
			{
		?>

			<label for="remember_me" class="form_label">Remember Me</label>
			<input type="checkbox" id="remember_me" name="remember_me" value="yes" />

		<?php
			}
		?>

		<p>
			<a href="<?php echo secure_site_url('user/recover'); ?>">
				Can't access your account?
			</a>
		</p>


		<input type="submit" name="submit" value="Login" id="submit_button"  />

	</div>
</form>

<?php

	}
	else
	{
		// EXCESSIVE LOGIN ATTEMPTS ERROR MESSAGE
		echo '
			<div style="border:1px solid red;">
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
					Please use the ' . secure_anchor('user/recover','Account Recovery') . ' after ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' minutes has passed,<br />
					or contact us if you require assistance gaining access to your account.
				</p>
			</div>
		';
	}

/* End of file login_form.php */
/* Location: /application/views/auth/login_form.php */ 