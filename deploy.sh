#!/bin/sh
sudo npm run dev
sudo php artisan cache:clear
sudo php artisan config:clear
sudo php artisan config:cache
sudo php artisan route:clear
sudo php artisan view:clear
sudo php artisan clear-compiled

