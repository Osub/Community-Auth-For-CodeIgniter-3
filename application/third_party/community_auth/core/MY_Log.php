<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Log Class Extension
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class MY_Log extends CI_Log {

	/**
	 * If on the production environment and you 
	 * want to be able to use console logging,
	 * then you will want to set to TRUE.
	 */
	protected $always_allow_console_logging = FALSE;

	/**
	 * The CI super object (once available)
	 */
	protected $ci = NULL;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Instead of calling FirePHP directly, it's better to 
	 * create a new logging method, so that we can swap out the custom 
	 * console logging for something else if necessary, such as 
	 * using ChromePHP or something else.
	 *
	 * Note that you can still call fb directly.
	 */
	public function console( $x = NULL )
	{
		// Get an instance of the CI super object, but only once
		if( is_null( $this->ci ) && class_exists('CI_Controller') )
		{
			$this->ci =& get_instance();

			/**
			 * If the production environment, FirePHP is normally disabled.
			 * This is handy because FirePHP debugging code can be left 
			 * within the application with no potential risks.
			 */
			if( 
				ENVIRONMENT == 'production' && 
				$this->ci->load->is_loaded('fb') && 
				! $always_allow_console_logging
			)
			{
				$this->ci->fb->setEnabled( FALSE );
			}
		}


		// As long as the CI super object is loaded, fb is available
		if( ! is_null( $this->ci ) )
		{
			$this->ci->fb->log( $x );
		}
	}
	
	// -----------------------------------------------------------------------

}

/* End of file MY_Log.php */
/* Location: /application/core/MY_Log.php */ 