#!/bin/sh

echo "🎬 entrypoint.sh: [$(whoami)] [PHP $(php -r 'echo phpversion();')]"

composer dump-autoload --no-interaction --no-dev --optimize

echo "🎬 artisan commands"

# 💡 Group into a custom command e.g. php artisan app:on-deploy
php artisan migrate --no-interaction --force
php artisan storage:link

echo "🎬 setup nodejs environment"
# Create nodejs/.env if OPENAI_API_KEY is set
if [ ! -z "$OPENAI_API_KEY" ]; then
    echo "OPENAI_API_KEY=$OPENAI_API_KEY" > $LARAVEL_PATH/nodejs/.env
    echo "PORT=3000" >> $LARAVEL_PATH/nodejs/.env
    echo "DB_HOST=$DB_HOST" >> $LARAVEL_PATH/nodejs/.env
    echo "DB_PORT=$DB_PORT" >> $LARAVEL_PATH/nodejs/.env
    echo "DB_DATABASE=$DB_DATABASE" >> $LARAVEL_PATH/nodejs/.env
    echo "DB_USER=$DB_USERNAME" >> $LARAVEL_PATH/nodejs/.env
    echo "DB_PASSWORD=$DB_PASSWORD" >> $LARAVEL_PATH/nodejs/.env
fi

echo "🎬 start supervisord"

supervisord -c $LARAVEL_PATH/.deploy/config/supervisor.conf
