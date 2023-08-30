#!/bin/sh

/bin/sh -c docker/env-substitution.sh

/bin/sh -c /usr/sbin/php-fpm82

exec nginx -g "daemon off;"
