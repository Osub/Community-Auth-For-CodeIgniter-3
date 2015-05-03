<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Static Pages Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 *
 * This controller serves to clean up some controllers that were just used for 
 * simple static content where there was very little logic, calculations, or 
 * much of anything worth having a whole controller for.
 */

class Static_pages extends MY_Controller {

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// Defeat duplicate content
		if( $this->uri->segment(1) == 'static_pages' )
		{
			show_404();
		}

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
	 * Display the home page
	 */
	public function index()
	{
		$data = array(
			'content' => $this->load->view( 'static_pages/home', '', TRUE ),
			'dynamic_extras' => '
				$("a[rel*=external]").click( function(){
					window.open(this.href);
					return false;
				});
			'
		);

		/**
		 * When you install Community Auth on your own domain, you can
		 * merge these array elements back into the $data array on line 
		 * 34, and put in your own title, keywords, and description, 
		 * as I'm sure you are not going to want the ones provided.
		 */
		if( WEBSITE_NAME == 'Community Auth' )
		{
			$data['title'] = 'Community Auth - Open Source CodeIgniter Authentication - CodeIgniter Project Foundation - CodeIgniter Example Application';
			$data['keywords'] = 'codeigniter,authentication,auth,login,\'open source\',example';
			$data['description'] = 'Community Auth is an open source user authentication application for CodeIgniter. More than just an authentication library, Community Auth is distributed with example controllers, models, views, and should be considered a project foundation.';
		}

		$this->load->view( $this->template, $data );
	}

	// --------------------------------------------------------------

	/**
	 * Display the screenshots
	 */
	public function screenshots()
	{
		$data = array(
			'title' => WEBSITE_NAME . ' - Screen Shots',
			'description' => 'Not sure Community Auth is right for you? See some screenshots of the areas in Community Auth that require login.',
			'content' => $this->load->view( 'static_pages/screenshots', '', TRUE )
		);

		$this->load->view( $this->template, $data );
	}

	// --------------------------------------------------------------

	/**
	 * Display the privacy page
	 */
	public function privacy()
	{
		$data = array(
			'title' => WEBSITE_NAME . ' - Privacy Policy',
			'keywords' => 'codeigniter,authentication,open source',
			'content' => $this->load->view( 'static_pages/privacy', '', TRUE ),
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
	 * Display the license
	 */
	public function license()
	{
		$view_data['license'] = nl2br( file_get_contents('licenses/CommunityAuth/license.txt') );

		$data = array(
			'title' => 'Community Auth - Licensed Under BSD',
			'content' => $this->load->view( 'static_pages/license', $view_data, TRUE )
		);

		$this->load->view( $this->template, $data );
	}

	// --------------------------------------------------------------

	/**
	 * i18n through subdomains test
	 */
	public function language_test()
	{
		$this->lang->load( 'subdomain_test', LANG );

		$data = array(
			'title' => $this->lang->line('title'),
			'keywords' => $this->lang->line('keywords'),
			'content' => $this->load->view( 'static_pages/language_test', '', TRUE )
		);

		$this->load->view( $this->template, $data );
	}

	// --------------------------------------------------------------
	
	/**
	 * robots.txt generation for development environment.
	 * This is only here so to try to prevent an unmodified 
	 * installation of Community Auth from appearing to be 
	 * duplicate content by a search engine.
	 */
	public function robots_txt()
	{
		if( ENVIRONMENT == 'development' )
		{
			header("Content-Type: text/plain");
			echo "User-agent: *\nDisallow: /\nNoindex: /\n";
		}
		else
		{
			show_404();
		}
	}
	
	// --------------------------------------------------------------
}

/* End of file static_pages.php */
/* Location: ./application/controllers/static_pages.php */