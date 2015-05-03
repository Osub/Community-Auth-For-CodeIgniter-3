<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Custom Uploader Controls View
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

<h1>Custom Uploader</h1>
<div id="img-upload-specs">
	<div id="activity-indicator">
		<img id="uploader-activity" src="img/network_activity.gif" />
	</div>
	<ul class="std-list">
		<li>Allowed Image Types: <?php echo $file_types; ?></li>
		<li>Max Image File Size: <?php echo $uploader_settings['max_size']; ?> kb</li>
		<li>Max Image Width: <?php echo $uploader_settings['max_width']; ?> px</li>
		<li>Max Image Height: <?php echo $uploader_settings['max_height']; ?> px</li>
	</ul>
</div>
<div id="image-manager">
	<div id="upload-div">
		<p>
			<?php echo form_open(); ?>
				<input type="button" id="upload-button" value="Upload Image" />
				<input type="hidden" id="user_id" value="<?php echo $auth_user_id; ?>" />
				<input type="hidden" id="ci_csrf_token_name" value="<?php echo config_item('csrf_token_name'); ?>" />
				<input type="hidden" id="file_types" value="<?php echo $file_types; ?>" />
				<input type="hidden" id="allowed_types" value="<?php echo $uploader_settings['allowed_types']; ?>" />
				<input type="hidden" id="update_image_order_url" value="<?php echo secure_site_url('custom_uploader/update_image_order'); ?>" />
				<input type="hidden" id="delete_image_url" value="<?php echo secure_site_url('custom_uploader/delete_image'); ?>" />
				<input type="hidden" id="upload_image_url" value="<?php echo secure_site_url('uploads_manager/bridge_filesystem/custom_uploader'); ?>" />
			</form>
		</p>
		<div id="status-bar"></div>
	</div>
	<div id="image-list">
		<?php
			// If there are images
			if( ! empty( $images->images_data ) )
			{
				// Unserialize the images
				$images = unserialize( $images->images_data );

				// If the unserialized data is not empty
				if( ! empty( $images ) )
				{
					// Start the image list
					echo '<ul id="image-ul">';

					// For each image, create list item
					foreach( $images as $image )
					{
						echo '<li class="sortable-element">';

						$attrs = array(
							'src' => $image,
							'width' => '100',
							'height' => '75'
						);

						echo img( $attrs );

						echo '</li>';
					}

					// End image list
					echo '</ul>';
				}

				// Serialized data was empty
				else
				{
					echo '<p id="no-images">NO IMAGES TO LIST</p>';
				}
			}

			// If there are no images
			else
			{
				echo '<p id="no-images">NO IMAGES TO LIST</p>';
			}
		?>
	</div>
	<div id="trash_can" class="unhovered">
		&nbsp;
	</div>
</div>

<?php

/* End of uploader_controls.php */
/* Location: ./application/views/uploader_controls.php */