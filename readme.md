Community Auth For CodeIgniter 3
================================

This version of Community Auth is a user authentication package for CodeIgniter 3.

Installation
------------

**1)** Put the community_auth package in CodeIgniter's application/third_party directory.

**2)** Verify that extensions in community_auth/helpers and community_auth/libraries do not conflict with extensions you already have made in your application. You'll need to merge any conflicts if they exist, and then eliminate one of the files.

**3)** Copy files from community_auth package to CodeIgniter's application sub-directories.

* If on linux or mac, if there is no existing application and no reason to worry about overwriting existing files, use the terminal and cd to the application directory, then execute ./third_party/community_auth/sh/.install.sh, then **skip to step 8**. You will probably need to set permissions of .install.sh to allow execution. After execution, remove permissions to execute or remove the file completely.
* If not on linux or mac, or if you already have an existing application, **proceed to step 4**.

**4)** Copy MY_Controller.php, MY_Input.php, MY_Log.php, and MY_Model.php from community_auth/core to CodeIgniter's application/core directory. If you already had these files, merge them with your existing files.

**5)** Copy auth_constants.php from community_auth/hooks to CodeIgniter's application/hooks directory.

**6)** Copy the controllers in community_auth/controllers to CodeIgniter's application/controllers directory.

**7)** The .htaccess file in community_auth/public_root can be moved to CodeIgniter's public root directory. Notice the lines at the top that allow for access denial. If you already have an .htaccess file, make sure to include those lines at the top of it.

**8)** If the site has a security certificate, change value of USE_SSL to 1 in application/hooks/auth_constants.php. While you are there you should review the other constants, as all are configurable.

**9)** Modify config/autoload:

	$autoload['packages'] = array(
		APPPATH . 'third_party/community_auth/'
	);

	$autoload['libraries'] = array(
		'fb','database','session','tokens','Authentication'
	);

	$autoload['helper'] = array(
		'serialization','security','html','url','form','cookie'
	);

	$autoload['config'] = array(
		'db_tables','authentication'
	);

	$autoload['model'] = array(
		'auth_model'
	);

**10)** Add route to login page in config/routes:

	$route[LOGIN_PAGE] = 'user/login';

**11)** Enable hooks and make sure there is an encryption key set in config/config.

**12)** While not critical for basic Community Auth usage, check out and configure community_auth/config/authentication.php as needed.

**13)** Define Community Auth constants hook by adding it to config/hooks.

	$hook['pre_system'] = array(
		'function' => 'auth_constants',
		'filename' => 'auth_constants.php',
		'filepath' => 'hooks'
	);

**14)** Database

* Create a database if not already available.
* Run community_auth/sql/install.sql.
* Configure CodeIgniter to use the database in config/database.php.

**15)** Create a user for testing purposes by editing the user_data array that is inside the create_user method, which is in the Examples controller. When specifying a user level, be aware of the "levels_and_roles" array located in config/authentication. Run /examples/create_user in your browser to create the user.

**16)** If you did everything right, you should be able to go to /examples and log in.

**17)** Going to /user/logout will log you out.

License
-------

Community Auth is distributed with a Revised "3-clause" BSD license, http://www.opensource.org/licenses/BSD-3-Clause:

Copyright (c) 2011 - 2015, Robert B Gottier.
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.

2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

3. Neither the name "Community Auth", nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. 
