#!/bin/bash

# This script is only meant for the development environment.

DBNAME=community_auth_ci_3
mysqladmin -u root create $DBNAME
cat ../sql/install.sql | mysql -u root $DBNAME