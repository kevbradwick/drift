#!/usr/bin/env bash

./vendor/bin/phpcs --standard=PSR2 src/
./vendor/bin/phpunit -c test/phpunit.xml

