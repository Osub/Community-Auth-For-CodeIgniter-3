<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Init Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Init extends MY_Controller {

	/**
	 * Admin's user level
	 *
	 * @var int
	 * @access private
	 */
	private $admin_user_level;

	/**
	 * Errors to display in the view
	 *
	 * @var array
	 * @access private
	 */
	private $error_message_stack = array();

	/**
	 * The tables in the database
	 *
	 * @var array
	 * @access private
	 */
	private $tables              = array();

	// --------------------------------------------------------------

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// Force encrypted connection
		$this->force_ssl();

		// Load resources
		$this->load->model('user_model');

		// Get the admin user level number
		$this->admin_user_level = $this->authentication->levels['admin'];

		// Use special template
		$this->template = 'templates/installation_template';

		// Get tables in database
		$this->tables = $this->db->list_tables();
	}

	// --------------------------------------------------------------

	/**
	 * Population of database tables, creation of admin, and creation of test users.
	 */
	public function index()
	{
		// Check if script totally disabled
		if( ! config_item('disable_installer') )
		{
			// Check if a valid form submission has been made
			if( $this->tokens->match )
			{
				if( 
					// If there are already tables created
					( ! $this->input->post('populate_database') && count( $this->tables ) > 0 ) OR

					// If there are no tables, but tables to be created
					$this->input->post('populate_database')
				)
				{
					// Run the validation
					$this->load->library('form_validation');
					$this->form_validation->set_error_delimiters('<li>', '</li>');

					// The form validation class doesn't allow for multiple config files, so we do it the old fashion way
					$this->config->load( 'form_validation/init/install' );
					$this->form_validation->set_rules( config_item('install_rules') );

					// If the post validates
					if( $this->form_validation->run() !== FALSE )
					{
						// Check if tables to be created
						$tables = set_value('populate_database');

						// Check if admin to be created
						$admin = set_value('admin');

						// Check if test users to be created
						$users = set_value('users');

						// Apply the test user password now because admin creation resets the form validation class
						$test_users_pass = set_value('test_users_pass');

						if( ! empty( $tables ) )
						{
							// Create the tables
							$tables_status = $this->_populate_database();
						}

						if( ! empty( $admin ) )
						{
							// Create the admin
							$this->_create_admin();
						}

						if( ! empty( $users ) )
						{
							// Create the test users
							$this->_create_test_users( $test_users_pass );
						}

						//kill set_value() since we won't need it
						$this->form_validation->unset_field_data('*');
					}

					// If validation failed
					else
					{
						// show errors
						$view_data['error_message_stack'] = validation_errors();

						// do not repopulate with data that did not validate
						$error_array = $this->form_validation->get_error_array();

						foreach( $this->input->post() as $k => $v )
						{
							if( array_key_exists( $k, $error_array ))
							{
								//kill set_value()
								$this->form_validation->unset_field_data( $k );
							}
						}
					}
				}
				else
				{
					$this->error_message_stack[] = '<li>You Must First Populate the Database Before Creating Users.</li>';
				}
			}

			// If a valid form submission has not been made, show error
			else if( ! empty( $_POST ) )
			{
				$this->error_message_stack[] = '<li>No Token Match</li>';
			}

			// If there are already tables created, or if the tables were just created
			if( count( $this->tables ) > 0 OR ( isset( $tables_status ) && $tables_status === TRUE ) )
			{
				$view_data['tables_installed'] = TRUE;

				// Check if admin created
				$query = $this->db->get_where( 
					config_item( 'user_table' ), 
					array( 'user_level' => $this->admin_user_level ) 
				);

				$view_data['admin_created'] = ( $query->num_rows() > 0 ) ? TRUE : FALSE;

				// Check how many non-admin users exist
				$this->db->where( 'user_level !=', $this->admin_user_level );

				$view_data['basic_user_count'] = $this->db->count_all_results( config_item( 'user_table' ) );
			}
			else
			{
				$view_data['tables_installed'] = FALSE;

				$view_data['admin_created']    = FALSE;
			}

			if( ! empty( $this->error_message_stack ) )
			{
				$view_data['error_message_stack'] = $this->error_message_stack;
			}

			$data = array(
				'title'     => 'Community Auth Installation',
				'no_robots' => 1,
				'javascripts' => array(
					'js/jquery.char-limiter-3.0.0.js',
					'js/default-char-limiters.js',
					'js/init/install.js'
				),
				'content'   => $this->load->view( 'init/install', $view_data, TRUE )
			);

			$this->load->view( $this->template, $data );
		}
		else
		{
			show_404();
		}
	}

	// --------------------------------------------------------------

	/**
	 * Population of database (table creation)
	 */
	private function _populate_database()
	{
		// Load db.sql file as string
		if( $sql = $this->load->view( 'sql/db', '', TRUE ) )
		{
			// Get the db connection platform
			$platform = $this->db->platform();

			// If mysqli or mysql
			if( $platform == 'mysqli' OR $platform == 'mysql' )
			{
				// Break the sql file into separate queries
				$queries = explode( ';', $sql );

				// Do each query
				foreach( $queries as $query )
				{
					$this->db->simple_query( trim( $query ) );
				}

				return TRUE;
			}

			// If not mysqli or mysql
			else
			{
				$this->error_message_stack[] = '<li>Database Platform Not Supported</li>';
			}
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Admin creation
	 */
	private function _create_admin()
	{
		// Reset the form validation class
		$this->form_validation->reset();

		// Set the admin user level
		$_POST['user_level'] = $this->admin_user_level;

		$this->user_model->create_user( 'admin', array() );
	}

	// --------------------------------------------------------------

	/**
	 * Creation of test users
	 */
	private function _create_test_users( $test_users_pass )
	{
		// Make sure the test users password is not empty
		if( ! empty( $test_users_pass ) )
		{
			// Get the array of test users
			$test_user_data = $this->_get_test_users_data();

			// Check if even one of the test users already exists
			$i = 0;
			foreach( $test_user_data as $user )
			{
				if( $i == 0 )
				{
					$this->db->where( 'user_name', $user[0] );
					$this->db->or_where( 'user_email', $user[1] );
				}
				else
				{
					$this->db->or_where( 'user_name', $user[0] );
					$this->db->or_where( 'user_email', $user[1] );
				}
				$i++;
			}
			$this->db->from( config_item('user_table') );
			$result = $this->db->count_all_results();

			// If none of the test users exist
			if( $result == 0 )
			{
				// Load the encryption library to encrypt the license number
				$this->load->library('encrypt');

				// Start test user's user IDs at 93062220
				$test_user_id = 93062220;

				foreach( $test_user_data as $user )
				{
					// Generate random user salt
					$user_salt = $this->authentication->random_salt();

					// Setup user record
					$user_data[] = array(
						'user_id'       => $test_user_id,
						'user_name'     => $user[0],
						'user_pass'     => $this->authentication->hash_passwd( $test_users_pass, $user_salt ),
						'user_salt'     => $user_salt,
						'user_email'    => $user[1],
						// The first 5 test users will be managers, and the rest are customers.
						'user_level'    => ( $test_user_id < 93062224 ) ? 6 : 1,
						'user_date'     => time(),
						'user_modified' => time()
					);

					// Setup profile records
					if( $test_user_id < 93062224 )
					{
						$manager_data[] = array(
							'user_id'        => $test_user_id,
							'first_name'     => $user[2],
							'last_name'      => $user[3],
							'license_number' => $this->encrypt->encode( $user[4] ),
							'phone_number'   => $user[5]
						);
					}
					else
					{
						$customer_data[] = array(
							'user_id'        => $test_user_id,
							'first_name'     => $user[2],
							'last_name'      => $user[3],
							'street_address' => $user[4],
							'city'           => $user[5],
							'state'          => $user[6],
							'zip'            => $user[7],
						);
					}

					$test_user_id++;
				}

				// Insert the user records
				$this->db->insert_batch( config_item('user_table'), $user_data );

				// Insert the managers
				$this->db->insert_batch( config_item('manager_profiles_table'), $manager_data );

				// Insert the customers
				$this->db->insert_batch( config_item('customer_profiles_table'), $customer_data );
			}
			else
			{
				$this->error_message_stack[] = '<li>Please Remove All Test Users Before Re-installing.</li>';
			}
		}
		else
		{
			$this->error_message_stack[] = '<li>All Test User Fields Must be Filled in to Create Test Users.</li>';
		}
	}

	// --------------------------------------------------------------

	/**
	 * Array of test users ( U.S. Presidents )
	 *
	 * Please note that the email addresses provided may or may not be 
	 * real email addresses. You should not use the test users outside 
	 * of the development environment so that you don't send 
	 * emails to people that may have one of these email addresses.
	 */
	private function _get_test_users_data()
	{
		return array(
			array('gwashing','gwashington@gmail.com','George','Washington','17891797','555-555-5555'),
			array('jadams02','johnadams@hotmail.com','John','Adams','17971801','555-555-5555'),
			array('tjeffers','thomasjefferson@msn.com','Thomas','Jefferson','18011809','555-555-5555'),
			array('jmadison','jamesmadison@earthlink.net','James','Madison','18091817','555-555-5555'),
			array('jmonroe5','jamesmonroe@yahoo.com','James','Monroe','','','',''),
			array('jqadams6','johnqadams@gmail.com','John','Adams','','','',''),
			array('ajackson','andrewjackson@yahoo.com','Andrew','Jackson','','','',''),
			array('mvburen8','martinvanburen@msn.com','Martin','Van Buren','','','',''),
			array('wharriso','williamharrison@yahoo.com','William','Harrison','','','',''),
			array('jtyler10','johntyler@hotmail.com','John','Tyler','','','',''),
			array('jkpolk11','jameskpolk@gmail.com','James','Polk','','','',''),
			array('ztaylor2','zacharytaylor@yahoo.com','Zachary','Taylor','','','',''),
			array('mfillmor','millardfillmore@gmail.com','Millard','Fillmore','','','',''),
			array('fpierce4','franklinpierce@yahoo.com','Franklin','Pierce','','','',''),
			array('jbuchana','jamesbuchanan@hotmail.com','James','Buchanan','','','',''),
			array('alincoln','abrahamlincoln@gmail.com','Abraham','Lincoln','','','',''),
			array('ajohnson','andrewjohnson@gmail.com','Andrew','Johnson','','','',''),
			array('ugrant18','ulyssesgrant@hotmail.com','Ulysses','Grant','','','',''),
			array('rhayes19','rutherfordbhayes@msn.com','Rutherford','Hayes','','','',''),
			array('jgarfiel','jamesgarfield@yahoo.com','James','Garfield','','','',''),
			array('caarthur','chesterarthur@msn.com','Chester','Arthur','','','',''),
			array('gclevela','grovercleveland@yahoo.com','Grover','Cleveland','','','',''),
			array('bharriso','benjaminharrison@gmail.com','Benjamin','Harrison','','','',''),
			array('gclevel2','grovercleveland@msn.com','Grover','Cleveland','','','',''),
			array('wmckinle','williammckinley@gmail.com','William','McKinley','','','',''),
			array('trooseve','theodoreroosevelt@msn.com','Theodore','Roosevelt','','','',''),
			array('whtaft27','williamhtaft@mac.com','William','Taft','','','',''),
			array('wwilson8','woodrowwilson@yahoo.com','Woodrow','Wilson','','','',''),
			array('wharding','warrenharding@gmail.com','Warren','Harding','','','',''),
			array('ccoolidg','calvincoolidge@hotmail.com','Calvin','Coolidge','','','',''),
			array('hhoover3','herberthoover@gmail.com','Herbert','Hoover','','','',''),
			array('frooseve','franklindroosevelt@yahoo.com','Franklin','Roosevelt','','','',''),
			array('htruman7','harrytruman@msn.com','Harry','Truman','','','',''),
			array('deisenho','dwighteisenhower@mac.com','Dwight','Eisenhower','','','',''),
			array('jkennedy','johnfkennedy@mac.com','John','Kennedy','','','',''),
			array('ljohnson','lyndonbjohnson@hotmail.com','Lyndon','Johnson','','','',''),
			array('rnixon98','richardnixon@hotmail.com','Richard','Nixon','','','',''),
			array('gford383','geraldford@mac.com','Gerald','Ford','','','',''),
			array('jcarter8','jimmycarter@aol.com','Jimmy','Carter','','','',''),
			array('rreagan7','ronaldreagan@gmail.com','Ronald','Reagan','','','',''),
			array('ghwbush1','georgehwbush@gmail.com','George','Bush','','','',''),
			array('bclinton','billclinton@yahoo.com','Bill','Clinton','','','',''),
			array('gwbush20','georgebush@gmail.com','George','Bush','','','',''),
			array('bobama66','theclown@whitehouse.gov','Barack','Obama','','','',''),
		);
	}

	// --------------------------------------------------------------
}

/* End of file init.php */
/* Location: /application/controllers/init.php */