<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Session extends CI_Session {

	public $pre_regenerated_session_id = NULL;
	public $regenerated_session_id     = NULL;

	public function __construct(array $params = array())
	{
		parent::__construct($params);
	}

	// ------------------------------------------------------------------------

	/**
	 * Configuration
	 *
	 * Handle input parameters and configuration defaults
	 *
	 * @param	array	&$params	Input parameters
	 * @return	void
	 */
	protected function _configure(&$params)
	{
		$expiration = config_item('sess_expiration');

		if (isset($params['cookie_lifetime']))
		{
			$params['cookie_lifetime'] = (int) $params['cookie_lifetime'];
		}
		else
		{
			$params['cookie_lifetime'] = ( ! isset($expiration) && config_item('sess_expire_on_close'))
				? 0 : (int) $expiration;
		}

		// Begin modification for remember me
		$CI =& get_instance();
		if( $CI->config->item('allow_remember_me') )
		{
			$params['cookie_lifetime'] = $CI->config->item('remember_me_expiration');
		}
		// End modification for remember me

		isset($params['cookie_name']) OR $params['cookie_name'] = config_item('sess_cookie_name');
		if (empty($params['cookie_name']))
		{
			$params['cookie_name'] = ini_get('session.name');
		}
		else
		{
			ini_set('session.name', $params['cookie_name']);
		}

		isset($params['cookie_path']) OR $params['cookie_path'] = config_item('cookie_path');
		isset($params['cookie_domain']) OR $params['cookie_domain'] = config_item('cookie_domain');
		isset($params['cookie_secure']) OR $params['cookie_secure'] = (bool) config_item('cookie_secure');

		session_set_cookie_params(
			$params['cookie_lifetime'],
			$params['cookie_path'],
			$params['cookie_domain'],
			$params['cookie_secure'],
			TRUE // HttpOnly; Yes, this is intentional and not configurable for security reasons
		);

		if (empty($expiration))
		{
			$params['expiration'] = (int) ini_get('session.gc_maxlifetime');
		}
		else
		{
			$params['expiration'] = (int) $expiration;
			ini_set('session.gc_maxlifetime', $expiration);
		}

		$params['match_ip'] = (bool) (isset($params['match_ip']) ? $params['match_ip'] : config_item('sess_match_ip'));

		isset($params['save_path']) OR $params['save_path'] = config_item('sess_save_path');

		$this->_config = $params;

		// Security is king
		ini_set('session.use_trans_sid', 0);
		ini_set('session.use_strict_mode', 1);
		ini_set('session.use_cookies', 1);
		ini_set('session.use_only_cookies', 1);
		ini_set('session.hash_function', 1);
		ini_set('session.hash_bits_per_character', 4);
	}

	// ------------------------------------------------------------------------

	/**
	 * Session regenerate
	 *
	 * Legacy CI_Session compatibility method
	 *
	 * @param	bool	$destroy	Destroy old session data flag
	 * @return	void
	 */
	public function sess_regenerate($destroy = FALSE)
	{
		$this->pre_regenerated_session_id = $this->session_id;

		$_SESSION['__ci_last_regenerate'] = time();
		session_regenerate_id($destroy);

		$this->regenerated_session_id = $this->session_id;

		return $this->session_id;
	}

	// ------------------------------------------------------------------------
}
