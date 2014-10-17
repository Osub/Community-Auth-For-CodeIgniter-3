<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Category Menu View
 *
 * Community Auth is an open source authentication application for CodeIgniter 2.2.0
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2014, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
?>

<h1>Category Menu</h1>
<p>
	It seems a popular question to ask how to make a menu that is created by categories in a database. It isn't really all that hard to do, but it requires a recursive function. Check the category_menu.php controller to see how the menu is built. You should be able to apply your own custom styling and turn the menu into just about anything you want to see.
</p>

<?php

	echo '<div id="category-menu">
			<h3>' . secure_anchor('category', 'Category Menu') . '</h3>
			' . $category_menu . '
		</div>';

?>
<div style="float:right;width:260px;margin:2em 0 0 0;">
	<table class="simple_table" style="width:260px;">
		<caption style="color:#bf1e2e;font-weight:bold;font-size:100%;">Category Data From Database</caption>
		<thead>
			<tr>
				<th>Category ID</th>
				<th>Category Name</th>
				<th>Parent ID</th>
			</tr>
		</thead>
		<tbody>

			<?php
				$i = 0;

				foreach( $category_data as $row )
				{
					$class = ( $i % 2 ) ? ' class="odd"' : '';

					echo '
						<tr' . $class . '>
							<td>' . $row['category_id'] . '</td>
							<td>' . $row['name'] . '</td>
							<td>' . $row['parent_id'] . '</td>
						</tr>
					';

					$i++;
				}
			?>

		</tbody>
	</table>
</div>

<?php

/* End of file category_menu.php */
/* Location: /application/views/category_menu/category_menu.php */