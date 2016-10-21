<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Community Auth - Examples Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2016, Robert B Gottier. (http://brianswebdesign.com/)
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

        // Form and URL helpers always loaded (just for convenience)
        $this->load->helper('url');
        $this->load->helper('form');
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
            echo $this->load->view('examples/page_header', '', TRUE);

            echo '<p>You are logged in!</p>';

            echo $this->load->view('examples/page_footer', '', TRUE);
        }
    }
    
    // -----------------------------------------------------------------------

    /**
     * A basic page that shows verification that the user is logged in or not.
     * If the user is logged in, a link to "Logout" will be in the menu.
     * If they are not logged in, a link to "Login" will be in the menu.
     */
    public function home()
    {
        $this->is_logged_in();
        
        echo $this->load->view('examples/page_header', '', TRUE);

        echo '<p>Welcome Home</p>';

        echo $this->load->view('examples/page_footer', '', TRUE);
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
        if( $this->verify_min_level(1) )
        {
            $page_content = '<p>Although not required, you are logged in!</p>';
        }
        elseif( $this->tokens->match && $this->optional_login() )
        {
            // Let Community Auth handle the login attempt ...
        }
        else
        {
            // Notice parameter set to TRUE, which designates this as an optional login
            $this->setup_login_form(TRUE);

            $page_content = '<p>You are not logged in, but can still see this page.</p>';

            // Form helper needed
            $this->load->helper('form');

            $page_content .= $this->load->view('examples/login_form', '', TRUE);
        }

        echo $this->load->view('examples/page_header', '', TRUE);

        echo $page_content;

        echo $this->load->view('examples/page_footer', '', TRUE);
    }
    
    // -----------------------------------------------------------------------

    /**
     * Here we simply verify if a user is logged in, but
     * not enforcing authentication. The presence of auth 
     * related variables that are not empty indicates 
     * that somebody is logged in. Also showing how to 
     * get the contents of the HTTP user cookie.
     */
    public function simple_verification()
    {
        $this->is_logged_in();

        echo $this->load->view('examples/page_header', '', TRUE);

        echo '<p>';
        if( ! empty( $this->auth_role ) )
        {
            echo $this->auth_role . ' logged in!<br />
                User ID is ' . $this->auth_user_id . '<br />
                Auth level is ' . $this->auth_level . '<br />
                Username is ' . $this->auth_username;

            if( $http_user_cookie_contents = $this->input->cookie( config_item('http_user_cookie_name') ) )
            {
                $http_user_cookie_contents = unserialize( $http_user_cookie_contents );
                
                echo '<br />
                    <pre>';

                print_r( $http_user_cookie_contents );

                echo '</pre>';
            }

            if( config_item('add_acl_query_to_auth_functions') && $this->acl )
            {
                echo '<br />
                    <pre>';

                print_r( $this->acl );

                echo '</pre>';
            }

            /**
             * ACL usage doesn't require ACL be added to auth vars.
             * If query not performed during authentication, 
             * the acl_permits function will query the DB.
             */
            if( $this->acl_permits('general.secret_action') )
            {
                echo '<p>ACL permission grants action!</p>';
            }
        }
        else
        {
            echo 'Nobody logged in.';
        }

        echo '</p>';

        echo $this->load->view('examples/page_footer', '', TRUE);
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
     *   - Must be at less than 72 characters long
     *   - Must have at least one digit
     *   - Must have at least one lower case letter
     *   - Must have at least one upper case letter
     *   - Must not have any space, tab, or other whitespace characters
     *   - No backslash, apostrophe or quote chars are allowed
     */
    public function create_user()
    {
        // Customize this array for your user
        $user_data = [
            'username'   => 'skunkbot',
            'passwd'     => 'PepeLePew7',
            'email'      => 'skunkbot@example.com',
            'auth_level' => '1', // 9 if you want to login @ examples/index.
        ];

        $this->is_logged_in();

        echo $this->load->view('examples/page_header', '', TRUE);

        // Load resources
        $this->load->model('examples/examples_model');
        $this->load->model('examples/validation_callables');
        $this->load->library('form_validation');

        $this->form_validation->set_data( $user_data );

        $validation_rules = [
			[
				'field' => 'username',
				'label' => 'username',
				'rules' => 'max_length[12]|is_unique[' . config_item('user_table') . '.username]',
                'errors' => [
                    'is_unique' => 'Username already in use.'
                ]
			],
			[
				'field' => 'passwd',
				'label' => 'passwd',
				'rules' => [
                    'trim',
                    'required',
                    [ 
                        '_check_password_strength', 
                        [ $this->validation_callables, '_check_password_strength' ] 
                    ]
                ],
                'errors' => [
                    'required' => 'The password field is required.'
                ]
			],
			[
                'field'  => 'email',
                'label'  => 'email',
                'rules'  => 'trim|required|valid_email|is_unique[' . config_item('user_table') . '.email]',
                'errors' => [
                    'is_unique' => 'Email address already in use.'
                ]
			],
			[
				'field' => 'auth_level',
				'label' => 'auth_level',
				'rules' => 'required|integer|in_list[1,6,9]'
			]
		];

		$this->form_validation->set_rules( $validation_rules );

		if( $this->form_validation->run() )
		{
            $user_data['passwd']     = $this->authentication->hash_passwd($user_data['passwd']);
            $user_data['user_id']    = $this->examples_model->get_unused_id();
            $user_data['created_at'] = date('Y-m-d H:i:s');

            // If username is not used, it must be entered into the record as NULL
            if( empty( $user_data['username'] ) )
            {
                $user_data['username'] = NULL;
            }

			$this->db->set($user_data)
				->insert(config_item('user_table'));

			if( $this->db->affected_rows() == 1 )
				echo '<h1>Congratulations</h1>' . '<p>User ' . $user_data['username'] . ' was created.</p>';
		}
		else
		{
			echo '<h1>User Creation Error(s)</h1>' . validation_errors();
		}

        echo $this->load->view('examples/page_footer', '', TRUE);
    }
    
    // -----------------------------------------------------------------------

    /**
     * This login method only serves to redirect a user to a 
     * location once they have successfully logged in. It does
     * not attempt to confirm that the user has permission to 
     * be on the page they are being redirected to.
     */
    public function login()
    {
        // Method should not be directly accessible
        if( $this->uri->uri_string() == 'examples/login')
            show_404();

        if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' )
            $this->require_min_level(1);

        $this->setup_login_form();

        $html = $this->load->view('examples/page_header', '', TRUE);
        $html .= $this->load->view('examples/login_form', '', TRUE);
        $html .= $this->load->view('examples/page_footer', '', TRUE);

        echo $html;
    }

    // --------------------------------------------------------------

    /**
     * Log out
     */
    public function logout()
    {
        $this->authentication->logout();

        // Set redirect protocol
        $redirect_protocol = USE_SSL ? 'https' : NULL;

        redirect( site_url( LOGIN_PAGE . '?logout=1', $redirect_protocol ) );
    }

    // --------------------------------------------------------------

    /**
     * User recovery form
     */
    public function recover()
    {
        // Load resources
        $this->load->model('examples/examples_model');

        /// If IP or posted email is on hold, display message
        if( $on_hold = $this->authentication->current_hold_status( TRUE ) )
        {
            $view_data['disabled'] = 1;
        }
        else
        {
            // If the form post looks good
            if( $this->tokens->match && $this->input->post('email') )
            {
                if( $user_data = $this->examples_model->get_recovery_data( $this->input->post('email') ) )
                {
                    // Check if user is banned
                    if( $user_data->banned == '1' )
                    {
                        // Log an error if banned
                        $this->authentication->log_error( $this->input->post('email', TRUE ) );

                        // Show special message for banned user
                        $view_data['banned'] = 1;
                    }
                    else
                    {
                        /**
                         * Use the authentication libraries salt generator for a random string
                         * that will be hashed and stored as the password recovery key.
                         * Method is called 4 times for a 88 character string, and then
                         * trimmed to 72 characters
                         */
                        $recovery_code = substr( $this->authentication->random_salt() 
                            . $this->authentication->random_salt() 
                            . $this->authentication->random_salt() 
                            . $this->authentication->random_salt(), 0, 72 );

                        // Update user record with recovery code and time
                        $this->examples_model->update_user_raw_data(
                            $user_data->user_id,
                            [
                                'passwd_recovery_code' => $this->authentication->hash_passwd($recovery_code),
                                'passwd_recovery_date' => date('Y-m-d H:i:s')
                            ]
                        );

                        // Set the link protocol
                        $link_protocol = USE_SSL ? 'https' : NULL;

                        // Set URI of link
                        $link_uri = 'examples/recovery_verification/' . $user_data->user_id . '/' . $recovery_code;

                        $view_data['special_link'] = anchor( 
                            site_url( $link_uri, $link_protocol ), 
                            site_url( $link_uri, $link_protocol ), 
                            'target ="_blank"' 
                        );

                        $view_data['confirmation'] = 1;
                    }
                }

                // There was no match, log an error, and display a message
                else
                {
                    // Log the error
                    $this->authentication->log_error( $this->input->post('email', TRUE ) );

                    $view_data['no_match'] = 1;
                }
            }
        }

        echo $this->load->view('examples/page_header', '', TRUE);

        echo $this->load->view('examples/recover_form', ( isset( $view_data ) ) ? $view_data : '', TRUE );

        echo $this->load->view('examples/page_footer', '', TRUE);
    }

    // --------------------------------------------------------------

    /**
     * Verification of a user by email for recovery
     * 
     * @param  int     the user ID
     * @param  string  the passwd recovery code
     */
    public function recovery_verification( $user_id = '', $recovery_code = '' )
    {
        /// If IP is on hold, display message
        if( $on_hold = $this->authentication->current_hold_status( TRUE ) )
        {
            $view_data['disabled'] = 1;
        }
        else
        {
            // Load resources
            $this->load->model('examples/examples_model');

            if( 
                /**
                 * Make sure that $user_id is a number and less 
                 * than or equal to 10 characters long
                 */
                is_numeric( $user_id ) && strlen( $user_id ) <= 10 &&

                /**
                 * Make sure that $recovery code is exactly 72 characters long
                 */
                strlen( $recovery_code ) == 72 &&

                /**
                 * Try to get a hashed password recovery 
                 * code and user salt for the user.
                 */
                $recovery_data = $this->examples_model->get_recovery_verification_data( $user_id ) )
            {
                /**
                 * Check that the recovery code from the 
                 * email matches the hashed recovery code.
                 */
                if( $recovery_data->passwd_recovery_code == $this->authentication->check_passwd( $recovery_data->passwd_recovery_code, $recovery_code ) )
                {
                    $view_data['user_id']       = $user_id;
                    $view_data['username']     = $recovery_data->username;
                    $view_data['recovery_code'] = $recovery_data->passwd_recovery_code;
                }

                // Link is bad so show message
                else
                {
                    $view_data['recovery_error'] = 1;

                    // Log an error
                    $this->authentication->log_error('');
                }
            }

            // Link is bad so show message
            else
            {
                $view_data['recovery_error'] = 1;

                // Log an error
                $this->authentication->log_error('');
            }

            /**
             * If form submission is attempting to change password 
             */
            if( $this->tokens->match )
            {
                $this->examples_model->recovery_password_change();
            }
        }

        echo $this->load->view('examples/page_header', '', TRUE);

        echo $this->load->view( 'examples/choose_password_form', $view_data, TRUE );

        echo $this->load->view('examples/page_footer', '', TRUE);
    }

    // --------------------------------------------------------------

    /**
     * Attempt to login via AJAX
     */
    public function ajax_login()
    {
        $this->is_logged_in();

        $this->tokens->name = 'login_token';

        $data['javascripts'] = [
            'https://code.jquery.com/jquery-1.12.0.min.js'
        ];

        if( $this->authentication->on_hold === TRUE )
        {
            $data['on_hold_message'] = 1;
        }

        // This check for on hold is for normal login attempts
        else if( $on_hold = $this->authentication->current_hold_status() )
        {
            $data['on_hold_message'] = 1;
        }

        $data['final_head'] = "<script>
            $(document).ready(function(){
                $(document).on( 'submit', 'form', function(e){
                    $.ajax({
                        type: 'post',
                        cache: false,
                        url: '/examples/ajax_attempt_login',
                        data: {
                            'login_string': $('#login_string').val(),
                            'login_pass': $('#login_pass').val(),
                            'login_token': $('[name=\"login_token\"]').val()
                        },
                        dataType: 'json',
                        success: function(response){
                            $('[name=\"login_token\"]').val( response.token );
                            console.log(response);
                            if(response.status == 1){
                                $('form').replaceWith('<p>You are now logged in.</p>');
                                $('#login-link').attr('href','/examples/logout').text('Logout');
                                $('#ajax-login-link').parent().hide();
                            }else if(response.status == 0 && response.on_hold){
                                $('form').hide();
                                $('#on-hold-message').show();
                                alert('You have exceeded the maximum number of login attempts.');
                            }else if(response.status == 0 && response.count){
                                alert('Failed login attempt ' + response.count + ' of ' + $('#max_allowed_attempts').val());
                            }
                        }
                    });
                    return false;
                });
            });
        </script>";

        $html = $this->load->view('examples/page_header', $data, TRUE);
        $html .= $this->load->view('examples/ajax_login_form', $data, TRUE);
        $html .= $this->load->view('examples/page_footer', '', TRUE);

        echo $html;
    }

    // --------------------------------------------------------------

    /**
     * Test for login via ajax
     */
    public function ajax_attempt_login()
    {
        if( $this->input->is_ajax_request() )
        {
            // Allow this page to be an accepted login page
            $this->config->set_item('allowed_pages_for_login', ['examples/ajax_attempt_login'] );

            // Make sure we aren't redirecting after a successful login
            $this->authentication->redirect_after_login = FALSE;

            // Do the login attempt
            $this->auth_data = $this->authentication->user_status( 0 );

            // Set user variables if successful login
            if( $this->auth_data )
                $this->_set_user_variables();

            // Call the post auth hook
            $this->post_auth_hook();

            // Login attempt was successful
            if( $this->auth_data )
            {
                echo json_encode([
                    'status'   => 1,
                    'user_id'  => $this->auth_user_id,
                    'username' => $this->auth_username,
                    'level'    => $this->auth_level,
                    'role'     => $this->auth_role,
                    'email'    => $this->auth_email
                ]);
            }

            // Login attempt not successful
            else
            {
                $this->tokens->name = 'login_token';

                $on_hold = ( 
                    $this->authentication->on_hold === TRUE OR 
                    $this->authentication->current_hold_status()
                )
                ? 1 : 0;

                echo json_encode([
                    'status'  => 0,
                    'count'   => $this->authentication->login_errors_count,
                    'on_hold' => $on_hold, 
                    'token'   => $this->tokens->token()
                ]);
            }
        }

        // Show 404 if not AJAX
        else
        {
            show_404();
        }
    }
    
    // -----------------------------------------------------------------------

    /**
     * If you are using some other way to authenticate a created user, 
     * such as Facebook, Twitter, etc., you will simply call the user's 
     * record from the database, and pass it to the maintain_state method.
     *
     * So, you must know either the user's username or email address to 
     * log them in.
     *
     * How you would safely implement this in your application is your choice.
     * Please keep in mind that such functionality bypasses all of the 
     * checks that Community Auth does during a normal login.
     */
    public function social_login()
    {
        // Add the username or email address of the user you want logged in:
        $username_or_email_address = '';

        if( ! empty( $username_or_email_address ) )
        {
            $auth_model = $this->authentication->auth_model;

            // Get normal authentication data using username or email address
            if( $auth_data = $this->{$auth_model}->get_auth_data( $username_or_email_address ) )
            {
                /**
                 * If redirect param exists, user redirected there.
                 * This is entirely optional, and can be removed if 
                 * no redirect is desired.
                 */
                $this->authentication->redirect_after_login();

                // Set auth related session / cookies
                $this->authentication->maintain_state( $auth_data );
            }
        }
        else
        {
            echo 'Example requires that you set a username or email address.';
        }
    }
    
    // -----------------------------------------------------------------------
}

/* End of file Examples.php */
/* Location: /community_auth/controllers/Examples.php */
