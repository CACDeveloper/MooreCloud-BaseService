## Install
```
sudo apt-get install composer
composer require predis/predis
composer require zendframework
composer require zendframework/zend-config
composer require zendframework/zend-http
composer require zendframework/zend-uri
composer require firebase/php-jwt
```

## Redis
```
Download, extract and compile Redis with:
$ wget http://redis.googlecode.com/files/redis-2.6.4.tar.gz
$ tar xzf redis-2.6.4.tar.gz
$ cd redis-2.6.4
$ make
```

## Bitnami
```
把application放到Apache2的apps下 
在bitnami的conf folder 中, 修改bitnami-prefix.config.
在当中include application的路径
```