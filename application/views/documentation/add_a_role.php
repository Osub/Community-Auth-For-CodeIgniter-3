<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Documentation of Adding a Role
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

<h1>Tutorial: Adding a New Role</h1>
<p>
	Adding a role to Community Auth requires creating and editing some files, and the goal of this tutorial will be to show you how to do it quickly. I am going to walk you through the process of adding a "vendor" role to an untouched installation of Community Auth. Installation itself is not covered in this tutorial, and I'm going to assume you have Community Auth up and running, and that application is working.
</p>
<h2>Add a Vendor Profile Table to the Database</h2>
<p>
	A vendor is a wholesaler or distributor of merchandise. As we create our vendor profile, we will give the vendor a couple of fields that are unique to their role, "business_name" and "business_type". In the example application all profile tables include a profile image, and Community Auth requires that "first_name" and "last_name" fields exist. I'm going to keep this tutorial very simple, so the two unique fields will work nicely.
</p>
<p>
	Take a look at the SQL below. You can run it on your database to create the vendor profile table, or create the table manually.
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false;">
CREATE TABLE IF NOT EXISTS `vendor_profiles` (
  `user_id` int(10) unsigned NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `business_name` varchar(30) NOT NULL,
  `business_type` varchar(30) NOT NULL,
  `profile_image` text,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;</pre>
</div>
<h2>Add the Vendor Profile Table to DB Tables Config</h2>
<p>
	Open up <b>/application/config/db_tables.php</b> and add the following line:
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false; first-line:19;">
$config['vendor_profiles_table'] = 'vendor_profiles';</pre>
</div>
<h2>Add a New Level &amp; Role to Authentication Config</h2>
<p>
	Open <b>/application/config/authentication.php</b>. The second configuration setting is Levels and Roles. Add a new element to the array for our vendor. Use '2' as the key and 'vendor' as the value.
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false; first-line:41;">
$config['levels_and_roles'] = array(
	'1' => 'customer',
	'2' => 'vendor',
	'6' => 'manager',
	'9' => 'admin'
);</pre>
</div>
<h2>User Creation</h2>
<p>
	We need to be able to create new vendors, and that requires the creation of a new view and new form validation file. 
</p>
<h3>Creation Form</h3>
<p>
	The form that is filled out during user creation is a nested view, and all roles (except admin which are created through the Community Auth installer) need one. The views are located at <b>/application/views/administration/create_user/</b>. The filename of the views are built with "create_" + the user role + ".php". Using the customer's create_customer.php so I didn't have to start from scratch, do a "save as" <b>create_vendor.php</b>. The vendor profile doesn't have the address, city, state, and zip fields that the customer profile has, so I'm going to delete those fields and replace them with the vendor's business_name and business_type fields.
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false; first-line:123;">
&lt;div class="form-row">

	&lt;?php
		// BUSINESS NAME LABEL AND INPUT *************
		echo form_label(
			'Business Name',
			'business_name',
			array('class'=>'form_label')
		);

		echo input_requirement('*');

		$input_data = array(
			'name'		=> 'business_name',
			'id'		=> 'business_name',
			'class'		=> 'form_input max_chars',
			'value'		=> set_value('business_name'),
			'maxlength'	=> '30',
		);

		echo form_input($input_data);

	?>

&lt;/div>
&lt;div class="form-row">

	&lt;?php
		// BUSINESS TYPE LABEL AND INPUT *************
		echo form_label(
			'Business Type',
			'business_type',
			array('class'=>'form_label')
		);

		echo input_requirement('*');

		$input_data = array(
			'name'		=> 'business_type',
			'id'		=> 'business_type',
			'class'		=> 'form_input max_chars',
			'value'		=> set_value('business_type'),
			'maxlength'	=> '30',
		);

		echo form_input($input_data);

	?>

&lt;/div></pre>
</div>
<h3>Validation Rules for Creation</h3>
<p>
	Character limiters are provided for client side validation, and are set up as classes for the form elements. For the vendor's business name and business type fields I a used "max_chars" class. Server side validation is where we truly validate the input from the form, and to save time, we're going to borrow code from the customer's validation file.Open up <b>/application/config/form_validation/administration/create_user/create_customer.php</b>, and save it as <b>create_vendor.php</b>. The filename of the user creation validation files are built with "create_" + the user role + ".php", which is the same name as the view.
</p>
<p>
	Make sure the configuration setting in create_vendor.php is named <b>"vendor_creation_rules"</b>.
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false; first-line:14;">
$config['vendor_creation_rules'] = array(</pre>
</div>
<p>
	Delete the rules for address, city, state, and zip. Add rules for the business name and business type.
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false; first-line:40;">
array(
	'field' => 'business_name',
	'label' => 'BUSINESS NAME',
	'rules' => 'trim|required|xss_clean|max_length[30]'
),
array(
	'field' => 'business_type',
	'label' => 'BUSINESS TYPE',
	'rules' => 'trim|required|xss_clean|max_length[30]'
)</pre>
</div>
<p>
	You should now be able to create a vendor.
</p>
<h2>User Update</h2>
<p>
	We need to be able to update the vendor. Forunately, the work we did to be able to create a vendor is very much like what we will need to do to be able to update the vendor.
</p>
<p>
	Open <b>/application/views/administration/update_user/update_customer.php</b> and save as <b>update_vendor.php.</b> Make the changes to form fields that are specific to the vendor. (Remove address, city, state, and zip, then add business name and business type)
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false; first-line:130;">
&lt;div class="form-row">

	&lt;?php
		// BUSINESS NAME LABEL AND INPUT *************
		echo form_label(
			'Business Name',
			'business_name',
			array('class'=>'form_label')
		);

		echo input_requirement('*');

		$input_data = array(
			'name'		=> 'business_name',
			'id'		=> 'business_name',
			'class'		=> 'form_input max_chars',
			'value'		=> set_value(
				'business_name', 
				$user_data->business_name
			),
			'maxlength'	=> '30',
		);
		echo form_input($input_data);

	?>

&lt;/div>
&lt;div class="form-row">

	&lt;?php
		// BUSINESS TYPE LABEL AND INPUT *************
		echo form_label(
			'Business Type',
			'business_type',
			array('class'=>'form_label')
		);

		echo input_requirement('*');

		$input_data = array(
			'name'		=> 'business_type',
			'id'		=> 'business_type',
			'class'		=> 'form_input max_chars',
			'value'		=> set_value(
				'business_type', 
				$user_data->business_type
			),
			'maxlength'	=> '30',
		);
		echo form_input($input_data);

	?>

&lt;/div></pre>
</div>
<p>
	Setting up the form validation for the vendor update is a little tricky, because all profiles currently share the same form validation config file, located at <b>/application/config/form_validation/user/user_update.php</b> In this file you will see where customer, manager and admin have their own validation rules. I'm going to copy the customer's update rules and modify them for the vendor. Remember the vendor doesn't have an address, city, state, or zip, but does have the business_name and business_type. Make sure when you copy the rules that you rename the configuration setting to <b>"vendor_update_rules"</b>. Go down to the bottom of the file and add the lines that merge the self update rules and update user rules with the vendor update rules
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false; first-line:59;">
// VENDOR SPECIFIC UPDATE RULES --------------------------
$config['vendor_update_rules'] = array(
	array(
		'field' => 'user_pass',
		'label' => 'PASSWORD',
		'rules' => 'trim|matches[user_pass_confirm]|external_callbacks[model,formval_callbacks,_check_password_strength,FALSE]'
	),
	array(
		'field' => 'user_pass_confirm',
		'label' => 'CONFIRMED PASSWORD',
		'rules' => 'trim'
	),
	array(
		'field' => 'last_name',
		'label' => 'LAST NAME',
		'rules' => 'trim|required|xss_clean'
	),
	array(
		'field' => 'first_name',
		'label' => 'FIRST NAME',
		'rules' => 'trim|required|xss_clean'
	),
	array(
		'field' => 'business_name',
		'label' => 'BUSINESS NAME',
		'rules' => 'trim|required|xss_clean|max_length[30]'
	),
	array(
		'field' => 'business_type',
		'label' => 'BUSINESS TYPE',
		'rules' => 'trim|required|xss_clean|max_length[30]'
	)
);

// Code below goes near the end of the file

$config['self_update_vendor'] = array_merge( 
	$config['self_update_rules'], 
	$config['vendor_update_rules'] 
);

$config['update_user_vendor'] = array_merge( 
	$config['update_user_rules'], 
	$config['vendor_update_rules'] 
);</pre>
</div>
<p>
	The good news is that when we get to the next section, "self update", the validation is already done.
</p>
<h2>Self Update</h2>
<p>
	There's nothing about making the self update form that you haven't done already. Open up <b>/application/views/user/self_update/self_update_customer.php</b> and save it as <b>self_update_vendor.php</b>. Find the address, city, state, and zip fields and delete them. Add in fields for the business_name and business_type.
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false; first-line:66;">
&lt;div class="form-row">

	&lt;?php
		// BUSINESS NAME LABEL AND INPUT *************
		echo form_label(
			'Business Name',
			'business_name',
			array('class'=>'form_label')
		);

		echo input_requirement('*');

		$input_data = array(
			'name'		=> 'business_name',
			'id'		=> 'business_name',
			'class'		=> 'form_input max_chars',
			'value'		=> set_value(
				'business_name', 
				$user_data->business_name
			),
			'maxlength'	=> '60',
		);

		echo form_input($input_data);

	?>

&lt;/div>
&lt;div class="form-row">

	&lt;?php
		// BUSINESS TYPE LABEL AND INPUT *************
		echo form_label(
			'Business Type',
			'business_type',
			array('class'=>'form_label')
		);

		echo input_requirement('*');

		$input_data = array(
			'name'		=> 'business_type',
			'id'		=> 'business_type',
			'class'		=> 'form_input max_chars',
			'value'		=> set_value(
				'business_type', 
				$user_data->business_type
			),
			'maxlength'	=> '60',
		);

		echo form_input($input_data);

	?>

&lt;/div></pre>
</div>
<h3>Profile Image Upload Configuration</h3>
<p>
	The profile image upload on the "My Profile" page and the Custom Uploader use ajax to upload the image, and authentication is performed on those requests. Unless you remove the image upload functionality for the vendor, you'll need to open <b>/application/config/uploads_manager.php</b> and add vendor to the <b>"authentication_profile_image"</b> and <b>"authentication_custom_uploader"</b> configuration settings.
</p>
<div class="doc_code">
	<pre class="brush: php; toolbar: false; first-line:40;">
$config['authentication_profile_image'] = 'admin,manager,vendor,customer';

// ... space between configuration settings ...

$config['authentication_custom_uploader'] = 'admin,manager,vendor,customer';</pre>
</div>
<p>
	Login as the vendor you created, and you should be able to update yourself.
</p>
<h2>Summary</h2>
<p>
	We ended up modifying 4 files and creating 4 new ones:
</p>
<table class="simple_table">
	<thead>
		<tr>
			<th>Filename</th>
			<th>New / Existing</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>/application/config/db_tables.php</td>
			<td>Existing</td>
		</tr>
		<tr class="odd">
			<td>/application/config/authentication.php</td>
			<td>Existing</td>
		</tr>
		<tr>
			<td>/application/views/administration/create_user/create_vendor.php</td>
			<td>New</td>
		</tr>
		<tr class="odd">
			<td>/application/config/form_validation/administration/create_user/create_vendor.php</td>
			<td>New</td>
		</tr>
		<tr>
			<td>/application/views/administration/update_user/update_vendor.php</td>
			<td>New</td>
		</tr>
		<tr class="odd">
			<td>/application/config/form_validation/user/user_update.php</td>
			<td>Existing</td>
		</tr>
		<tr>
			<td>/application/views/user/self_update/self_update_vendor.php</td>
			<td>New</td>
		</tr>
		<tr class="odd">
			<td>/application/config/uploads_manager.php</td>
			<td>Existing</td>
		</tr>
	</tbody>
</table>
<p>
	When you set up the roles in your application, you will probably have a lot of different fields and validation that needs to be done. Actual time to create each user will probably depend on how complex your profiles are. Having created a new user for Community Auth's example application, I hope you'll feel confident that you can do it again.
</p>

<?php

/* End of file add_a_role.php */
/* Location: /application/views/documentation/add_a_role.php */