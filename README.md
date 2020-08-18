# PHP PS-4 sample

## Description
Sample code for how PS4 autoload works in PHP

## Requirements
- PHP 7.4+ (will not work with previous versions)
- [Composer](https://getcomposer.org/)
- [Postman](https://www.postman.com/) to import "resources/postman/PHP PS4 Sample.postman_collection.json"

If you don't have **PHP 7.4+** you can just do ```docker run -it --rm --name my-running-script -p 8000:8000 -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:7.4-cli php -S 0.0.0.0:8000``` at your project folder and you should be good to go asap.

## Getting started
To get started, take the following steps:

1. Local clone repository;
2. Import postman collection on your prefered postman client;
2. Run composer install;
3. Run ``` php -S localhost:8000 ``` (or use docker as sampled tipped in the item "requirements" right above)
4. Play the game using postman collection!