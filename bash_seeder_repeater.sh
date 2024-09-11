#!/bin/bash

ASSET_COUNTER=0

while [  $ASSET_COUNTER -lt 10 ]; do
    php artisan db:seed --class="AssetSeeder" --verbose
    let ASSET_COUNTER=ASSET_COUNTER+1
    echo asset counter: $ASSET_COUNTER
done

ACTION_LOG_COUNTER=0

while [ $ACTION_LOG_COUNTER -lt 3 ]; do
    php -d xdebug.mode=profile artisan db:seed --class="ActionlogSeeder"
    let ACTION_LOG_COUNTER=ACTION_LOG_COUNTER+1
    echo action log counter: $ACTION_LOG_COUNTER
done
