#!/bin/bash

# Wait for the database to be ready
echo "Waiting for MySQL to be available..."

until php artisan db:connect --no-interaction; do
  >&2 echo "MySQL is unavailable - sleeping"
  sleep 3
done

echo "MySQL is up - running migrations..."
php artisan migrate --force

echo "Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=8000
