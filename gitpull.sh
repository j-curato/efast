#!/bin/bash

# Check if the script is run from the correct directory
if [ ! -f "composer.json" ]; then
    echo "Error: This script must be run from the Yii2 project root directory."
    exit 1
fi
echo 'qwe'
git fetch
# Perform git pull
git pull 

# Install/update Composer dependencies
# composer install --no-interaction --optimize-autoloader

# Run any necessary database migrations
php yii migrate --interactive=0