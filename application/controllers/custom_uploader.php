<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Custom Uploader Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Custom_uploader extends MY_Controller {

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// Force encrypted connection
		$this->force_ssl();

		// Load common resources
		$this->config->load('uploads_manager');
		$this->load->model('uploads_model');
	}

	// --------------------------------------------------------------

	/**
	 * Default method
	 */
	public function index()
	{
		$this->custom_gallery();
	}

	// --------------------------------------------------------------

	/**
	 * Uploader controls
	 */
	public function uploader_controls()
	{
		// Make sure anyone is logged in
		if( $this->require_min_level(1) )
		{
			// Get the uploader settings
			$view_data['uploader_settings'] = config_item('upload_configuration_custom_uploader');

			// Create a more human friendly version of the allowed_types
			$view_data['file_types'] = str_replace( '|', ' &bull; ', $view_data['uploader_settings']['allowed_types'] );

			// Get any existing images
			$view_data['images'] = $this->uploads_model->get_custom_uploader_images( $this->auth_user_id );

			$data = array(
				'javascripts' => array(
					'//ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js',
					'js/ajaxupload.js',
					'js/custom_uploader/uploader-controls.js',
				),
				'content' => $this->load->view('custom_uploader/uploader_controls', $view_data, TRUE)
			);

			$this->load->view( $this->template, $data );
		}
	}

	// --------------------------------------------------------------

	/**
	 * Update image order in the database
	 */
	public function update_image_order()
	{
		// Make sure anyone is logged in
		if( $this->require_min_level(1) )
		{
			if( $this->tokens->match )
			{
				if( $image_data = $this->input->post('image_data') )
				{
					$image_data = serialize( $image_data );

					if( $model_response = $this->uploads_model->save_image_data( $this->auth_user_id, $image_data ) )
					{
						$response['status']        = 'Image Order Updated';
						$response['token']         = $this->tokens->token();
						$response['ci_csrf_token'] = $this->security->get_csrf_hash();
					}
					else
					{
						$response['status'] = 'Error: Model Response = FALSE';
					}
				}
				else
				{
					$response['status'] = 'No Image Data';

					/**
					 * We need to update the tokens when there is no image data
					 * because when all images have been deleted, $image_data = FALSE
					 */
					$response['token']         = $this->tokens->token();
					$response['ci_csrf_token'] = $this->security->get_csrf_hash();
				}
			}
			else
			{
				$response['status'] = 'Error: No Token Match - ' . $this->tokens->posted_value . ' != ' . $this->tokens->current_token;
			}

			echo json_encode( $response );
		}
	}

	// --------------------------------------------------------------

	/**
	 * Delete image from filesystem and database
	 */
	public function delete_image()
	{
		// Make sure anyone is logged in
		if( $this->require_min_level(1) )
		{
			// Load resources
			$this->load->helper('file');

			// Make sure the form token matches
			if( $this->tokens->match )
			{
				// Make sure we have the appropriate post variable
				if( $image_data = $this->input->post('src') )
				{
					// Make sure the user's directory appears in the posted 'src'
					$user_dir = $this->auth_user_id . '-' . md5( config_item('encryption_key') . $this->auth_user_id );

					if( strpos( $image_data, $user_dir ) !== FALSE )
					{
						// Remove scheme and domain from the src
						$file_location = str_replace( if_secure_base_url(), '', $image_data );

						// Add path to base file location
						$uploaded_file = FCPATH . $file_location;

						// Delete the file from the file system
						unlink( $uploaded_file );

						// Remove the file from the base file location to get path to directory
						$dir_location = FCPATH . pathinfo( $file_location, PATHINFO_DIRNAME );

						// rmdir() will remove the directory if it is empty
						@rmdir( $dir_location );

						// Check the database for existing images data
						$query_data = $this->uploads_model->get_custom_uploader_images( $this->auth_user_id );

						// Unserialize the existing images data
						$arr = unserialize( $query_data->images_data );

						/**
						 * If the deleted image was the only image, delete the record
						 * and delete the directory that was holding the images. If 
						 * there is more than one image, we just update the record.
						 */
						if( count( $arr ) > 1 )
						{
							$temp = FALSE;

							// For each image in the existing images data
							foreach( $arr as $k => $v )
							{
								// If this isn't the image that we are deleting now
								if( $v != $image_data )
								{
									// Save it to a temp array
									$temp[] = $v;
								}
							}

							// Send the new images data to the model for record update
							if( $model_response = $this->uploads_model->save_image_data( $this->auth_user_id, serialize( $temp ) ) )
							{
								$response = array(
									'status'        => 'Image Deleted',
									'token'         => $this->tokens->token(),
									'ci_csrf_token' => $this->security->get_csrf_hash()
								);
							}
							else
							{
								$response['status'] = 'Error: Model Response = FALSE on SAVE';
							}
						}

						// The deleted image was the only image
						else
						{
							if( $model_response = $this->uploads_model->delete_image_record( $this->auth_user_id ) )
							{
								$response = array(
									'status'        => 'Image Deleted',
									'token'         => $this->tokens->token(),
									'ci_csrf_token' => $this->security->get_csrf_hash()
								);
							}
							else
							{
								$response['status'] = 'Error: Model Response = FALSE on DELETE';
							}
						}

					}
					else
					{
						$response['status'] = 'Error: Image Path Not Verified';
					}
				}
				else
				{
					$response['status'] = 'Error: No Image Data';
				}
			}
			else
			{
				$response['status'] = 'Error: No Token Match';
			}

			echo json_encode( $response );
		}
	}

	// --------------------------------------------------------------

	/**
	 * Future home of the gallery.
	 */
	public function custom_gallery()
	{
		echo 'Nothing to see yet.';
	}

	// --------------------------------------------------------------
}

/* End of file custom_uploader.php */
/* Location: ./application/controllers/custom_uploader.php */