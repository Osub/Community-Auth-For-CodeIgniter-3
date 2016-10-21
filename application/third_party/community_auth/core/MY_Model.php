<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - MY Model
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2016, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 *
 * I decided it was important to have the ACL related 
 * methods here because then I can access them from any model.
 * This has been especially useful in websites I work on.
 */

class MY_Model extends CI_Model
{
	/**
	 * ACL for a logged in user
	 * @var mixed
	 */
	public $acl = NULL;

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	// -----------------------------------------------------------------------

	/**
	 * Get all of the ACL records for a specific user
	 */
	public function acl_query( $user_id, $called_during_auth = FALSE )
	{
		// ACL table query
		$query = $this->db->select('b.action_id, b.action_code, c.category_code')
			->from( config_item('acl_table') . ' a' )
			->join( config_item('acl_actions_table') . ' b', 'a.action_id = b.action_id' )
			->join( config_item('acl_categories_table') . ' c', 'b.category_id = c.category_id' )
			->where( 'a.user_id', $user_id )
			->get();

		/**
		 * ACL becomes an array, even if there were no results.
		 * It is this change that indicates that the query was 
		 * actually performed.
		 */
		$acl = [];

		if( $query->num_rows() > 0 )
		{
			// Add each permission to the ACL array
			foreach( $query->result() as $row )
			{
				// Permission identified by category + "." + action code
				$acl[$row->action_id] = $row->category_code . '.' . $row->action_code;
			}
		}

		if( $called_during_auth OR $user_id == config_item('auth_user_id') )
			$this->acl = $acl;

		return $acl;
	}
	
	// -----------------------------------------------------------------------

	/**
	 * Check if ACL permits user to take action.
	 *
	 * @param  string  the concatenation of ACL category 
	 *                 and action codes, joined by a period.
	 * @return bool
	 */
	public function acl_permits( $str )
	{
		list( $category_code, $action_code ) = explode( '.', $str );

		// We must have a legit category and action to proceed
		if( strlen( $category_code ) < 1 OR strlen( $action_code ) < 1 )
			return FALSE;

		// Get ACL for this user if not already available
		if( is_null( $this->acl ) )
		{
			if( $this->acl = $this->acl_query( config_item('auth_user_id') ) )
			{
				$this->load->vars( ['acl' => $this->acl] );
				$this->config->set_item( 'acl', $this->acl );
			}
		}

		if( 
			// If ACL gives permission for entire category
			in_array( $category_code . '.*', $this->acl ) OR  
			in_array( $category_code . '.all', $this->acl ) OR 

			// If ACL gives permission for specific action
			in_array( $category_code . '.' . $action_code, $this->acl )
		)
		{
			return TRUE;
		}

		return FALSE;
	}
	
	// -----------------------------------------------------------------------

	/**
	 * Check if the logged in user is a certain role or 
	 * in a comma delimited string of roles.
	 *
	 * @param  string  the role to check, or a comma delimited
	 *                 string of roles to check.
	 * @return bool
	 */
	public function is_role( $role = '' )
	{
		$auth_role = config_item('auth_role');

		if( $role != '' && ! empty( $auth_role ) )
		{
			$role_array = explode( ',', $role );

			if( in_array( $auth_role, $role_array ) )
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	// -----------------------------------------------------------------------
}

/* End of file MY_Model.php */
/* Location: /community_auth/core/MY_Model.php */