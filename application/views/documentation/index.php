<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Community Auth - Documentation Index View
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

<h1>Documentation Index</h1>
<ul class="std-list">
	<li><?php echo anchor('documentation/configuration', 'Configuration'); ?></li>
	<li><?php echo anchor('documentation/installation', 'Installation'); ?></li>
	<li><?php echo anchor('documentation/usage', 'Usage'); ?></li>
</ul>
<h2>Documentation Summary</h2>
<p>
	Community Auth's documentation consists of three simple steps, and assumes that you have Community Auth installed in CodeIgniter <?php echo CI_VERSION; ?>.
</p>
<p>
	The links to each of the three documentation steps are provided at the top of each documentation page. Please start with <?php echo anchor('documentation/configuration', 'configuration'); ?> and work your way down. You'll have Community Auth up and running in no time at all. Once you are done, learn how to easily <?php echo anchor('documentation/add_a_role', 'add a new role'); ?>.
</p>
<h3>Why are there two versions of Community Auth?</h3>
<p>
	There are two version of Community Auth. One called <i>"complete"</i>, which is Community Auth pre-installed in CodeIgniter <?php echo CI_VERSION; ?>, and the other is called the <i>"isolated"</i> version, which is just the files that are necessary to merge with your existing application. I've made two versions because people want both. Personally I prefer to use the complete version, because I would never start a new project without this code foundation.
</p>
<p>
	If you've downloaded the complete version, then you're ready to start with <?php echo anchor('documentation/configuration', 'configuration'); ?>. If you're working with the isolated version, no special documentation or guidance is available for merging the files with your existing application. I'm going to assume that developers who download the isolated version have some experience using CodeIgniter, but still recommend taking a look at the installation, configuration, and usage documentation.
</p>
<h3>A quick note about installation into a sub-directory:</h3>
<p>
	Regardless of the version you choose to install, the provided <b>.htaccess</b> file assumes you will either be using virtual hosts or installing Community Auth in the public root. Should you install Community Auth in a sub-directory, the RewriteBase in the .htaccess file needs to be changed accordingly. For instance, if you install Community Auth into a sub-directory named "authentication", then your RewriteBase should be changed from "/" to "/authentication/".
</p>

<?php

/* End of file index.php */
/* Location: /application/views/documentation/index.php */