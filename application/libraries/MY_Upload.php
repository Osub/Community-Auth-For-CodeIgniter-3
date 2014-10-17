<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Upload Library Extension
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
 * This extension of Upload Library allows it to output the URL of 
 * the uploaded file, as long as the file destination is the filesystem.
 *
 * Extension also allows the Upload Library to be used to do file 
 * validation when the file won't be saved to the filesystem.
 * This is important because the filesystem is not always the 
 * final destination for an uploaded file.
 *
 * The upload_bridge method allows for easy uploading to 
 * the filesystem, the database, or an external location via FTP.
 * You provide a callback method, named 'success_callback', in order
 * to log the path to the database, store the file in the database,
 * or whatever fun stuff you can come up with.
 *
 * Other private methods were added to facilitate the creation 
 * and renaming of files, as well as setting dynamic upload locations.
 */
class MY_Upload extends CI_Upload {

	/**
	 * The CodeIgniter Super Object
	 */
	public $CI;

	/**
	 * Allows Upload Library to be used for file validation, 
	 * without saving the file to the filesystem. This is handy if
	 * you want to send the file via FTP to another server,
	 * or if you want to save the file to the database.
	 */
	public $destination_not_file_system = FALSE;

	/**
	 * Location to upload to (only for filesystem uploads)
	 */
	public $upload_dir;
	public $primary_dir    = FALSE;
	public $secondary_dir  = FALSE;
	public $tertiary_dir   = FALSE;
	public $quaternary_dir = FALSE;


	/**
	 * The callback to run when a file is uploaded
	 */
	public $use_success_callback = TRUE;
	public $success_callback;

