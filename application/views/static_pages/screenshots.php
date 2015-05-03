<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Screenshots View
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

<h1>Screenshots</h1>
<p>
	Since there is no demo to allow you to check out what you see when logged in to Community Auth, some screenshots have been provided below. Community Auth has a user recovery process, as well as a registration process. No screenshots have been provided for those processes.
</p>

<?php

$images = array(
	array(
		'src' => 'http://images.brianswebdesign.com/community_auth_screenshots/init-sm.jpg',
		'alt' => 'Installation'
	),
	array(
		'src' => 'http://images.brianswebdesign.com/community_auth_screenshots/create_user-sm.jpg',
		'alt' => 'User Creation'
	),
	array(
		'src' => 'http://images.brianswebdesign.com/community_auth_screenshots/manage_users-sm.jpg',
		'alt' => 'User Management'
	),
	array(
		'src' => 'http://images.brianswebdesign.com/community_auth_screenshots/deny_access-sm.jpg',
		'alt' => 'Deny Access'
	),
	array(
		'src' => 'http://images.brianswebdesign.com/community_auth_screenshots/update_user-sm.jpg',
		'alt' => 'User Update'
	),
	array(
		'src' => 'http://images.brianswebdesign.com/community_auth_screenshots/self_update-sm.jpg',
		'alt' => 'My Profile'
	),
	array(
		'src' => 'http://images.brianswebdesign.com/community_auth_screenshots/registration_settings-sm.jpg',
		'alt' => 'Registration Settings'
	),
	array(
		'src' => 'http://images.brianswebdesign.com/community_auth_screenshots/pending_registrations-sm.jpg',
		'alt' => 'Pending Registrations'
	),
	array(
		'src' => 'http://images.brianswebdesign.com/community_auth_screenshots/custom_uploader-sm.jpg',
		'alt' => 'Custom Uploader'
	),
	array(
		'src' => 'http://images.brianswebdesign.com/community_auth_screenshots/auto_populate-sm.jpg',
		'alt' => 'Auto Population of Dropdowns'
	),
	array(
		'src' => 'http://images.brianswebdesign.com/community_auth_screenshots/category_menu-sm.jpg',
		'alt' => 'Category Menu'
	)
);

// Start gallery output
$gallery = '<div class="screenshot-row">';

$i = 0;

$last_image = count( $images ) - 1;

foreach( $images as $img )
{
	// Get the full size image path
	$full_size = str_replace( '-sm', '', $img['src'] );

	$gallery .= '<div class="screenshot">
			' . anchor( $full_size, img( $img ) ) . '
			<br />' . $img['alt'] . '
		</div>';

	// Two images belong in a row
	if( $i % 2 && $i != $last_image )
	{
		$gallery .= '</div>
			<div class="screenshot-row">';
	}

	$i++;
}

// Finish gallery output
$gallery .= '</div>';

/**
 * Attempt to use Tidy.
 * Doing this makes checking the structure 
 * of the HTML a lot easier, and I decided 
 * to leave it in for reference.
 */
if( class_exists( 'tidy' ) )
{
	// Tidy configuration
	$config = array(
		'indent'       => true,
		'output-xhtml' => true,
		'show-body-only' => true,
		'wrap'         => 0
	);

	// Tidy
	$tidy = new tidy;
	$tidy->parseString( $gallery, $config, 'utf8');
	$tidy->cleanRepair();
}

// Output gallery!
echo ( isset( $tidy ) ) ? $tidy : $gallery;

/* End of file screenshots.php */
/* Location: /application/views/screenshots/screenshots.php */