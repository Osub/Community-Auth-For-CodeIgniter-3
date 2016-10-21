#!/bin/bash
#
# This installer allows you to quickly move through steps 1 through 7 of 
# the installation instructions for Community Auth for CodeIgniter 3.
# After running this script, you'll want to delete it, then proceed to step 8.
# 

# Make sure CodeIgniter doesn't already exist
if [ ! -d ./application ]; 
then
	# Temp file deleted after extracts
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

	# Success
	echo "REMOVE INSTALLER SCRIPT (THIS FILE) AND PROCEED TO INSTALLATION STEP 8."
else
	# Error
	echo "INSTALLER ERROR: APPLICATION ALREADY INSTALLED."
fi