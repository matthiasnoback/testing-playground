#!/usr/bin/env bash

set -e

vendor/bin/phpstan analyse
vendor/bin/phpunit -v
#vendor/bin/behat --suite acceptance -vvv
