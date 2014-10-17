<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Uploads Manager Config
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

/**
 * Upload_dir must be a single public root level directory.
 */
$config['upload_dir'] = 'upload_directory';

/**
 * Lost_dir is the directory where files go if the FTP connection not available.
 */
$config['lost_dir'] = './lost_directory/';

// --------------------------------------------------------------

/**
 * PROFILE IMAGE UPLOAD OPTIONS
 *
 * Choose the upload destination. Either 'database' OR 'filesystem'.
 * Please note: If you are going to store the image in the database,
 * you'll need to change the `profile_image` datatype in the user_profiles 
 * table to 'text'. Also, native IE8 may fail to return the base64 encoded
 * string representation of the image in Ajax. It is what it is.
 */

// Profile Image Destination
$config['profile_image_destination'] = 'filesystem';

// Profile Image Authentication (for ajax only)
$config['authentication_profile_image'] = 'admin,manager,customer';

// Upload config for user's profile image
$config['upload_configuration_profile_image'] = array(

	// Settings for any destination
	'allowed_types' => 'gif|jpg|jpeg|png',
	'max_size'      => '50',
	'max_width'     => '100',
	'max_height'    => '100',

	// FILESYSTEM specific settings
	'primary_dir'   => 'dir_name',     // <- POST key of primary directory
	'secondary_dir' => 'user_id',      // <- POST key of secondary directory
	'add_hash'      => 'secondary_dir' // <- add a hash to ( for unwanted direct browsing )
);

// --------------------------------------------------------------

/**
 * CUSTOM UPLOADER OPTIONS
 */

// Profile Image Destination
$config['custom_uploader_destination'] = 'filesystem';

// Profile Image Authentication
$config['authentication_custom_uploader'] = 'admin,manager,customer';

// Upload config for user's profile image
$config['upload_configuration_custom_uploader'] = array(

	// Settings for any destination
	'allowed_types' => 'gif|jpg|jpeg|png',
	'max_size'      => '50',
	'max_width'     => '100',
	'max_height'    => '100',

	// FILESYSTEM specific settings
	'primary_dir'   => 'dir_name',     // <- POST key of primary directory
	'secondary_dir' => 'user_id',      // <- POST key of secondary directory
	'add_hash'      => 'secondary_dir' // <- add a hash to ( for unwanted direct browsing )
);

// --------------------------------------------------------------

/* End of file uploads_manager.php */
/* Location: /application/config/uploads_manager.php */