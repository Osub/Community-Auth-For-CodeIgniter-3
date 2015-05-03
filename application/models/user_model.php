<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - User_model Model
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class User_model extends MY_Model {

	/**
	 * An array holding values necessary for pagination of users
	 * in the Manage Users area of the adminstration
	 *
	 * @var array
	 * @access private
	 */
	private $query_params = array();

	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	// --------------------------------------------------------------

	/**
	 * Create a new user
	 * 
	 * @param   string  the user type to be created
	 * @param   array  pre-validated data to insert into the user and profile records
	 * @return  bool
	 */
	public function create_user( $role, $insert_array = array() )
	{
		// The form validation class doesn't allow for multiple config files, so we do it the old fashion way
		$this->config->load( 'form_validation/administration/create_user/create_' . $role );
		$this->validation_rules = config_item( $role . '_creation_rules' );

		// If the data is already validated, there's no reason to do it again
		if( ! empty( $insert_array ) OR $this->validate() === TRUE )
		{
			// Prepare user_data array for insert into user table
			$user_data = array(
				'user_name'  => ( isset( $insert_array['user_name'] ) ) ? $insert_array['user_name'] : set_value('user_name'),
				'user_pass'  => ( isset( $insert_array['user_pass'] ) ) ? $insert_array['user_pass'] : set_value('user_pass'),
				'user_email' => ( isset( $insert_array['user_email'] ) ) ? $insert_array['user_email'] : set_value('user_email')
			);

			// User level derived directly from the role argument
			$user_data['user_level'] = $this->authentication->levels[$role];

			// If we are using form validation for the user creation
			if( empty( $insert_array ) )
			{
				// Remove some user_data elements from _field_data array as prep for insert into profile table
				$this->form_validation->unset_field_data( array(
					'user_name',
					'user_pass',
					'user_email'
				));

				// Create array of profile data
				foreach( $this->form_validation->get_field_data() as $k => $v )
				{
					$profile_data[$k] = $v['postdata'];
				}

				// Unset all data for set_value(), so we can create another user
				$this->kill_set_value();
			}

			// If we are not using form validation for the user creation
			else
			{
				// Remove some insert_array elements as prep for insert into profile table
				unset( $insert_array['user_name'] );
				unset( $insert_array['user_pass'] );
				unset( $insert_array['user_email'] );

				// Profile data is insert array
				$profile_data = $insert_array;
			}

			// Encrypt any sensitive data
			if( isset( $profile_data['license_number'] ) )
			{
				$this->load->library('encrypt');
				$profile_data['license_number'] = $this->encrypt->encode( $profile_data['license_number'] );
			}

			// Create a random user id if not already set
			$random_unique_int = $this->get_unused_id();

			// Generate random user salt
			$user_salt = $this->authentication->random_salt();

			// Perform transaction
			$this->db->trans_start();

			$user_data['user_id']       = $random_unique_int;
			$user_data['user_pass']     = $this->authentication->hash_passwd( $user_data['user_pass'], $user_salt );
			$user_data['user_salt']     = $user_salt;
			$user_data['user_date']     = time();
			$user_data['user_modified'] = time();

			// Insert data in user table
			$this->db->set($user_data)
						->insert( config_item('user_table'));

			$profile_data['user_id'] = $random_unique_int;

			// Insert data in profile table
			$this->db->set($profile_data)
						->insert( config_item( $role . '_profiles_table'));

			// Complete transaction
			$this->db->trans_complete();

			// Verify transaction was successful
			if( $this->db->trans_status() !== FALSE )
			{
				// Load var to confirm user inserted into database
				$this->load->vars( array( 'user_created' => 1 ) );
			}

			return TRUE;
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Delete a user
	 * 
	 * @param   int  the user ID to delete
	 * @param   int  the deleter's account type number
	 * @return  bool
	 */
	public function delete_user( $user_id, $deleter_level )
	{
		// Query database for user to be deleted
		$query = $this->db->select('user_level')
			->from( config_item('user_table') )
			->where('user_id', $user_id)
			->limit(1)
			->get();

		// User obviously needs to exist to be deleted
		if( $query->num_rows() == 1 )
		{
			$user_data = $query->row();

			// Deleter must be of higher level than deletee
			if( (int) $user_data->user_level < $deleter_level )
			{
				// Perform transaction
				$this->db->trans_start();

				// Delete user table record
				$this->db->delete( 
					config_item('user_table'), 
					array( 'user_id' => $user_id ) 
				);

				// Get the user's role
				$role = $this->authentication->roles[$user_data->user_level];

				// Delete profile table record
				$this->db->delete( 
					config_item( $role . '_profiles_table'), 
					array( 'user_id' => $user_id ) 
				);

				// Complete transaction
				$this->db->trans_complete();

				// Delete user from both profiles table and users table
				if( $this->db->trans_status() !== FALSE )
				{
					// Delete was a success
					return TRUE;
				}
			}
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Update a user
	 * 
	 * @param  int     the user ID to update
	 * @param  string  the type of update
	 * @param  array   the data to update in the user table
	 * @param  array   the data to update in the profile table
	 * @return bool
	 */
	public function update_user( $role, $the_user, $update_type, $user_data = array(), $profile_data = array() )
	{
		// Load the appropriate form validation rules from the config file
		$this->config->load( 'form_validation/user/user_update' );

		switch( $update_type )
		{
			case 'self_update':
				$this->validation_rules = config_item( 'self_update_' . $role );
				break;
			case 'update_user':
				$this->validation_rules = config_item( 'update_user_' . $role );
				break;

			case 'profile_image':

			default:
				$_POST = array_merge( $user_data, $profile_data );
				$this->validation_rules = config_item( $update_type );
				break;
		}

		if( $this->validate() )
		{
			// Sort the posted data so it goes in the right table
			$user_fields = $this->db->list_fields( config_item('user_table') );

			$new_time = time();

			foreach( $this->form_validation->get_field_data() as $k => $v )
			{
				if( in_array( $k, $user_fields ) )
				{
					$user_arr[$k] = $v['postdata'];
				}
				else
				{
					$profile_arr[$k] = $v['postdata'];
				}
			}

			// Encrypt anything that needs encryption
			if( isset( $profile_arr['license_number'] ) )
			{
				$profile_arr['license_number'] = $this->encrypt->encode( $profile_arr['license_number'] );
			}

			// Perform transaction
			$this->db->trans_start();

			// If the password was set to be updated
			if( 
				isset( $user_arr['user_pass'], $profile_arr['user_pass_confirm'] ) &&
				! empty( $user_arr['user_pass'] ) 
			)
			{
				$this->_change_password(
					$user_arr['user_pass'],
					$profile_arr['user_pass_confirm'],
					$the_user,
					config_item('encryption_key')
				);
			}

			// Password fields need to be unset
			$user_arr['user_pass'] = 'x';
			$profile_arr['user_pass_confirm'] = 'x';
			unset( $user_arr['user_pass'] );
			unset( $profile_arr['user_pass_confirm'] );	

			// There's always going to be a user_modified change
			$user_arr['user_modified'] = $new_time;

			// Update user table record
			$this->db->where('user_id', $the_user)
					->update( config_item('user_table'), $user_arr );

			// Update profile table record
			if( ! empty( $profile_arr ) )
			{
				$this->db->where('user_id', $the_user)
				->update( config_item( $role . '_profiles_table'), $profile_arr );
			}

			// If a self update, recreate the auth_identifier
			if( config_item('auth_user_id') == $the_user )
			{
				// The user's last login time is needed
				$login_time = $this->authentication->expose_login_time( $this->session->userdata('auth_identifier') );

				$this->session->set_userdata( 
					'auth_identifier',
					$this->authentication->create_auth_identifier(
						$this->auth_user_id,
						$new_time,
						$login_time
					)
				);
			}

			// Complete transaction
			$this->db->trans_complete();

			// Verify transaction was successful
			if( $this->db->trans_status() !== FALSE )
			{
				// Profile image is not a mandatory upload
				if( 
					$update_type == 'self_update' &&
					! empty( $_FILES['userfile']['tmp_name'] ) 
				)
				{
					// Set upload location (only for filesystem uploads)
					$this->upload->primary_dir = 'profile_images';
					$this->upload->secondary_dir = $the_user;

					// Set the upload success callback
					$this->upload->success_callback = '_profile_image';

					/**
					 * Upload the photo using the specific upload destination 
					 * and type specified in the uploads_manager config.
					 */
					$upload_response = $this->upload->upload_bridge( 
						'profile_image', 
						config_item('profile_image_destination') 
					);

					// Output an error message if there was an error
					if( $upload_response['status'] != 'success' )
					{
						$this->load->vars( array( 'validation_errors' => $upload_response['issue'] ) );

						return FALSE;
					}
				}

				return TRUE;
			}
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Update a user record with data not from POST
	 *
	 * @param  int     the user ID to update
	 * @param  array   the data to update in the user table
	 * @param  array   the data to update in the profile table
	 * @return bool
	 */
	public function update_user_raw_data( $the_user, $user_data = array(), $profile_data = array() )
	{
		// Perform transaction
		$this->db->trans_start();

		// Update user record
		if( ! empty( $user_data ) )
		{
			$this->db->where('user_id', $the_user)
					->update( config_item('user_table'), $user_data );
		}

		// Update profile record
		if( ! empty( $profile_data ) )
		{
			// Get the user_level so we know what profile table to update
			$query = $this->db->select('user_level')
				->where('user_id', $the_user)
				->limit(1)
				->get( config_item('user_table') );

			if( $query->num_rows() == 1 )
			{
				$row = $query->row();

				// Get the user's role
				$role = $this->authentication->roles[$row->user_level];

				$this->db->where('user_id', $the_user)
					->update( config_item( $role . '_profiles_table'), $profile_data );
			}
		}

		// Complete transaction
		$this->db->trans_complete();

		// Verify transaction was successful
		if( $this->db->trans_status() !== FALSE )
		{
			return TRUE;
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Get data for a recovery
	 * 
	 * @param   string  the email address
	 * @return  mixed   either query data or FALSE
	 */
	public function get_recovery_data( $email )
	{
		$query = $this->db->select('u.user_id, u.user_salt, u.user_email, u.user_banned')
			->from( config_item('user_table') . ' u')
			->where('u.user_email', $email)
			->limit(1)
			->get();

		if( $query->num_rows() == 1 )
		{
			return $query->row();
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Get the user name, user salt, and hashed recovery code,
	 * but only if the recovery code hasn't expired.
	 */
	public function get_recovery_verification_data( $user_id )
	{
		$query = $this->db->select('user_name,user_salt,passwd_recovery_code')
			->from( config_item('user_table') )
			->where( 'user_id', $user_id )
			->where( 'passwd_recovery_date >', time() - config_item('recovery_code_expiration') )
			->limit(1)
			->get();

		if ( $query->num_rows() == 1 )
		{
			return $query->row();
		}
		
		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Validation and processing for password change during account recovery
	 */
	public function recovery_password_change()
	{
		// The form validation class doesn't allow for multiple config files, so we do it the old fashion way
		$this->config->load( 'form_validation/user/recovery_verification' );
		$this->validation_rules = config_item('recovery_verification');

		if( $this->validate() )
		{
			$this->_change_password(
				set_value('user_pass'),
				set_value('user_pass_confirm'),
				$this->input->post('user_identification'),
				$this->input->post('recovery_code')
			);
		}
	}

	// --------------------------------------------------------------

	/**
	 * Change a user's password
	 * 
	 * @param  string  the form token
	 * @param  string  the flash token to match the form token
	 * @param  string  the new password
	 * @param  string  the new password confirmed
	 * @param  string  the user ID
	 * @param  string  the special string
	 */
	protected function _change_password( $password, $password2, $user_id, $special_string )
	{
		// User ID check
		if( isset( $user_id ) && $user_id !== FALSE )
		{
			$this->db->select('user_id');

			// If special string is the CI encryption key, this is a self update or user update.
			if( $special_string == config_item('encryption_key') )
			{
				$this->db->where( 'user_id', $user_id );
			}

			// If the special string was not present, this is a password recovery
			else
			{
				$this->db->where( 'user_id', $user_id );
				$this->db->where( 'passwd_recovery_code', $special_string );
			}

			$query = $this->db->get_where( config_item('user_table') );

			// If above query indicates a match, change the password
			if( $query->num_rows() == 1 )
			{
				$user_data = $query->row();

				// Generate a new random user salt
				$new_salt = $this->authentication->random_salt();

				$data = array(
					'user_pass' => $this->authentication->hash_passwd( $password, $new_salt ),
					'user_salt' => $new_salt
				);

				$this->db->where( 'user_id', $user_data->user_id )
					->update( config_item('user_table'), $data );
			}
		}
	}

	// --------------------------------------------------------------

	/**
	 * Get a user record by user ID
	 * 
	 * @param   int    the user ID
	 * @param   string  specific field to select from row
	 * @return  mixed  either the query data or FALSE
	 */
	public function view_user_record( $user_id, $field = FALSE )
	{
		if( (int) $user_id !== 0 )
		{
			$query = $this->db->select( $field === FALSE ? '*' : $field )
				->where('user_id', $user_id)
				->limit(1)
				->get( config_item('user_table') );

			if( $query->num_rows() == 1 )
			{
				$row = $query->row_array();

				if( ! $field )
				{
					// Get profile data
					$role = $this->authentication->roles[$row['user_level']];

					// Profile data query
					$query = $this->db->select('*')
						->from( config_item( $role . '_profiles_table') )
						->where( 'user_id', $row['user_id'] )
						->limit(1)
						->get();

					if ( $query->num_rows() == 1 )
					{
						// Merge the user data and profile data and return
						return (object) array_merge( $row,  $query->row_array() );
					}
				}
				else
				{
					return $row[$field];
				}
			}
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Perform either the count or data query for a specific set or users
	 *
	 * Query params are set the first time this method is called, which
	 * is during the count of all of the user records that fit the 
	 * specific set we are searching. Because we are using a class member 
	 * to hold these params, we don't have to send them twice when the 
	 * method is called the second time, which is when we get the actual 
	 * user data
	 *
	 * @param   array   the values to obtain a custom set of users
	 * @param   bool    whether or not the query is the count
	 * @return  mixed   either int, query data as an object, or FALSE
	 */
	public function manage_user_records_data( $params = FALSE, $count = FALSE )
	{
		// Set params if this is the first call
		if( $params !== FALSE )
		{
			$this->query_params = $params;

			// Compute / set the offset for the query
			$this->query_params['offset'] = ( $params['page'] * $params['limit'] ) - $params['limit'];
		}

		// If this is the actual data query, we want to add a SELECT to our query
		if( ! $count )
		{
			$this->db->select('u.*');
		}

		$this->db->from( config_item('user_table') . ' u');
		$this->db->where('u.user_level <', $this->query_params['user_level'] );

		// If this is a search, it may change the number of results
		if( ! empty( $this->query_params['search_in'] ) && ! empty( $this->query_params['search_for'] ) )
		{
			$this->db->like( $this->query_params['search_in'], $this->query_params['search_for'] );
		}

		// If this is the row count
		if( $count )
		{
			return $this->db->count_all_results();
		}

		// If this is the data query
		else
		{
			// Set the limit / offset
			$this->db->limit( $this->query_params['limit'], $this->query_params['offset'] );

			// Order by user creation date so newest users appear first
			$this->db->order_by( 'user_date', 'desc' );

			$query = $this->db->get();

			// Return data if there is any
			if( $query->num_rows() > 0 )
			{
				return $query->result();
			}
		}

		return FALSE;
	}

	/**
	 * Get an unused ID for user creation
	 * 
	 * @param   bool  whether to generate a user Id or registration ID
	 * @return  int
	 */
	public function get_unused_id( $temp = FALSE )
	{
		// Create a random user id
		$random_unique_int = mt_rand(1200,999999999);

		// Make sure the random user_id isn't already in use
		if($temp === FALSE)
		{
			// Generate unused user ID
			$query = $this->db->where('user_id', $random_unique_int)
								->get_where( config_item('user_table'));
		}
		else
		{
			// Generate unused registration ID
			$query = $this->db->where('reg_id', $random_unique_int)
								->get_where( config_item('temp_reg_data_table'));
		}

		if ($query->num_rows() > 0)
		{
			$query->free_result();

			// If the random user_id is already in use, get a new number
			$this->get_unused_id($temp);
		}

		return $random_unique_int;
	}

	// --------------------------------------------------------------

}

/* End of file user_model.php */
/* Location: /application/models/user_model.php */