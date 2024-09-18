#!/bin/bash

# Install Laravel
composer create-project laravel/laravel laravel

# Copy .env file to laravel folder
#yes | cp -rf ./.env ./laravel/.env

# Go to Laravel directory
#cd ./laravel

# Pulse package setup
#composer require laravel/pulse
#php artisan vendor:publish --provider="Laravel\Pulse\PulseServiceProvider"

# Laravel Reverb package (web socket aka Pusher)
#composer require laravel/reverb
#npm install --save-dev laravel-echo pusher-js

# Laravel Breeze setup
#composer require laravel/breeze --dev
#php artisan breeze:install api

# Install Redis
#composer require predis/predis:^2.0

# Install odata package
#composer require lexxsoft/odata
#php artisan vendor:publish --provider="Lexxsoft\Odata\Providers\OdataServiceProvider"

# Install Spatie laravel permissions package
#composer require spatie/laravel-permission
#php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# Migrate database
#php artisan migrate

# Install npm packages
#npm i

# Install Tailwind CSS
#npm install -D tailwindcss postcss autoprefixer
#npx tailwindcss init -p
