<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Special Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Special extends MY_Controller{
	
	public function __construct()
	{
		parent::__construct();

		// Force SSL
		//$this->force_ssl();
	}

	// -----------------------------------------------------------------------

	/**
	 * Demonstrate being redirected to login.
	 * If you are logged in and request this method, 
	 * you'll see the message, otherwise you will be 
	 * shown the login form.
	 */
	public function index()
	{
		if( $this->require_role('admin') )
		{
			echo 'Logged in</br>' . secure_site_url('user/logout', 'Logout');
		}
	}
	
	// -----------------------------------------------------------------------

	/**
	 * Most minimal user creation. You will of course make your 
	 * own interface for adding users, and you may even let users
	 * register and create their own accounts.
	 */
	public function create_user()
	{
		$user_data = array(
			'user_name'     => 'skunkbad',
			'user_pass'     => 'Something1',
			'user_email'    => 'example@gmail.com',
			'user_level'    => 9,
			'user_id'       => $this->_get_unused_id(),
			'user_salt'     => $this->authentication->random_salt(),
			'user_date'     => time(),
			'user_modified' => time()
		);

		$user_data['user_pass'] = $this->authentication->hash_passwd( $user_data['user_pass'], $user_data['user_salt'] );

		// Insert data in user table
		$this->db->set($user_data)
			->insert( config_item('user_table'));
	}
	
	// -----------------------------------------------------------------------

	/**
	 * Get an unused ID for user creation
	 * 
	 * @return  int
	 */
	private function _get_unused_id()
	{
		// Create a random user id
		$random_unique_int = mt_rand(1200,999999999);

		// Make sure the random user_id isn't already in use
		$query = $this->db->where('user_id', $random_unique_int)
			->get_where( config_item('user_table'));

		if( $query->num_rows() > 0 )
		{
			$query->free_result();

			// If the random user_id is already in use, get a new number
			return $this->_get_unused_id();
		}

		return $random_unique_int;
	}

	// --------------------------------------------------------------
}

/* End of file Special.php */
/* Location: /application/controllers/Special.php */