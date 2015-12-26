<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Key_creator extends CI_Controller{
	
	public function __construct()
	{
		parent::__construct();
	}

	// -----------------------------------------------------------------------

	/**
	 * Alias for create method
	 */
	public function index()
	{
		echo '<ul>
			<li><a href="/key_creator/create/16?cipher=' . urlencode('AES-128 / Rijndael-128') . '">AES-128 / Rijndael-128</a></li>
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

/* End of file  */
/* Location: /community_auth/controllers/Key_creator.php */