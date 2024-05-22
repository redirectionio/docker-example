#!/bin/sh

set -e

export REDIRECTIONIO_PROJECT_KEY
envsubst '${REDIRECTIONIO_PROJECT_KEY}' < /tmp/default.template > /etc/nginx/sites-enabled/default

exec "$@"
