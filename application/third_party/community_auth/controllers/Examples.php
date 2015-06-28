<?php
defined('BASEPATH') or exit('No direct script access allowed');

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

class Examples extends MY_Controller
{
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
        if ($this->require_role('admin')) {
            echo 'Logged in</br>' . secure_anchor('user/logout', 'Logout');
        }
    }
    
    // -----------------------------------------------------------------------

    /**
     * Demonstrate an optional login.
     * Remember to add "examples/optional_login_test" to the
     * allowed_pages_for_login array in config/authentication.php.
     *
     * Notice that we are using verify_min_level to check if
     * a user is already logged in.
     */
    public function optional_login_test()
    {
        if ($this->verify_min_level(1)) {
            echo 'Optionally logged in</br>' . secure_anchor('user/logout', 'Logout');
        } elseif ($this->tokens->match && $this->optional_login()) {
            // Let Community Auth handle the login attempt ...
        } else {
            // Show your form here ...
            echo '<p>You are not logged in, but can still see this page.</p>';

            $this->setup_login_form();

            $this->load->vars([ 'optional_login' => true ]);

            $this->load->view('auth/login_form');
        }
    }
    
    // -----------------------------------------------------------------------

    /**
     * Most minimal user creation. You will of course make your
     * own interface for adding users, and you may even let users
     * register and create their own accounts.
     *
     * The password used in the $user_data array needs to meet the
     * following default strength requirements:
     *   - Must be at least 8 characters long
     *   - Must have at least one digit
     *   - Must have at least one lower case letter
     *   - Must have at least one upper case letter
     *   - Must not have any space, tab, or other whitespace characters
     *   - No backslash, apostrophe or quote chars are allowed
     */
    public function create_user()
    {
        // Customize this array for your user
        $user_data = array(
            'user_name'     => 'skunkbot',
            'user_pass'     => 'Something1',
            'user_email'    => 'skunkbot@example.com',
            'user_level'    => '1', // 9 if you want to login @ examples/index.
        );

        $this->load->library('form_validation');

        $this->form_validation->set_data( $user_data );

        $validation_rules = array(
			array(
				'field' => 'user_name',
				'label' => 'user_name',
				'rules' => 'required|max_length[12]'
			),
			array(
				'field' => 'user_pass',
				'label' => 'user_pass',
				'rules' => 'trim|required|external_callbacks[model,formval_callbacks,_check_password_strength,TRUE]',
			),
			array(
				'field' => 'user_email',
				'label' => 'user_email',
				'rules' => 'required|valid_email'
			),
			array(
				'field' => 'user_level',
				'label' => 'user_level',
				'rules' => 'required|integer|in_list[1,6,9]'
			)
		);

		$this->form_validation->set_rules( $validation_rules );

		if( $this->form_validation->run() )
		{
			$user_data['user_salt']     = $this->authentication->random_salt();
			$user_data['user_pass']     = $this->authentication->hash_passwd($user_data['user_pass'], $user_data['user_salt']);
			$user_data['user_id']       = $this->_get_unused_id();
			$user_data['user_date']     = date('Y-m-d H:i:s');
			$user_data['user_modified'] = date('Y-m-d H:i:s');

			$this->db->set($user_data)
				->insert(config_item('user_table'));

			if ($this->db->affected_rows() == 1) {
				echo '<h1>Congratulations</h1>' . '<p>User ' . $user_data['user_name'] . ' was created.</p>';
			}
		}
		else
		{
			echo '<h1>User Creation Error(s)</h1>' . validation_errors();
		}
    }
    
    // -----------------------------------------------------------------------

    /**
     * Get an unused ID for user creation
     *
     * @return  int between 1200 and 4294967295
     */
    private function _get_unused_id()
    {
        // Create a random user id
        $random_unique_int = mt_rand(1200, 4294967295);

        // Make sure the random user_id isn't already in use
        $query = $this->db->where('user_id', $random_unique_int)
            ->get_where(config_item('user_table'));

        if ($query->num_rows() > 0) {
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
