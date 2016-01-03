#!/bin/bash

# This script is only meant for the development environment.

DBNAME=community_auth_ci_3
mysqladmin -u root create $DBNAME
cat ./third_party/community_auth/sql/install.sql | mysql -u root $DBNAME
echo "DB CREATED AND TABLES ADDED."