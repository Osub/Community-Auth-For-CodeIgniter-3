<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Category Menu Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Category_menu extends MY_Controller {

	/**
	 * HTML for category menu
	 *
	 * @var string
	 * @access private
	 */
	private $menu            = '';

	/**
	 * Restructed category array
	 *
	 * @var array
	 * @access private
	 */
	private $categories      = array();

	// --------------------------------------------------------------

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	// --------------------------------------------------------------

	/**
	 * Display the category menu page
	 */
	public function index()
	{
		// Make sure somebody is logged in
		if( $this->require_min_level(1) )
		{
			// Load resources
			$this->load->model('category_menu_model', 'cat_model');

			// Get all category data from database
			if( $category_data = $this->cat_model->get_all_category_data() )
			{
				// Reformat query results as new array
				foreach( $category_data as $k => $v )
				{
					$this->categories[ $v['parent_id'] ][ $v['category_id'] ] = $v['name'];
				}

				// Initiate menu creation
				$this->_make_category_menu( $this->categories[0], '', 0 );

				// Send menu and category data to view
				$view_data = array(
					'category_menu' => $this->menu,
					'category_data' => $category_data
				);
			}
			else
			{
				$view_data['category_menu'] = 'Error: No Menu.';
			}

			$data = array(
				'title' => WEBSITE_NAME . ' - Category Menu',
				'dynamic_extras' => '$("#category-menu a").click(function(e){e.preventDefault();alert("None of the links in the menu actually point to a real URL.");});',
				'content' => $this->load->view( 'category_menu/category_menu', $view_data, TRUE ),
			);

			$this->load->view( $this->template, $data );
		}
	}

	// --------------------------------------------------------------

	/**
	 * Recursive method takes formatted array of categories and turns it into a menu.
	 *
	 * @param   array   the current categories array to process
	 * @param   string  the path between the top level and the current level (all parents)
	 * @param   int     how many levels deep we are in nested lists
	 */
	private function _make_category_menu( $child, $parents='', $level )
	{
		if( $level > 0 )
		{
			// Start a submenu list:
			$this->menu .= '<ul class="submenu-level-' . $level . '">';
		}

		// Loop through each child
		foreach( $child as $cat_id => $cat_name )
		{
			// Display the top level header and start submenu wrapper
			if( $level === 0 )
			{
				$this->menu .= 
					'<h4>' . secure_anchor( 'category' . strtolower( $parents . '/' . $cat_name ), $cat_name ) . '</h4>
					<div class="submenu-div">
						<div class="submenu-listbox">
				';
			}
			else
			{
				// Start a list item
				$this->menu .= '<li>' . secure_anchor( 'category' . strtolower( $parents . '/' . $cat_name ), $cat_name );
			}

			// Check for children
			if( isset( $this->categories[$cat_id] ) )
			{
				// Add parents to URL if applicable
				$new_parents = $parents . '/' . $cat_name;

				// Do recursion with new level:
				$new_level = $level + 1;

				$this->_make_category_menu( $this->categories[$cat_id], $new_parents, $new_level );
			}

			// Close the submenu wrapper
			if( $level === 0 )
			{
				$this->menu .= '
						</div>
					</div>
				';
			}
			else
			{
				// Complete the list item:
				$this->menu .= '</li>';
			}
		}

		if( $level > 0 )
		{
			// Close a submenu list:
			$this->menu .= '</ul>';
		}
	}

	// --------------------------------------------------------------
}

/* End of file category_menu.php */
/* Location: ./application/controllers/category_menu.php */