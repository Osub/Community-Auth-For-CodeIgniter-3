<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Auto Populate Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Auto_populate extends MY_Controller {

	private $recursion = 0;
	private $dropdown_data;
	private $selections;
	private $options_output;

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// Load common resources
		$this->load->model( 'auto_populate_model', 'autopop' );
	}

	// --------------------------------------------------------------

	/**
	 * Display the auto population form
	 */
	public function index()
	{
		if( $this->require_min_level(1) )
		{
			// Get the vehicle types
			$view_data['types'] = $this->autopop->get_types();

			if( $this->tokens->match )
			{
				if( $this->input->post('type') )
				{
					$view_data['makes'] = $this->autopop->get_makes_in_type();

					if( $this->input->post('make') )
					{
						$view_data['models'] = $this->autopop->get_models_in_make();

						if( $this->input->post('model') )
						{
							$view_data['colors'] = $this->autopop->get_colors_in_model();
						}
					}
				}
			}

			$data = array(
				'title' =>  WEBSITE_NAME . ' - Auto Population of Form Selects',
				'javascripts' => array(
					'js/auto_populate/auto-populate.js'
				),
				'content' => $this->load->view( 'auto_populate/auto_populate', $view_data, TRUE )
			);

			$this->load->view( $this->template, $data );
		}
	}

	// --------------------------------------------------------------

	/**
	 * This is the method that is called by the ajax request
	 */
	public function process_request( $type )
	{
		if( $this->require_min_level(1) )
		{
			if( $this->input->is_ajax_request() && $this->tokens->match )
			{
				// Load resources
				$this->config->load( 'auto_populate/' . $type );

				// Get config
				$config = config_item( $type );

				// Count the levels
				$levels_count = count( $config['levels'] );

				if( $this->input->post( $config['levels'][0] ) )
				{
					$this->_build_dropdown_data( $config );

					$this->recursion = 0;

					$this->_build_output( $config );
				}

				// If for some reason the level 1 selection is set to the default
				else
				{
					for( $x = 1; $x < $levels_count; $x++ )
					{
						$this->options_output[$config['levels'][$x]] = '<option value="0">' . $config['defaults'][0] . '</option>';
					}
				}

				$this->options_output['status'] = 'success';
				$this->options_output['token'] = $this->tokens->token();
				$this->options_output['ci_csrf_token'] = $this->security->get_csrf_hash();

				echo json_encode( $this->options_output );
			}
		}
	}

	// --------------------------------------------------------------

	/**
	 * This method contacts the model for data and puts it 
	 * all into an array that is used by _build_output().
	 * This method also creates an array for selected options.
	 */
	private function _build_dropdown_data( $config )
	{
		// Count the levels
		$levels_count = count( $config['levels'] );

		if( $this->recursion + 2 <= $levels_count )
		{
			$data_key = $this->recursion + 2;

			// Set default option
			$this->dropdown_data[$data_key]['0'] = '-- Select --';

			// Set the method
			$method = $config['methods'][$this->recursion];

			if( $result = $this->autopop->$method() )
			{
				foreach( $result as $k => $v )
				{
					// Build up the array of select data for this set
					if( ! empty( $config['keys'] ) )
					{
						$this->dropdown_data[$data_key][$v[$config['keys'][$this->recursion]]] = $v[$config['levels'][$this->recursion + 1]];
					}
					else
					{
						$this->dropdown_data[$data_key][$v[$config['levels'][$this->recursion + 1]]] = $v[$config['levels'][$this->recursion + 1]];
					}
				}

				// If this isn't the last set
				if( $data_key != $levels_count )
				{
					foreach( $result as $k => $v )
					{
						// Check to see if the posted value for the next set is in this select data
						if( in_array( $this->input->post( $config['levels'][$this->recursion + 1] ), $v ) )
						{
							// Mark as selected
							$this->selections[$data_key] = $this->input->post( $config['levels'][$this->recursion + 1] );

							$this->recursion++;
							$this->_build_dropdown_data( $config );

							break;
						}
					}
				}
			}
		}
	}

	// --------------------------------------------------------------

	/**
	 * This method takes the arrays created by _build_dropdown_data() 
	 * and makes sets of options that are sent back to process_request()
	 */
	private function _build_output( $config )
	{
		// Count the levels
		$levels_count = count( $config['levels'] );
		
		$data_key = $this->recursion + 2;

		$this->options_output[$config['levels'][$this->recursion + 1]] = '';

		if( ! empty( $this->dropdown_data[$data_key] ) )
		{
			foreach( $this->dropdown_data[$data_key] as $k => $v )
			{
				// If this is the selected option
				if( isset( $this->selections[$data_key] ) && $this->selections[$data_key] == $k )
				{
					$this->options_output[$config['levels'][$this->recursion + 1]] .= '<option selected="selected" value="' . $k . '">' . $v . '</option>';
				}
				else
				{
					$this->options_output[$config['levels'][$this->recursion + 1]] .= '<option value="' . $k . '">' . $v . '</option>';
				}
			}
		}
		else
		{
			$this->options_output[$config['levels'][$this->recursion + 1]] .= '<option value="0">' . $config['defaults'][$this->recursion] . '</option>';
		}

		$this->recursion++;

		if( $this->recursion + 2 <= $levels_count )
		{
			$this->_build_output( $config );
		}
	}

	// --------------------------------------------------------------
}

/* End of file auto_populate.php */
/* Location: ./application/controllers/auto_populate.php */