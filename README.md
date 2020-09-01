# PHP PSR-4 sample

## Description
Sample code for how PSR4 autoload works in PHP

The system is a spceialist system (uses a low level artificial inteligence
architecture known as specialist) to play a guessing game. Whenever it miss a
guess it tryes to learn a little more.

Uses SQLite* as databases (by and large no need to install anything) and
implements a verry simple structure, similar to neural network schema, but a
little simpler for it does not have the guessing weight, only the path to the
answare. And, although multiple paths are possible for the same answare, it
does not provide multiple paths to that same answare (physically the paths are
different in the repository). For examnple, it can tell if lasagna is pasta,
but it cannot tell how much cheese is in it. Humanly we can say it knows that
Lasagna is a Pasta, but it has no idea how much pasta and how much cheese it
has in its content in relation to a pasta boiled with salt. Literaly it cannot
say how much "Pasta" is "Lasagna" nor teh other way around.;

*) By default it creates a file in "/tmp/php-psr4.sample" to store database (its
the database schema repository file). you can change it at the ".env" file

## Requirements
- PHP 7.4+ (will not work with previous versions) with php7.0-sqlite (the docker image bellow have it)
- [Composer](https://getcomposer.org/)
- [Postman](https://www.postman.com/) to import "resources/postman/PHP PSR4 Sample.postman_collection.json"
- SQLite
- Unix-Like operational system**

**) The system uses the UUID generator of the unix system therefor it must run
on unix like (Linux, FreeBSD MacOS, and others). If you are on windows machine
you must use docker container to run the system or change the UUID generator.
The generator was tested on Linux systems, if you are on another distribution
or other operational system the UUID generator may need a fix, but in essence,
all linux distributions and "Unix System V" standards systems must provide some
kind of proprietary UUID generator file.

If you don't have **PHP 7.4+** you can just do ```docker run -it --rm --name my-running-script -p 8000:8000 -v /tmp/php-
.sample:/tmp/php-ps4.sample -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:7.4-cli php -S 0.0.0.0:8000``` at your project folder and you should be
good to go asap.

## Getting started
To get started, take the following steps:

1. Local clone repository;
2. Import postman collection on your prefered postman client;
3. Run composer install;
4. Create a ".env" file (could copy from ".env.dev" file)
5. Touch the DB_DATABASE reffered file on .env ('default to /tmp/php-ps4.sample)
6. Run ``` php -S localhost:8000 ``` (or use docker as sampled tipped in the item "requirements" right above)
7. Play the game using postman collection!

Its database agnostic, you can chose another one aside SQLite if you will

## Recomendations

Read the ~/.env.dev file for more tips on configuration.

# API Definitions

# Methods

* **/**: The base structure of the game. It creates the database and sets session
up;
* **/start**: Made to start up a new round of questions, it will start by the
first question them follow some path to the las known answare;
* **/yes**: If the reply to the last question is no you get to cosume this
method, it will reply you with a new question. It can end the game if the last
answare to the last question is the same food you whore thinking;
* **/no**: Same as yes, but happens when you'de like to say no to the last
question. It can end the round asking you to teatch the game what was you
thinking of.
* **/add**: Add new knowledge to the engine. Must be consumed only afther some
'/no' consumption;
* **/knowledge**: shows the knowledge structure. See bellow to check out a
sample;

## Same table structure samples

Bellow the base structure when you hit '/' method on the API
```json
{
    "data": [
        {
            "uuid": "51b1e62d-cfcc-45b8-93cc-8f196c3b6f12",
            "parent": "",
            "item": "Pasta",
            "yes": "ff1d2990-145b-405f-8f2f-1088ffd5cf1a",
            "no": "29f0d3b3-4324-422b-9caf-887fb69a43cc",
            "created_at": "2020-08-22 18:35:30",
            "updated_at": "2020-08-22 18:35:30"
        },
        {
            "uuid": "ff1d2990-145b-405f-8f2f-1088ffd5cf1a",
            "parent": "51b1e62d-cfcc-45b8-93cc-8f196c3b6f12",
            "item": "Lasagna",
            "yes": null,
            "no": null,
            "created_at": "2020-08-22 18:35:30",
            "updated_at": "2020-08-22 18:35:30"
        },
        {
            "uuid": "29f0d3b3-4324-422b-9caf-887fb69a43cc",
            "parent": "51b1e62d-cfcc-45b8-93cc-8f196c3b6f12",
            "item": "Chocolate cake",
            "yes": null,
            "no": null,
            "created_at": "2020-08-22 18:35:30",
            "updated_at": "2020-08-22 18:35:30"
        }
    ]
}
```

If you reply you're not thinking in pasta nor it is a chocolate cake for the
first round the system will ask you to teatch it. If you add, lest say an apple
as a fruit, the structure will turn to look like:
```json
{
    "data": [
        {
            "uuid": "51b1e62d-cfcc-45b8-93cc-8f196c3b6f12",
            "parent": "",
            "item": "Pasta",
            "yes": "ff1d2990-145b-405f-8f2f-1088ffd5cf1a",
            "no": "369e7729-bd46-46de-8099-bba91dc2e7d1",
            "created_at": "2020-08-22 18:35:30",
            "updated_at": "2020-08-22 18:39:26"
        },
        {
            "uuid": "ff1d2990-145b-405f-8f2f-1088ffd5cf1a",
            "parent": "51b1e62d-cfcc-45b8-93cc-8f196c3b6f12",
            "item": "Lasagna",
            "yes": null,
            "no": null,
            "created_at": "2020-08-22 18:35:30",
            "updated_at": "2020-08-22 18:35:30"
        },
        {
            "uuid": "29f0d3b3-4324-422b-9caf-887fb69a43cc",
            "parent": "369e7729-bd46-46de-8099-bba91dc2e7d1",
            "item": "Chocolate cake",
            "yes": null,
            "no": null,
            "created_at": "2020-08-22 18:35:30",
            "updated_at": "2020-08-22 18:39:26"
        },
        {
            "uuid": "369e7729-bd46-46de-8099-bba91dc2e7d1",
            "parent": "51b1e62d-cfcc-45b8-93cc-8f196c3b6f12",
            "item": "Fruit",
            "yes": "b3144d74-58c5-4741-8fc6-c969becf3ea1",
            "no": "29f0d3b3-4324-422b-9caf-887fb69a43cc",
            "created_at": "2020-08-22 18:39:26",
            "updated_at": "2020-08-22 18:39:26"
        },
        {
            "uuid": "b3144d74-58c5-4741-8fc6-c969becf3ea1",
            "parent": "369e7729-bd46-46de-8099-bba91dc2e7d1",
            "item": "Maça",
            "yes": null,
            "no": null,
            "created_at": "2020-08-22 18:39:26",
            "updated_at": "2020-08-22 18:39:26"
        }
    ]
}
```

