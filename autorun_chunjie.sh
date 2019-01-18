#! /bin/bash

echo "下载最新代码"
cd /data/chunjie/ && git pull && composer install
echo "开启队列"
nohup  /usr/bin/php7.1 /data/chunjie/artisan queue:work database --queue=h5 --tries=1 --delay=108000 --daemon >/dev/null 2>&1 &
nohup  /usr/bin/php7.1 /data/chunjie/artisan queue:work database --queue=h5 --tries=1 --delay=108000 --daemon >/dev/null 2>&1 &
nohup  /usr/bin/php7.1 /data/chunjie/artisan queue:work database --queue=h5 --tries=1 --delay=108000 --daemon >/dev/null 2>&1 &
nohup  /usr/bin/php7.1 /data/chunjie/artisan queue:work database --queue=h5 --tries=1 --delay=108000 --daemon >/dev/null 2>&1 &
nohup  /usr/bin/php7.1 /data/chunjie/artisan queue:work database --queue=h5 --tries=1 --delay=108000 --daemon >/dev/null 2>&1 &
nohup  /usr/bin/php7.1 /data/chunjie/artisan queue:work database --queue=h5 --tries=1 --delay=108000 --daemon >/dev/null 2>&1 &
nohup  /usr/bin/php7.1 /data/chunjie/artisan queue:work database --queue=h5 --tries=1 --delay=108000 --daemon >/dev/null 2>&1 &
nohup  /usr/bin/php7.1 /data/chunjie/artisan queue:work database --queue=h5 --tries=1 --delay=108000 --daemon >/dev/null 2>&1 &
nohup  /usr/bin/php7.1 /data/chunjie/artisan queue:work database --queue=h5 --tries=1 --delay=108000 --daemon >/dev/null 2>&1 &
nohup  /usr/bin/php7.1 /data/chunjie/artisan queue:work database --queue=h5 --tries=1 --delay=108000 --daemon >/dev/null 2>&1 &
echo "队列开启完毕"