	// --------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @access	public
	 */
	public function __construct($props = array())
	{
		parent::__construct();

		$this->CI =& get_instance();

		$this->CI->config->load('uploads_manager');

		if (count($props) > 0)
		{
			$this->initialize($props);
		}

		log_message('debug', "Upload Class Extension Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * Perform the file upload
	 *
	 * @return	bool
	 */
	public function do_upload($field = 'userfile')
	{

		// Is $_FILES[$field] set? If not, no reason to continue.
		if ( ! isset($_FILES[$field]))
		{
			$this->set_error('upload_no_file_selected');
			return FALSE;
		}

		/**
		 * MODIFICATION ****
		 * The upload path only needs to be valid if the destination for the
		 * uploaded file is the filesystem, and not another server or the database.
		 */
		if( $this->destination_not_file_system === FALSE )
		{
			// Is the upload path valid?
			if ( ! $this->validate_upload_path())
			{
				// errors will already be set by validate_upload_path() so just return FALSE
				return FALSE;
			}
		}

		// Was the file able to be uploaded? If not, determine the reason why.
		if ( ! is_uploaded_file($_FILES[$field]['tmp_name']))
		{
			$error = ( ! isset($_FILES[$field]['error'])) ? 4 : $_FILES[$field]['error'];

			switch($error)
			{
				case 1:	// UPLOAD_ERR_INI_SIZE
					$this->set_error('upload_file_exceeds_limit');
					break;
				case 2: // UPLOAD_ERR_FORM_SIZE
					$this->set_error('upload_file_exceeds_form_limit');
					break;
				case 3: // UPLOAD_ERR_PARTIAL
					$this->set_error('upload_file_partial');
					break;
				case 4: // UPLOAD_ERR_NO_FILE
					$this->set_error('upload_no_file_selected');
					break;
				case 6: // UPLOAD_ERR_NO_TMP_DIR
					$this->set_error('upload_no_temp_directory');
					break;
				case 7: // UPLOAD_ERR_CANT_WRITE
					$this->set_error('upload_unable_to_write_file');
					break;
				case 8: // UPLOAD_ERR_EXTENSION
					$this->set_error('upload_stopped_by_extension');
					break;
				default :   $this->set_error('upload_no_file_selected');
					break;
			}

			return FALSE;
		}


		// Set the uploaded data as class variables
		$this->file_temp = $_FILES[$field]['tmp_name'];
		$this->file_size = $_FILES[$field]['size'];
		$this->_file_mime_type($_FILES[$field]);
		$this->file_type = preg_replace("/^(.+?);.*$/", "\\1", $this->file_type);
		$this->file_type = strtolower(trim(stripslashes($this->file_type), '"'));
		$this->file_name = $this->_prep_filename($_FILES[$field]['name']);
		$this->file_ext	 = $this->get_extension($this->file_name);
		$this->client_name = $this->file_name;

		// Is the file type allowed to be uploaded?
		if ( ! $this->is_allowed_filetype())
		{
			$this->set_error('upload_invalid_filetype');
			return FALSE;
		}

		// if we're overriding, let's now make sure the new name and type is allowed
		if ($this->_file_name_override != '')
		{
			$this->file_name = $this->_prep_filename($this->_file_name_override);

			// If no extension was provided in the file_name config item, use the uploaded one
			if (strpos($this->_file_name_override, '.') === FALSE)
			{
				$this->file_name .= $this->file_ext;
			}

			// An extension was provided, lets have it!
			else
			{
				$this->file_ext	 = $this->get_extension($this->_file_name_override);
			}

			if ( ! $this->is_allowed_filetype(TRUE))
			{
				$this->set_error('upload_invalid_filetype');
				return FALSE;
			}
		}

		// Convert the file size to kilobytes
		if ($this->file_size > 0)
		{
			$this->file_size = round($this->file_size/1024, 2);
		}

		// Is the file size within the allowed maximum?
		if ( ! $this->is_allowed_filesize())
		{
			$this->set_error('upload_invalid_filesize');
			return FALSE;
		}

		// Are the image dimensions within the allowed size?
		// Note: This can fail if the server has an open_basdir restriction.
		if ( ! $this->is_allowed_dimensions())
		{
			$this->set_error('upload_invalid_dimensions');
			return FALSE;
		}

		// Sanitize the file name for security
		$this->file_name = $this->clean_file_name($this->file_name);

		// Truncate the file name if it's too long
		if ($this->max_filename > 0)
		{
			$this->file_name = $this->limit_filename_length($this->file_name, $this->max_filename);
		}

		// Remove white spaces in the name
		if ($this->remove_spaces == TRUE)
		{
			$this->file_name = preg_replace("/\s+/", "_", $this->file_name);
		}

		/*
		 * Validate the file name
		 * This function appends an number onto the end of
		 * the file if one with the same name already exists.
		 * If it returns false there was a problem.
		 */
		$this->orig_name = $this->file_name;

		if ($this->overwrite == FALSE)
		{
			$this->file_name = $this->set_filename($this->upload_path, $this->file_name);

			if ($this->file_name === FALSE)
			{
				return FALSE;
			}
		}

		/*
		 * Run the file through the XSS hacking filter
		 * This helps prevent malicious code from being
		 * embedded within a file.  Scripts can easily
		 * be disguised as images or other file types.
		 */
		if ($this->xss_clean)
		{
			if ($this->do_xss_clean() === FALSE)
			{
				$this->set_error('upload_unable_to_write_file');
				return FALSE;
			}
		}

		/**
		 * MODIFICATION ***
		 * We only need to move the file to its final destination
		 * on the server if the final destination is the server.
		 */
		if( $this->destination_not_file_system === FALSE )
		{
			/*
			 * Move the file to the final destination
			 * To deal with different server configurations
			 * we'll attempt to use copy() first.  If that fails
			 * we'll use move_uploaded_file().  One of the two should
			 * reliably work in most environments
			 */
			if ( ! @copy($this->file_temp, $this->upload_path.$this->file_name))
			{
				if ( ! @move_uploaded_file($this->file_temp, $this->upload_path.$this->file_name))
				{
					$this->set_error('upload_destination_error');
					return FALSE;
				}
			}

			/*
			 * Set the finalized image dimensions for 
			 * files saved to the file system.
			 * This sets the image width/height (assuming the
			 * file was an image).  We use this information
			 * in the "data" function.
			 */
			$this->set_image_properties($this->upload_path.$this->file_name);
		}
		else
		{
			/*
			 * Set the finalized image dimensions for 
			 * files that were not saved to the file system.
			 * This sets the image width/height (assuming the
			 * file was an image).  We use this information
			 * in the "data" function.
			 */

			$this->set_image_properties( $this->file_temp );
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Finalized Data Array
	 *
	 * Returns an associative array containing all of the information
	 * related to the upload, allowing the developer easy access in one array.
	 *
	 * @return	array
	 */
	public function data()
	{
		return array (
						'file_name'			=> $this->file_name,
						'file_type'			=> $this->file_type,
						'file_path'			=> $this->upload_path,
						'full_path'			=> $this->upload_path.$this->file_name,
						'raw_name'			=> str_replace($this->file_ext, '', $this->file_name),
						'orig_name'			=> $this->orig_name,
						'client_name'		=> $this->client_name,
						'file_ext'			=> $this->file_ext,
						'file_size'			=> $this->file_size,
						'is_image'			=> $this->is_image(),
						'image_width'		=> $this->image_width,
						'image_height'		=> $this->image_height,
						'image_type'		=> $this->image_type,
						'image_size_str'	=> $this->image_size_str,

						/**
						 * New file_url key shows complete URL to file on server.
						 */
						'file_url'			=> $this->file_url($this->upload_path.$this->file_name),
					);
	}

	// --------------------------------------------------------------------

	/**
	 * New function creates a URL to the file that was uploaded.
	 */
	public function file_url( $full_path )
	{
		// Get all URI segments of the file upload location
		$path_parts = explode('/', $full_path);

		// Initialize variable to track if upload_dir has been reached when looping through $path_parts
		$target_dir = FALSE;

		// Initialize variable to hold our image path to pass to base_url()
		$file_url = '';

		// Loop through $path_parts
		for( $x=0; $x <= count( $path_parts ) - 1; $x++ )
		{
			// If this parth part is the upload_dir, or if the upload_dir has already been reached
			if( $path_parts[$x] == $this->upload_dir OR $target_dir === TRUE )
			{
				// Build on to the path to pass to base_url()
				$file_url .= ( $target_dir ) ? '/' . $path_parts[$x] : $path_parts[$x];

				$target_dir = TRUE;
			}
		}

		// Return the URL to the image
		return if_secure_base_url( $file_url );
	}

	// --------------------------------------------------------------------

	/**
	 * UPLOAD_BRIDGE does most of the hard work of getting an upload 
	 * to it's destination, whether it be the filesystem, database, 
	 * or another server via FTP.
	 *
	 * @param   string    used as a suffix to the upload config set.
	 *                    Is is also the prefix of the config set.
	 * @param   string    Either 'filesystem', 'database', or 'ftp'
	 */
	public function upload_bridge( $type, $bridge_type )
	{
		/**
		 * Initialize the upload class based on destination of upload
		 */

		// Initialization for FILESYSTEM uploads
		if( $bridge_type == 'filesystem')
		{
			// Get special upload config for filesystem storage
			$local_config = $this->_set_upload_location( $type );

			// Merge upload path into config settings
			$init_config = array_merge( $local_config, config_item( 'upload_configuration_' . $type ) );

			// Initialize file upload class
			$this->initialize( $init_config );
		}

		// Initialization for DATABASE uploads or FTP transfers
		else if( $bridge_type == 'database' OR $bridge_type == 'ftp' )
		{
			$this->destination_not_file_system = TRUE;

			// Initialize file upload class with database storage config
			$this->initialize( config_item( 'upload_configuration_' . $type ) );
		}

		/**
		 * The upload class' do_upload method will save the file to the 
		 * filesystem, but if we are storing the image in the database 
		 * or transferring it via FTP, the do_upload method is just used 
		 * for file validation.
		 */
		if( $this->do_upload() )
		{
			// Process FILESYSTEM callback
			if( $bridge_type == 'filesystem' )
			{
				$response = $this->_process_success_callback( FALSE );
			}

			// Process DATABASE callback
			else if( $bridge_type == 'database' )
			{
				// Transform the file into a base64 encoded string
				$handle = fopen( $this->file_temp, "r" );
				$file_string = base64_encode( fread( $handle, filesize( $this->file_temp ) ) );
				fclose($handle);

				$response = $this->_process_success_callback( $file_string );
			}

			// Transfer file via FTP and process FTP callback
			else if( $bridge_type == 'ftp' )
			{
				// Load FTP resources
				$this->CI->load->library('ftp');
				$this->CI->config->load('ftp');
				$ftp_config = config_item( $type . '_ftp_settings' );

				// Make the FTP connection
				if( $this->CI->ftp->connect( $ftp_config ) === TRUE )
				{
					// Check if filename already exists on remote server, and if so, rename
					$remote_list = $this->CI->ftp->list_files( $ftp_config['remote_directory'] );

					$this->file_name = $this->_force_unique_filename( $remote_list, $this->file_name );

					// Attempt FTP upload to remote server
					if( $this->CI->ftp->upload( $this->file_temp, $ftp_config['remote_directory'] . $this->file_name ) )
					{
						$response = $this->_process_success_callback( FALSE );
					}
					// FTP upload failed
					else
					{
						// Get FTP Errors
						$errors = $this->CI->ftp->error_stack;

						$formatted_errors = '';
						foreach( $errors as $error )
						{
							$formatted_errors .= $error . "\n";
						}

						// Send FTP Errors as Response
						$response['status'] = 'error';
						$response['issue']  = $formatted_errors;
					}

					$this->CI->ftp->close();
				}

				// Error: No connection to other server
				else
				{
					$this->CI->load->helper('file');

					// Check if filename already exists in the lost_dir
					$local_list = get_filenames( config_item('lost_dir') . $type );

					$this->file_name = $this->_force_unique_filename( $local_list, $this->file_name );

					// Save the file locally to be retreived by the other server's lost_files_recovery cron
					if ( ! write_file( config_item('lost_dir') . $type . '/' . $this->file_name, $this->file_temp ) )
					{
						$response['status'] = 'error';
						$response['issue']  = 'Upload failed. No connection to external server. Please try again later.';
					}
					else
					{
						$response = $this->_process_success_callback( FALSE );
					}
				}
			}
		}
		else
		{
			// Error: Upload Failed
			$response['status'] = 'error';
			$response['issue']  = $this->display_errors('','');
		}

		return $response;
	}

	// --------------------------------------------------------------

	/**
	 * _PROCESS_SUCCESS_CALLBACK runs the custom success_callback 
	 * methods that you will use to do things like store the path to 
	 * the upload in the database, or store a base64 encoded string 
	 * upload in the database. Normally you won't just upload a file 
	 * without doing something, and the success_callback is that something.
	 *
	 * @param   mixed    the base64 encoded string for database storage, 
	 *                   or FALSE if the upload destination is not the db.
	 */
	private function _process_success_callback( $file_string )
	{
		// Make upload details available in callback and ajax response
		foreach( $this->data() as $k => $v )
		{
			$response[$k] = $v;
		}

		// Need to have callback to handle the specific upload details
		if( empty( $this->success_callback ) )
		{
			$this->success_callback = $this->CI->input->post('success_callback');
		}

		// Check if use success callback set in POST
		if( $this->CI->input->post('no_success_callback') )
		{
			$this->use_success_callback = FALSE;

			$response['status'] = 'success';
		}

		if( ! empty( $this->success_callback ) )
		{
			if( method_exists( $this, $this->success_callback ) )
			{
				$callback = $this->success_callback;

				if( $callback_response = $this->$callback( $file_string ) )
				{
					// Send Success Response
					$response['status']            = 'success';
					$response['callback_response'] = $callback_response;
				}
				else
				{
					// Error: Callback Failed
					$response['status'] = 'error';
					$response['issue']  = 'Callback failed.';
				}
			}
			else
			{
				// Error: Callback Doesn't Exist
				$response['status'] = 'error';
				$response['issue']  = 'Callback does not exist.';
			}
		}
		else if( $this->use_success_callback === TRUE )
		{
			// Error: No Callback Specified
			$response['status'] = 'error';
			$response['issue']  = 'No callback specified.';
		}

		return $response;
	}

	// --------------------------------------------------------------

	/**
	 * This method creates the dynamic location of where on the file system
	 * a file with be saved by the upload class.
	 */
	private function _set_upload_location( $type )
	{
		$config_array = config_item( 'upload_configuration_' . $type );

		// Set directories for upload location
		if( empty( $this->upload_dir ) )
		{
			$this->upload_dir = config_item('upload_dir');
		}

		// If primary directory not set in controller, get it from the POST array
		if( empty( $this->primary_dir ) )
		{
			$this->primary_dir = $this->CI->input->post( $config_array['primary_dir'] );
		}

		/**
		 * If secondary directory not set in controller, 
		 * but the config specifies a POST key to check,
		 * obtain the secondary directory from that POST element.
		 */
		if( empty( $this->secondary_dir ) && isset( $config_array['secondary_dir'] ) )
		{
			$this->secondary_dir = $this->CI->input->post( $config_array['secondary_dir'] );
		}

		/**
		 * If tertiary directory not set in controller, 
		 * but the config specifies a POST key to check,
		 * obtain the tertiary directory from that POST element.
		 */
		if( empty( $this->tertiary_dir ) && isset( $config_array['tertiary_dir'] ) )
		{
			$this->tertiary_dir = $this->CI->input->post( $config_array['tertiary_dir'] );
		}

		/**
		 * If quaternary directory not set in controller, 
		 * but the config specifies a POST key to check,
		 * obtain the quaternary directory from that POST element.
		 */
		if( empty( $this->quaternary_dir ) && isset( $config_array['quaternary_dir'] ) )
		{
			$this->quaternary_dir = $this->CI->input->post( $config_array['quaternary_dir'] );
		}

		// Add hash to directory if specified in config
		if( isset( $config_array['add_hash'] ) )
		{
			$hash_dir = $config_array['add_hash'];

			$this->$hash_dir .= '-' . md5( config_item('encryption_key') . $this->CI->input->post( $config_array[$hash_dir] ) );
		}

		/**
		 * Create the upload path using FCPATH.
		 * This provides an absolute path to the 
		 * upload directory location.
		 */
		$upload_path = FCPATH . $this->upload_dir . '/' . $this->primary_dir . '/';

		if( ! empty( $this->secondary_dir ) )
		{
			$upload_path .= $this->secondary_dir . '/';

			if( ! empty( $this->tertiary_dir ) )
			{
				$upload_path .= $this->tertiary_dir . '/';

				if( ! empty( $this->quaternary_dir ) )
				{
					$upload_path .= $this->quaternary_dir . '/';
				}
			}
		}

		// Create upload directories if they don't exist
		if( ! is_dir( $upload_path ) )
		{
			mkdir( $upload_path , 0777, TRUE );
		}

		return array( 'upload_path' => $upload_path );
	}

	// --------------------------------------------------------------

	/**
	 * _force_unique_filename makes sure that the filename of the 
	 * uploaded file does not already exist.
	 *
	 * Different than the CI Upload class' set_filename function,
	 * this function takes a supplied directory listing and checks
	 * that the supplied filename is not in it. If it is, it adds a 
	 * number, in parenthesis, to the end of the filename. 
	 *
	 * @param   mixed    An array or string representing the directory listing.
	 * @param   string   The filename to check.
	 * @param   int      The number to use in the filename suffix for the second file.
	 *                   You may prefer 1, 0, etc.
	 */
	private function _force_unique_filename( $remote_list, $file_name, $x = 2 )
	{
		/**
		 * Dir list may be an array of file names, or in the case of 
		 * cURL, the list may be supplied as a string. If an array, we 
		 * just convert the array to a string so it is checked as a string.
		 */
		if( is_array( $dir_list ) )
		{
			$dir_list = implode( ' ', $dir_list );
		}

		while( strpos( $dir_list, $file_name ) !== FALSE )
		{
			// Use pathinfo to break apart the filename
			$info = pathinfo( $file_name );

			// Get the file extension of the file
			$ext = '.' . $info['extension'];

			// Get the name of the file without extension
			$file_name = basename( $file_name, $ext );

			// Remove the filename suffix before adding a new one
			$pattern = '/\(\d+\)/';
			$replacement = '';
			$file_name = preg_replace( $pattern, $replacement, $file_name );
			
			// Add new filename suffix
			$file_name .= '(' . (string) $x . ')' . $ext;

			// Increment the number we are using in a filename suffix "($x)"
			$x++;
		}

		return $file_name;
	}

	// --------------------------------------------------------------

	/**
	 * This method saves a user's profile image or a reference its location on the filesystem:
	 */
	private function _profile_image( $file_string = FALSE )
	{
		$this->CI->load->model('user_model');

		$upload_details = $this->data();

		/**
		 * If file string provided, upload goes to database, 
		 * otherwise reference to image URL stored in database.
		 */
		$data = ( $file_string ) ? $file_string : $upload_details['file_url'];

		$model_response = $this->CI->user_model->update_user(
			config_item( 'auth_role' ),
			config_item( 'auth_user_id' ),
			'profile_image',
			array(),
			array( 'profile_image' => $data )
		);

		if( $model_response !== FALSE )
		{
			return $data;
		}

		return FALSE;
	}

	// --------------------------------------------------------------

}

/* End of file MY_Upload.php */
/* Location: ./application/libraries/MY_Upload.php */