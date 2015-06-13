#!/bin/bash
# core
cp ./application/third_party/community_auth/core/MY_Controller.php ./application/core/MY_Controller.php 
cp ./application/third_party/community_auth/core/MY_Input.php ./application/core/MY_Input.php 
cp ./application/third_party/community_auth/core/MY_Model.php ./application/core/MY_Model.php
# hooks
cp ./application/third_party/community_auth/hooks/auth_constants.php ./application/hooks/auth_constants.php
# controllers
cp ./application/third_party/community_auth/controllers/Examples.php ./application/controllers/Examples.php 
cp ./application/third_party/community_auth/controllers/User.php ./application/controllers/User.php
# public_root
cp ./application/third_party/community_auth/public_root/.htaccess ./.htaccess
