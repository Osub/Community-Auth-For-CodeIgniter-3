<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Community Auth - Crons Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2016, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Crons extends MY_Controller
{
    /**
     * This method is purely optional, and would be called via a cron job
     * to perform garbage collection on the auth_sessions table.
     *
     * If you do set up a cron to run this garbage collection, you may 
     * turn off the garbage collection on logout setting in config/authentication.php
     */
    public function auth_sessions_gc()
    {
        $auth_model = $this->authentication->auth_model;

        $this->{$auth_model}->auth_sessions_gc();
    }
    
    // -----------------------------------------------------------------------
}

/* End of file Crons.php */
/* Location: /community_auth/controllers/Crons.php */
