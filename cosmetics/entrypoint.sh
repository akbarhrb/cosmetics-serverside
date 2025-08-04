#!/bin/bash

echo "Waiting for PostgreSQL to be available..."

# Try to connect to the database by checking migration status
until php artisan migrate:status > /dev/null 2>&1; do
  >&2 echo "PostgreSQL is unavailable - sleeping"
  sleep 3
done

echo "PostgreSQL is up - running migrations..."
php artisan migrate --force

echo "Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=8000
