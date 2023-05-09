#!/usr/bin/env bash

set -e

vendor/bin/phpstan analyse
vendor/bin/phpunit --testsuite unit -v
vendor/bin/phpunit --testsuite integration -v
vendor/bin/behat --suite acceptance -vvv
