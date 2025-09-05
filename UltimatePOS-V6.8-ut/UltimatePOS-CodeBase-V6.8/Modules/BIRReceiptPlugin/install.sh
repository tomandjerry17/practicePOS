#!/bin/bash

echo "Installing BIR Receipt Plugin..."

# Run the installation script
php Modules/BIRReceiptPlugin/install.php

# Run migrations
echo "Running migrations..."
php artisan migrate

# Seed the database
echo "Seeding BIR templates..."
php artisan db:seed --class="Modules\\BIRReceiptPlugin\\Database\\Seeders\\BIRReceiptTemplateSeeder"

echo "Seeding BIR settings..."
php artisan db:seed --class="Modules\\BIRReceiptPlugin\\Database\\Seeders\\BIRReceiptSettingsSeeder"

# Clear cache
echo "Clearing cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "BIR Receipt Plugin installation completed!"
echo ""
echo "You can now:"
echo "1. Visit /bir-receipt to access the plugin"
echo "2. Visit /bir-receipt/settings to configure your business information"
echo "3. Use the BIR Receipt button in your POS interface"
echo ""
echo "For more information, see Modules/BIRReceiptPlugin/README.md"
