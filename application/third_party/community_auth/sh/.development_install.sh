#!/bin/bash
# This is the installer for those working on Community Auth development.
# Place and execute this script where IT WILL CREATE the web root directory.
# It will also create the database, as long as you set a valid user and password.

# The directory that git will clone into
SITEDIR="community_auth_ci_3"

# The base_url for CodeIgniter
# Note: make sure to escape slashes!
BASEURL="http:\/\/localhost.community_auth_ci_3\/"

# The name of the database to create
DBNAME="community_auth_ci_3"

# The DB user
DBUSER="root"

# The DB password
DBPASS=""

# If symlinks (soft links) should be used instead of copying files
SYMLINKS=false

# -------------------------------------------------------------------------

# Make sure not already installed
if [ ! -d ./$SITEDIR ]; 
then
	# Clone the Community Auth repository on bitbucket
	git clone git@bitbucket.org:skunkbad/community-auth-for-codeigniter-3.git $SITEDIR

	# The web root directory should now exist
	if [ -d ./$SITEDIR ];
	then
		# Move into web root
		cd ./$SITEDIR

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

		# Copy or symlink core files
		if [ "$SYMLINKS" = true ];
		then
			ln -s ../third_party/community_auth/core/MY_Controller.php ./core/MY_Controller.php
			ln -s ../third_party/community_auth/core/MY_Input.php ./core/MY_Input.php
			ln -s ../third_party/community_auth/core/MY_Model.php ./core/MY_Model.php
		else
			cp ./third_party/community_auth/core/MY_Controller.php ./core/MY_Controller.php
			cp ./third_party/community_auth/core/MY_Input.php ./core/MY_Input.php
			cp ./third_party/community_auth/core/MY_Model.php ./core/MY_Model.php 
		fi

		# Copy or symlink hook files
		if [ "$SYMLINKS" = true ];
		then
			ln -s ../third_party/community_auth/hooks/auth_constants.php ./hooks/auth_constants.php
			ln -s ../third_party/community_auth/hooks/auth_sess_check.php ./hooks/auth_sess_check.php
		else
			cp ./third_party/community_auth/hooks/auth_constants.php ./hooks/auth_constants.php
			cp ./third_party/community_auth/hooks/auth_sess_check.php ./hooks/auth_sess_check.php
		fi

		# Copy or symlink controller files
		if [ "$SYMLINKS" = true ];
		then
			ln -s ../third_party/community_auth/controllers/Examples.php ./controllers/Examples.php
			ln -s ../third_party/community_auth/controllers/Key_creator.php ./controllers/Key_creator.php
		else
			cp ./third_party/community_auth/controllers/Examples.php ./controllers/Examples.php 
			cp ./third_party/community_auth/controllers/Key_creator.php ./controllers/Key_creator.php
		fi 

		# Copy or modify main .htaccess
		cp ./third_party/community_auth/public_root/.htaccess ./../.htaccess

		# Add autoload configuration
		sed -i "s/\['packages'\] = array()/\['packages'\] = array(\n\tAPPPATH . 'third_party\/community_auth\/'\n)/g; \
s/\['libraries'\] = array()/\['libraries'\] = array(\n\t'database','session','tokens','Authentication'\n)/g; \
s/\['helper'\] = array()/\['helper'\] = array(\n\t'serialization','cookie'\n)/g; \
s/\['config'\] = array()/\['config'\] = array(\n\t'db_tables','authentication'\n)/g; \
s/\['model'\] = array()/\['model'\] = array(\n\t'auth_model'\n)/g;" ./config/autoload.php

		# Add route to login page
		echo "\$route[LOGIN_PAGE] = 'examples/login';" >> ./config/routes.php

		# Change home page to examples/home
		sed -i "s/\['default_controller'\] = 'welcome'/\['default_controller'\] = 'examples\/home'/g" ./config/routes.php

		# Set base_url, index_page, turn hooks on, add an encryption key, and configure sessions
		sed -i "s/\['base_url'\] = ''/\['base_url'\] = '$BASEURL'/g; \
s/\['index_page'\] = 'index.php'/\['index_page'\] = ''/g; \
s/\['enable_hooks'\] = FALSE/\['enable_hooks'\] = TRUE/g; \
s/\['encryption_key'\] = ''/\['encryption_key'\] = hex2bin('5d3a06b1a1efeb861ad761fb8839794f')/g; \
s/\['sess_driver'\] = 'files'/\['sess_driver'\] = 'database'/g; \
s/\['sess_cookie_name'\] = 'ci_session'/\['sess_cookie_name'\] = 'ciSess'/g; \
s/\['sess_save_path'\] = NULL/\['sess_save_path'\] = 'ci_sessions'/g; \
s/\['sess_regenerate_destroy'\] = FALSE/\['sess_regenerate_destroy'\] = TRUE/g;" ./config/config.php

		# Add hooks
		printf "\$hook['pre_system'] = array(\n\t'function' => 'auth_constants',\n\t'filename' => 'auth_constants.php',\n\t'filepath' => 'hooks'\n);\n\$hook['post_system'] = array(\n\t'function' => 'auth_sess_check',\n\t'filename' => 'auth_sess_check.php',\n\t'filepath' => 'hooks'\n);" >> ./config/hooks.php

		# Create DB
		if [ -n "$DBPASS" ];
		then
			mysqladmin -u $DBUSER -p$DBPASS create $DBNAME
			cat ./third_party/community_auth/sql/install.sql | mysql -u $DBUSER -p$DBPASS $DBNAME
		else
			mysqladmin -u $DBUSER create $DBNAME
			cat ./third_party/community_auth/sql/install.sql | mysql -u $DBUSER $DBNAME
		fi

		# Configure DB
		if [ -n "$DBPASS" ];
		then
			sed -i "s/'username' => ''/'username' => '$DBUSER'/g; \
s/'password' => ''/'password' => '$DBPASS'/g; \
s/'database' => ''/'database' => '$DBNAME'/g;" ./config/database.php
		else
			sed -i "s/'username' => ''/'username' => '$DBUSER'/g; \
s/'database' => ''/'database' => '$DBNAME'/g;" ./config/database.php
		fi

		# Pretty URLs
		printf "\n\nRewriteEngine On\nRewriteBase /\n\nRewriteRule ^(system|application|cgi-bin) - [F,L]\nRewriteCond %%{REQUEST_FILENAME} !-f\nRewriteCond %%{REQUEST_FILENAME} !-d\nRewriteRule .* index.php/\$0 [PT,L]" >> ./../.htaccess

		# Checkout develop from origin
		git checkout -b develop origin/develop

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