<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Show Pending Registrations View
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

<h1>Pending Registrations</h1>

<?php

if( isset( $admin_mode ) )
{
	echo form_open();

	?>

	<div id="table-wrapper">
		<table id="myTable" class="tablesorter">
			<thead>
				<tr>
					<th></th>
					<th>Date Registered</th>
					<th>Name</th>
					<th>Username</th>
					<th>Email Address</th>
				</tr>
			</thead>
			<tbody>

	<?php

	if( isset( $pending_regs ) )
	{

		foreach( $pending_regs as $reg )
		{
			echo '
				<tr>
					<td>
						<input type="checkbox" name="selected_' . html_escape( $reg->reg_id ) . '" value="' . html_escape( $reg->reg_id ) . '" />
					</td>
					<td>
						' . date( "M j, Y", $reg->reg_time ) . '
					</td>
					<td>' 
						. html_escape( $reg->first_name ) . ' ' . html_escape( $reg->last_name ) .
					'</td>
					<td>' 
						. html_escape( $reg->user_name ) . 
					'</td>
					<td>' 
						. html_escape( $reg->user_email ) . 
					'</td>
				</tr>
			';
		}
	}

	?>

			</tbody>
		</table>
	</div>
	<div id="decision_buttons">
		<input type="submit" name="approve" value="Approve Selected" /><br />
		<input type="submit" name="delete" value="Delete Selected" />
	</div>
</form>

	<?php
}
else
{
	echo $reg_mode_mismatch;
}

/* End of file show_pending.php */
/* Location: /application/views/register/show_pending.php */