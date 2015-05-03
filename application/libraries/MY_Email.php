<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - MY_Email
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
 
class MY_Email extends CI_Email {

	/**
	 * Send an email to a single recipient by calling a single function 
	 *
	 * @param  array   an array of configuration options for sending the email.
	 */
	public function quick_email( $params = array() )
	{
		global $CI;

		// If email config not supplied for sender
		if( ! isset( $params['from_name'] ) )
		{
			// Use the default email config
			$defaults = config_item('default_email_config');

			$params = array_merge( $params, $defaults );
		}

		// Is the FROM NAME a config set ?
		else
		{
			$config_set = config_item( $params['from_name'] );

			if( $config_set )
			{
				$params = array_merge( $params, $config_set );
			}
		}

		// Make all email config available in email template
		foreach( $params as $k => $v )
		{
			$params['template_data'][$k] = $v;
		}

		$template_data['content'] = $CI->load->view( $params['email_template'], ( isset( $params['template_data'] ) ) ? $params['template_data'] : '', TRUE );

		$built_message = $CI->load->view( 'email_templates/email-boilerplate.php', $template_data, TRUE );

		// Send email if not development environment
		if( ENVIRONMENT != 'development' )
		{
			// Mail type
			$this->mailtype = ( isset( $params['mailtype'] ) ) ? $params['mailtype'] : 'html';

			// Protocol
			$this->protocol = ( isset( $params['protocol'] ) ) ? $params['protocol'] : 'mail';

			// SMTP SETTINGS
			$this->_smtp_auth   = ( $params['protocol'] = 'smtp' ) ? TRUE : FALSE;
			$this->smtp_host    = ( isset( $params['smtp_host'] ) ) ? $params['smtp_host'] : '';
			$this->smtp_user    = ( isset( $params['smtp_user'] ) ) ? $params['smtp_user'] : '';
			$this->smtp_pass    = ( isset( $params['smtp_pass'] ) ) ? $params['smtp_pass'] : '';
			$this->smtp_port    = ( isset( $params['smtp_port'] ) ) ? $params['smtp_port'] : '';
			$this->smtp_timeout = ( isset( $params['smtp_timeout'] ) ) ? $params['smtp_timeout'] : '';

			// Reply To
			if( isset( $params['reply_to_email'], $params['reply_to_name'] ) )
				$this->reply_to( $params['reply_to_email'], $params['reply_to_name'] );

			// From
			$this->from( $params['from_email'] , $params['from_name'] );

			// To
			$this->to( $params['to'] );

			// Subject
			$this->subject( $params['subject'] );

			// Message
			$this->message( $built_message );

			$this->send();
		}

		// Log email if development environment
		else
		{
			$CI->load->helper('file');

			write_file( APPPATH . 'logs/email/' . microtime(TRUE) . '.html', $built_message );
		}

		// Reset for second email
		$this->clear();
	}

	// --------------------------------------------------------------

}

/* End of file MY_Email.php */
/* Location: ./application/libraries/MY_Email.php */