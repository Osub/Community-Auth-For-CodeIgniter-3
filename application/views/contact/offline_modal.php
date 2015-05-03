<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Contact Form's Offline Mode Modal View
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.2
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
?>
<div id="mask_overlay"></div>
<div id="modal_dialog">
	<div id="modal_content">
		<div id="adv-modal-title-bar">
			<h5 id="adv-modal-title-bar-header">Contact Form Offline</h5>
			<div id="adv-modal-red-x">
				<img id="red-x-close" src="img/red-x.gif" />
			</div>
		</div>
		<div class="adv-modal-content">
			<div id="form-feedback">
				<div class="feedback reminder">
					<p class="feedback_header">
						The message form is temporarily offline, and not functional.
					</p>
				</div>
			</div>
			<div class="modal-row">
				If you need help with Community Auth, have comments, or would like to make a suggestion, please either submit a new issue at the Community Auth repository on <a href="https://bitbucket.org/skunkbad/community-auth-git-version/issues/new">Bitbucket</a>, or post to the <a href="http://forum.codeigniter.com">CodeIgniter forum</a>.
			</div>
			<form class="std-form">
				<div id="buttons-row">
					<input id="close-button" type="button" class="form_button" value="Close" />
				</div>
			</form>
		</div>
	</div>
</div>
<?php

/* End of file offline_modal.php */
/* Location: /application/views/contact/offline_modal.php */