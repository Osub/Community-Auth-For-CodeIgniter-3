<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Examples Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Examples extends MY_Controller{
	
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
	 * shown the login form. Once login is achieved, 
	 * you will be redirected back to this method.
	 */
	public function index()
	{
		if( $this->require_role('admin') )
		{
			echo 'Logged in</br>' . secure_anchor('user/logout', 'Logout');
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
		// Customize this array for your user
		$user_data = array(
			'user_name'     => 'skunkbot',
			'user_pass'     => 'Something1',
			'user_email'    => 'example@hotmail.com',
			'user_level'    => 1,
			'user_id'       => $this->_get_unused_id(),
			'user_salt'     => $this->authentication->random_salt(),
			'user_date'     => time(),
			'user_modified' => time()
		);

		$user_data['user_pass'] = $this->authentication->hash_passwd( $user_data['user_pass'], $user_data['user_salt'] );

		$this->db->set($user_data)
			->insert( config_item('user_table'));

		if( $this->db->affected_rows() == 1 )
		{
			echo 'User ' . $user_data['user_name'] . ' was created.';
		}

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

/* End of file Examples.php */
/* Location: /application/controllers/Examples.php */