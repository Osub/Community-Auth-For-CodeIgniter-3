<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Reauthentication Example View
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

<h1>Reauthentication Example</h1>

<?php
// Show a confirmation if reauthentication was successful
if( isset( $validation_passed ) && isset( $reauthenticated ) && $reauthenticated === TRUE )
{
	echo '
			<div class="feedback confirmation">
				<p class="feedback_header">
					Congratulations! You reauthenticated.
				</p>
			</div>
		';
}
else if( 
	// If there were validation errors
	isset( $validation_errors ) OR 
	// Or if reauthentication failed
	( isset( $reauthenticated ) && $reauthenticated === FALSE )
)
{
	$errors = '';

	// Show validation errors
	if( isset( $validation_errors ) )
	{
		$errors .= $validation_errors;
	}

	// Show reauthentication error
	if( isset( $reauthenticated ) && $reauthenticated === FALSE )
	{
		$errors .= '<li>Reauthentication failed ( Bad username, email, or password )</li>';
	}

	echo '
			<div class="feedback error_message">
				<p class="feedback_header">
					Form Submission Error
				</p>
				<ul>
					' . $errors . '
				</ul>
			</div>
		';
}

// Only show the notice on initial pageload.
else
{
	echo '<p>Notice that you are already logged in, yet you are presented with the login form. We can enforce a reauthentication just because we feel like it. Since we are using a form to post data to the same controller/method, the Authentication library checks the login status of the user based on their cookie. What reauthentication will do is make the user provide proper login credentials to get past this point, regardless of who the cookie said they were.</p>';
}

// Don't show the form if form validation and reauthentication passed.
if( 
	! isset( $validation_passed ) OR 
	( isset( $reauthenticated ) && $reauthenticated === FALSE )
)
{
	echo $login_form;
}

/* End of file reauthentication_example.php */
/* Location: /application/views/reauthentication_example.php */ 