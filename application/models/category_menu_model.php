<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Category_menu_model Model
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Category_menu_model extends CI_Model {

	public function get_all_category_data()
	{
		// Get the complete category_menu table
		$query = $this->db->order_by('parent_id','asc')
			->get( config_item('category_menu_table') );

		if( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}

		return FALSE;
	}

	// --------------------------------------------------------------
}

/* End of file category_menu_model.php */
/* Location: /application/models/category_menu_model.php */