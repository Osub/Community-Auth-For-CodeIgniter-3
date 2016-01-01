#!/bin/bash

# Make sure CodeIgniter doesn't already exist
if [ ! -d ./application ]; 
then
	TMPFILE=`mktemp`

	# Download and extract CodeIgniter
	wget https://github.com/bcit-ci/CodeIgniter/archive/master.tar.gz -O $TMPFILE
	tar -xvf $TMPFILE --strip 1
	rm $TMPFILE

	# Download and extract Community Auth
	cd ./application/third_party
	wget https://bitbucket.org/skunkbad/community-auth-for-codeigniter-3/get/master.tar.gz -O $TMPFILE
	tar -xvf $TMPFILE --strip 3
	rm $TMPFILE

	cd ../

	# Copy core files from Community Auth to CodeIgniter application
	if [ ! -f ./core/MY_Controller.php ]; then
		cp ./third_party/community_auth/core/MY_Controller.php ./core/MY_Controller.php 
	fi

	if [ ! -f ./core/MY_Input.php ]; then
		cp ./third_party/community_auth/core/MY_Input.php ./core/MY_Input.php 
	fi

	if [ ! -f ./core/MY_Model.php ]; then
		cp ./third_party/community_auth/core/MY_Model.php ./core/MY_Model.php
	fi

	# Copy hook files from Community Auth to CodeIgniter application
	if [ ! -f ./hooks/auth_constants.php ]; then
		cp ./third_party/community_auth/hooks/auth_constants.php ./hooks/auth_constants.php
	fi

	if [ ! -f ./hooks/auth_sess_check.php ]; then
		cp ./third_party/community_auth/hooks/auth_sess_check.php ./hooks/auth_sess_check.php
	fi

	# Copy controller files from Community Auth to CodeIgniter application
	if [ ! -f ./controllers/Examples.php ]; then
		cp ./third_party/community_auth/controllers/Examples.php ./controllers/Examples.php 
	fi

	if [ ! -f ./controllers/Key_creator.php ]; then
		cp ./third_party/community_auth/controllers/Key_creator.php ./controllers/Key_creator.php 
	fi

	# Copy or modify main .htaccess
	if [ ! -f ./../.htaccess ]; 
	then
		cp ./third_party/community_auth/public_root/.htaccess ./../.htaccess
	else
		sed -i '1s/^/# MAKE SURE TO LEAVE THE NEXT TWO LINES HERE.\n# BEGIN DENY LIST --\n# END DENY LIST --\n\n/' ./../.htaccess
	fi

	echo "REMOVE INSTALLER SCRIPT (THIS FILE) AND PROCEED TO INSTALLATION STEP 8."

else
	echo "INSTALLER ERROR: APPLICATION ALREADY INSTALLED."
fi