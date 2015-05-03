<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Documentation Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Documentation extends MY_Controller {

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();

		/**
		 * If session is not in a secure cookie, we can still test for logged in user 
		 * via the is_logged_in() method, and the variables it sets in MY_Controller. 
		 * If session is in a secure cookie, then we would test for something in the 
		 * http user cookie. The difference between these cookies is that the secure 
		 * session cookie offers better overall protection. The http user cookie should 
		 * never be used for authentication purposes. Community Auth only uses this 
		 * cookie to show the logout link, which is not sensitive.
		 */
		$this->is_logged_in();
	}

	// --------------------------------------------------------------

	/**
	 * Display the documentation index
	 */
	public function index()
	{
		$data = array(
			'title' => 'Community Auth - Documentation Index',
			'description' => 'Learn how easy it is to use Community Auth, an open source authentication application for CodeIgniter ' . CI_VERSION,
			'content' => $this->load->view( 'documentation/index', '', TRUE )
		);

		$this->load->view( $this->template, $data );
	}

	// --------------------------------------------------------------

	/**
	 * Display the documentation of installation
	 */
	public function installation()
	{
		$data = array(
			'title' => 'Community Auth - Documentation of Installation',
			'content' => $this->load->view( 'documentation/installation', '', TRUE ),
			'dynamic_extras' => '
				$("a[rel*=external]").click( function(){
					window.open(this.href);
					return false;
				});
			'
		);

		$this->load->view( $this->template, $data );
	}

	// --------------------------------------------------------------

	/**
	 * Display the documentation of configuration
	 */
	public function configuration()
	{
		$data = array(
			'title' => 'Community Auth - Documentation of Configuration',
			'content' => $this->load->view( 'documentation/configuration', '', TRUE ),
			'dynamic_extras' => '
				$("a[rel*=external]").click( function(){
					window.open(this.href);
					return false;
				});
			'
		);

		$this->load->view( $this->template, $data );
	}

	// --------------------------------------------------------------

	/**
	 * Display the documentation of admin creation
	 */
	public function creating_an_admin()
	{
		show_error( 
			'The page you are trying to access has been removed from this website.',
			410
		);
	}

	// --------------------------------------------------------------

	/**
	 * Display the documentation of usage
	 */
	public function usage()
	{
		$data = array(
			'title' => 'Community Auth - Documentation of Usage',
			'style_sheets' => array(
				'css/shCoreRDark.css' => 'screen',
				'css/shThemeRDark.css' => 'screen'
			),
			'javascripts' => array(
				'js/shCore.js',
				'js/shBrushPhp.js'
			),
			'dynamic_extras' => '
				SyntaxHighlighter.all();

				$("a[rel*=external]").click( function(){
					window.open(this.href);
					return false;
				});
			',
			'content' => $this->load->view( 'documentation/usage', '', TRUE )
		);

		$this->load->view( $this->template, $data );
	}

	// --------------------------------------------------------------

	/**
	 * Display the documentation for login debugging
	 */
	public function login_debugging()
	{
		$data = array(
			'title' => 'Community Auth - Login Debugging',
			'style_sheets' => array(
				'css/shCoreRDark.css' => 'screen',
				'css/shThemeRDark.css' => 'screen'
			),
			'javascripts' => array(
				'js/shCore.js',
				'js/shBrushPhp.js'
			),
			'dynamic_extras' => '
				SyntaxHighlighter.all();

				$("a[rel*=external]").click( function(){
					window.open(this.href);
					return false;
				});
			',
			'content' => $this->load->view( 'documentation/login_debugging', '', TRUE )
		);

		$this->load->view( $this->template, $data );
	}

	// --------------------------------------------------------------

	/**
	 * Instruction on adding a role to a stock installation of Community Auth
	 */
	public function add_a_role()
	{
		$data = array(
			'title' => 'Community Auth - Adding a Role',
			'style_sheets' => array(
				'css/shCoreRDark.css' => 'screen',
				'css/shThemeRDark.css' => 'screen'
			),
			'javascripts' => array(
				'js/shCore.js',
				'js/shBrushPhp.js'
			),
			'dynamic_extras' => '
				SyntaxHighlighter.all();

				$("a[rel*=external]").click( function(){
					window.open(this.href);
					return false;
				});
			',
			'content' => $this->load->view( 'documentation/add_a_role', '', TRUE )
		);

		$this->template = 'templates/floating_template';

		$this->load->view( $this->template, $data );
	}

	// --------------------------------------------------------------
}

/* End of file documentation.php */
/* Location: ./application/controllers/documentation.php */