<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Auto_populate_model Model
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 *
 *
 * Some alternate query examples are shown in the comments above each method.
 * These alternate queries would be used if you were using "keys" in the 
 * config file, and if seperate tables were being used for types, makes, and models.
 */

class Auto_populate_model extends CI_Model {

	/**
	 * Method to query database for vehicle types.
	 *
	 * If you were using "keys" in the config file, 
	 * you might use a query like this:
	 *
		$query = $this->db->distinct()
			->select('type_id,type')
			->get('auto_types');
	 */
	public function get_types()
	{
		$query = $this->db->distinct()
			->select('type')
			->get( config_item('auto_populate_table') );

		if( $query->num_rows() > 0 )
		{
			return $query->result();
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Method to query database for vehicle makes.
	 *
	 * If you were using "keys" in the config file,
	 * you might use a query like this:
	 *
		$this->db->distinct();
		$this->db->select('make_id,make');
		$this->db->where('type_id_fk',$type );
	 */
	public function get_makes_in_type()
	{
		$type = $this->input->post('type');

		$this->db->distinct();

		$this->db->select('make');

		$this->db->where('type',$type );

		$query = $this->db->get( config_item('auto_populate_table') );

		if( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Method to query database for vehicle models.
	 *
	 * If you were using "keys in the config file",
	 * you might use a query like this:
	 *
		$this->db->select('model_id,model');
		$this->db->where('type_id_fk',$type);
		$this->db->where('make_id_fk',$make);
	 */
	public function get_models_in_make()
	{
		$type = $this->input->post('type');
		$make = $this->input->post('make');

		$this->db->select('model');

		$this->db->where('type',$type);
		$this->db->where('make',$make);

		$query = $this->db->get( config_item('auto_populate_table') );

		if( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Method to query database for vehicle colors.
	 */
	public function get_colors_in_model()
	{
		$type = $this->input->post('type');
		$make = $this->input->post('make');
		$model = $this->input->post('model');

		$this->db->select('color');

		$this->db->where('type',$type);
		$this->db->where('make',$make);
		$this->db->where('model',$model);

		$query = $this->db->get( config_item('auto_populate_table') );

		if( $query->num_rows() > 0 )
		{
			return $query->result_array();
		}

		return FALSE;
	}

	// --------------------------------------------------------------
}

/* End of file auto_populate_model.php */
/* Location: /application/models/auto_populate_model.php */