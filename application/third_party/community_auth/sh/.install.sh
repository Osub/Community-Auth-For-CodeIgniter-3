#!/bin/bash
# core
cp ./third_party/community_auth/core/MY_Controller.php ./core/MY_Controller.php 
cp ./third_party/community_auth/core/MY_Input.php ./core/MY_Input.php 
cp ./third_party/community_auth/core/MY_Model.php ./core/MY_Model.php
# hooks
cp ./third_party/community_auth/hooks/auth_constants.php ./hooks/auth_constants.php
# controllers
cp ./third_party/community_auth/controllers/Examples.php ./controllers/Examples.php 
cp ./third_party/community_auth/controllers/User.php ./controllers/User.php
# public_root
cp ./third_party/community_auth/public_root/.htaccess ./../.htaccess
