#!/bin/sh

if [ $# -eq 0 ]
  then
    echo "Missing version number. Usage: ./deploy.sh <version>"
    exit 1
fi

git tag -a $1 -m ""
git push origin $1

zip -r retailspotvideo_prestashop_$1.zip psretailspotvideo/