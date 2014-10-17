<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Registration_model Model
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Registration_model extends MY_Model {

	private $insert_array = array();

	/**
	 * Get the registration mode from the database
	 */
	public function get_reg_mode()
	{
		$query = $this->db->get( config_item('registration_table') );

		$row = $query->row();

		return $row->reg_mode;
	}

	// --------------------------------------------------------------

	/**
	 * Set the registration mode in the database
	 * 
	 * @param   int  the registration mode number
	 * @return  bool
	 */
	public function set_reg_mode( $num )
	{
		if( $this->db->update( config_item('registration_table'), array( 'reg_mode' => $num ) ) !== FALSE )
		{
			return TRUE;
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Insert a registration record into the database
	 *
	 * @return  mixed   either the registration ID or FALSE
	 */
	public function set_pending()
	{
		// The form validation class doesn't allow for multiple config files, so we do it the old fashion way
		$this->config->load( 'form_validation/register/registration_form' );
		$this->validation_rules = config_item('registration_form');

		if( $this->validate() )
		{
			// Load encryption library to encrypt password and license number
			$this->load->library('encrypt');

			// Get unused registration ID
			$this->load->model('user_model');
			$registration_id = $this->user_model->get_unused_id('reg');

			// Generate random user salt
			$user_salt = md5( mt_rand() );

			// Create insert array for registration record
			$insert_array = array(
				'reg_id'         => $registration_id,
				'reg_time'       => time(),
				'user_name'      => set_value('user_name'),
				'user_pass'      => $this->encrypt->encode( $user_salt . set_value('user_pass') ),
				'user_salt'      => $user_salt,
				'user_email'     => set_value('user_email'),
				'first_name'     => set_value('first_name'),
				'last_name'      => set_value('last_name'),
				'street_address' => set_value('street_address'),
				'city'           => set_value('city'),
				'state'          => set_value('state'),
				'zip'            => set_value('zip')
			);

			// Insert record
			$this->db->insert( config_item('temp_reg_data_table'), $insert_array );

			if( $this->db->affected_rows() > 0 )
			{
				return $registration_id;
			}
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * View pending registrations
	 *
	 * @return  mixed  either the query data as an object or FALSE
	 */
	public function view_pending()
	{
		$query = $this->db->get( config_item('temp_reg_data_table') );

		if( $query->num_rows() >= 1 )
		{
			return $query->result();
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Delete pending registrations
	 */
	public function delete( $ids )
	{
		foreach( $ids as $id )
		{
			$this->db->delete( config_item('temp_reg_data_table'), array( 'reg_id' => $id ) );
		}
	}

	// --------------------------------------------------------------

	/**
	 * Approve pending registrations
	 *
	 * @param   array  an array or registration IDs to approve
	 * @return  mixed  either an array of email addresses or FALSE
	 */
	public function approve( $ids )
	{
		$this->load->model('user_model');

		foreach( $ids as $id )
		{
			if( $email_address = $this->_prepare_registration_transfer( $id ) )
			{
				// Create user with pre-validated data
				if( $this->user_model->create_user( 'customer', $this->insert_array ) === TRUE )
				{
					// Delete the temp registration file
					$this->db->delete( config_item('temp_reg_data_table'), array( 'reg_id' => $id ) );

					// Add email address to array for email confirmation
					$email_addresses[] = $email_address;
				}
			}
		}

		if( isset( $email_addresses ) )
		{
			return $email_addresses;
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Approve a registrant by email
	 * 
	 * @param   int  the registrant ID
	 * @return  bool
	 */
	public function approve_by_email( $id )
	{
		if( $this->_prepare_registration_transfer( $id ) !== FALSE )
		{
			$this->load->model('user_model');

			// Create user with pre-validated data
			if( $this->user_model->create_user( 'customer', $this->insert_array ) === TRUE )
			{
				// Delete the temp registration file
				$this->db->delete( config_item('temp_reg_data_table'), array( 'reg_id' => $id ) );

				return TRUE;
			}
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Get a single pending registration's data and prepare 
	 * the necessary data to create a user account
	 *
	 * @param   int    the registrant's registrant ID
	 * @return  mixed  either the necessary data for user account creation or FALSE
	 */
	private function _prepare_registration_transfer( $id )
	{
		// Get the temp registration data
		$query = $this->db->get_where( config_item('temp_reg_data_table'), array( 'reg_id' => $id ) );

		if( $query->num_rows() == 1 )
		{
			$result = $query->row();

			/**
			 * Password and license number are decoded because 
			 * the create_user method encrypts them.
			 */
			$this->load->library('encrypt');

			// Decrypt the password
			$salted_password = $this->encrypt->decode( $result->user_pass );

			// Remove the user salt to obtain the real password
			$real_password = str_replace( $result->user_salt, '', $salted_password );

			// Build insert array for user and profile record
			$this->insert_array = array(
				'user_name'      => $result->user_name,
				'user_email'     => $result->user_email,
				'user_pass'      => $real_password,
				'first_name'     => $result->first_name,
				'last_name'      => $result->last_name,
				'street_address' => $result->street_address,
				'city'           => $result->city,
				'state'          => $result->state,
				'zip'            => $result->zip,
			);

			return $result->user_email;
		}

		return FALSE;
	}

	// --------------------------------------------------------------

}

/* End of file registration_model.php */
/* Location: /application/models/registration_model.php */