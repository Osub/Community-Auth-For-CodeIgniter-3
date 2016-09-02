<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Key Creator Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2016, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Key_creator extends CI_Controller{
	
	/**
	 * The key creator is only available if there is no current encryption key.
	 * If for some reason you'd like to use this controller and you already
	 * have an encryption key set in config/config, comment out lines 27 and 28.
	 */
	public function __construct()
	{
		parent::__construct();

		if( ! empty( config_item('encryption_key') ) )
			show_404();
	}

	// -----------------------------------------------------------------------

	/**
	 * Choose an encryption cipher
	 */
	public function index()
	{
		echo '<h1>Encryption Key Creator</h1>
		<p>Community Auth uses Blowfish for password hashing, but only for passwords created where PHP < v5.5.</p>
		<p>Community Auth also uses Blowfish for session encryption.</p>
		<p>More Information: <a href="https://www.codeigniter.com/user_guide/libraries/encryption.html" target="_blank">CodeIgniter Documentation for Encryption Library</a></p>
		<h2>Choose an Encryption Cipher:</h2>
		<ul>
			<li><a href="/key_creator/create/16?cipher=' . urlencode('AES-128 / Rijndael-128') . '">AES-128 / Rijndael-128 (CodeIgniter Default)</a></li>
			<li><a href="/key_creator/create/24?cipher=' . urlencode('AES-192') . '">AES-192</a></li>
			<li><a href="/key_creator/create/32?cipher=' . urlencode('AES-256') . '">AES-256</a></li>
			<li><a href="/key_creator/create/7?cipher=' . urlencode('DES') . '">DES</a></li>
			<li><a href="/key_creator/create/7?cipher=' . urlencode('TripleDES (56 bit)') . '">TripleDES (56 bit)</a></li>
			<li><a href="/key_creator/create/14?cipher=' . urlencode('TripleDES (112 bit)') . '">TripleDES (112 bit)</a></li>
			<li><a href="/key_creator/create/21?cipher=' . urlencode('TripleDES (168 bit)') . '">TripleDES (168 bit)</a></li>
			<li><a href="/key_creator/create/16?cipher=' . urlencode('Blowfish (128 bit)') . '">Blowfish (128 bit)</a></li>
			<li><a href="/key_creator/create/32?cipher=' . urlencode('Blowfish (256 bit)') . '">Blowfish (256 bit)</a></li>
			<li><a href="/key_creator/create/48?cipher=' . urlencode('Blowfish (384 bit)') . '">Blowfish (384 bit)</a></li>
			<li><a href="/key_creator/create/56?cipher=' . urlencode('Blowfish (448 bit)') . '">Blowfish (448 bit)</a></li>
			<li><a href="/key_creator/create/11?cipher=' . urlencode('CAST5 / CAST-128 (88 bit)') . '">CAST5 / CAST-128 (88 bit)</a></li>
			<li><a href="/key_creator/create/16?cipher=' . urlencode('CAST5 / CAST-128 (128 bit)') . '">CAST5 / CAST-128 (128 bit)</a></li>
			<li><a href="/key_creator/create/5?cipher=' . urlencode('RC4 / ARCFour (40 bit)') . '">RC4 / ARCFour (40 bit)</a></li>
			<li><a href="/key_creator/create/256?cipher=' . urlencode('RC4 / ARCFour (2048 bit)') . '">RC4 / ARCFour (2048 bit)</a></li>
		</ul>';
	}
	
	// -----------------------------------------------------------------------

	/**
	 * Create an encryption key for config/config
	 */
	public function create( $length = 16 )
	{
		$this->load->library('encryption');

		$cipher = $this->input->get('cipher')
			? urldecode( $this->input->get('cipher') )
			: $length . ' byte key';

		$key = bin2hex( $this->encryption->create_key( $length ) );

		echo '// ' . $cipher . '<br /> 
		$config[\'encryption_key\'] = hex2bin(\'' . $key . '\');';
	}
	
	// -----------------------------------------------------------------------
}

/* End of file Key_creator */
/* Location: /community_auth/controllers/Key_creator.php */