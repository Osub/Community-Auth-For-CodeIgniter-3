<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Uploads_model Model
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Uploads_model extends CI_Model {

	/**
	 * Get existing images data
	 * 
	 * @param   array  the user ID of the current logged in user
	 * @return  mixed
	 */
	public function get_custom_uploader_images( $user_id )
	{
		$query = $this->db->get_where( config_item('custom_uploader_table'), array( 'user_id' => $user_id ) );

		if( $query->num_rows() == 1 )
		{
			return $query->row();
		}

		return FALSE;
	}

	// -----------------------------------------------------------------------

	/**
	 * Save images data
	 */
	public function save_image_data( $user_id, $image_data )
	{
		// Check for existing images
		$query = $this->get_custom_uploader_images( $user_id );

		// If there is no existing record
		if( $query === FALSE )
		{
			$query = $this->db->insert( 
				config_item('custom_uploader_table'), 
				array(
					'user_id' => $user_id,
					'images_data' => $image_data
				)
			);
		}

		// If there is an existing record
		else
		{
			$query = $this->db->update( 
				config_item('custom_uploader_table'), 
				array('images_data' => $image_data), 
				array( 'user_id' => $user_id ) 
			);
		}

		if( $this->db->affected_rows() == 1 )
		{
			return TRUE;
		}

		return FALSE;
	}

	// -----------------------------------------------------------------------

	/**
	 * Delete image record
	 */
	public function delete_image_record( $user_id )
	{
		$this->db->where( 'user_id', $user_id )
			->delete( config_item('custom_uploader_table') );

		if( $this->db->affected_rows() == 1 )
		{
			return TRUE;
		}

		return FALSE;
	}
	
	// -----------------------------------------------------------------------
}

/* End of file uploads_model.php */
/* Location: /application/models/uploads_model.php */