<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Database Tables Config
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

// USER RELATED TABLES
$config['user_table']                   = 'users';

// LOGIN ERROR RELATED TABLES
$config['errors_table']                 = 'login_errors';
$config['IP_hold_table']                = 'ips_on_hold';
$config['username_or_email_hold_table'] = 'username_or_email_on_hold';
$config['denied_access_table']          = 'denied_access';

// SESSIONS TABLE
$config['sessions_table']               = 'ci_sessions';

/* End of file db_tables.php */
/* Location: /application/config/db_tables.php */