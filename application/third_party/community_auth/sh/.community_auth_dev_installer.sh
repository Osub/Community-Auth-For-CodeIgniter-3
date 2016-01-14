#!/bin/bash
# This is the installer for those working on Community Auth development

# Make sure not already installed
if [ ! -d ./community_auth_ci_3 ]; 
then
	# Clone the Community Auth repository on bitbucket
	git clone git@bitbucket.org:skunkbad/community-auth-for-codeigniter-3.git community_auth_ci_3

	# The web root directory should now exist
	if [ -d ./community_auth_ci_3 ];
	then
		# Move into web root
		cd ./community_auth_ci_3

		# Temporarily move Community Auth
		mv ./application/third_party/community_auth/ ./community_auth_tmp/
		rm -r ./application

		# Temp file deleted after extract
		TMPFILE=`mktemp`

		# Download and extract CodeIgniter (skip old files so .gitignore is not replaced)
		wget https://github.com/bcit-ci/CodeIgniter/archive/master.tar.gz -O $TMPFILE
		tar -xf $TMPFILE --skip-old-files --strip 1
		rm $TMPFILE

		# Restore Community Auth to its third party location
		mv ./community_auth_tmp/ ./application/third_party/community_auth/ 

		# Move into application directory
		cd ./application

		# Copy core files from Community Auth to CodeIgniter application
		cp ./third_party/community_auth/core/MY_Controller.php ./core/MY_Controller.php
		cp ./third_party/community_auth/core/MY_Input.php ./core/MY_Input.php 
		cp ./third_party/community_auth/core/MY_Model.php ./core/MY_Model.php

		# Copy hook files from Community Auth to CodeIgniter application
		cp ./third_party/community_auth/hooks/auth_constants.php ./hooks/auth_constants.php
		cp ./third_party/community_auth/hooks/auth_sess_check.php ./hooks/auth_sess_check.php

		# Copy controller files from Community Auth to CodeIgniter application
		cp ./third_party/community_auth/controllers/Examples.php ./controllers/Examples.php 
		cp ./third_party/community_auth/controllers/Key_creator.php ./controllers/Key_creator.php 

		# Copy or modify main .htaccess
		cp ./third_party/community_auth/public_root/.htaccess ./../.htaccess

		# Add autoload configuration
		sed -i "s/\['packages'\] = array()/\['packages'\] = array(\n\tAPPPATH . 'third_party\/community_auth\/'\n)/g; \
s/\['libraries'\] = array()/\['libraries'\] = array(\n\t'database','session','tokens','Authentication'\n)/g; \
s/\['helper'\] = array()/\['helper'\] = array(\n\t'serialization','url','form','cookie'\n)/g; \
s/\['config'\] = array()/\['config'\] = array(\n\t'db_tables','authentication'\n)/g; \
s/\['model'\] = array()/\['model'\] = array(\n\t'auth_model'\n)/g;" ./config/autoload.php

		# Add route to login page
		echo "\$route[LOGIN_PAGE] = 'examples/login';" >> ./config/routes.php

		# Set base_url, index_page, turn hooks on, and add an encryption key
		sed -i "s/\['base_url'\] = ''/\['base_url'\] = 'http:\/\/localhost.community_auth_ci_3\/'/g; \
s/\['index_page'\] = 'index.php'/\['index_page'\] = ''/g; \
s/\['enable_hooks'\] = FALSE/\['enable_hooks'\] = TRUE/g; \
s/\['encryption_key'\] = ''/\['encryption_key'\] = hex2bin('5d3a06b1a1efeb861ad761fb8839794f')/g;" ./config/config.php

		# Add hooks
		printf "\$hook['pre_system'] = array(\n\t'function' => 'auth_constants',\n\t'filename' => 'auth_constants.php',\n\t'filepath' => 'hooks'\n);\n\$hook['post_system'] = array(\n\t'function' => 'auth_sess_check',\n\t'filename' => 'auth_sess_check.php',\n\t'filepath' => 'hooks'\n);" >> ./config/hooks.php

		# Create DB
		DBNAME=community_auth_ci_3
		mysqladmin -u root create $DBNAME
		cat ./third_party/community_auth/sql/install.sql | mysql -u root $DBNAME

		# Configure DB
		sed -i "s/'username' => ''/'username' => 'root'/g; \
s/'database' => ''/'database' => 'community_auth_ci_3'/g;" ./config/database.php

		# Pretty URLs
		printf "\n\nRewriteEngine On\nRewriteBase /\n\nRewriteRule ^(system|application|cgi-bin) - [F,L]\nRewriteCond %%{REQUEST_FILENAME} !-f\nRewriteCond %%{REQUEST_FILENAME} !-d\nRewriteRule .* index.php/\$0 [PT,L]" >> ./../.htaccess

		# Success
		echo "REMOVE INSTALLER SCRIPT (THIS FILE)."
	else
		# Error
		echo "INSTALLER ERROR: COMMUNITY AUTH DIR DOES NOT EXIST."
	fi
else
	# Error
	echo "INSTALLER ERROR: COMMUNITY AUTH DIR ALREADY EXISTS."
fi