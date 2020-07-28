#!/bin/bash

SCRIPT_DIR=$(dirname "$0")
cd "$SCRIPT_DIR"/oroques || exit 1

if [[ "$#" -eq 0 ]]; then
    bundle exec jekyll serve
elif [[ "$#" -eq 1 ]]; then
    bundle exec jekyll serve -P "$1"
else
    echo "Usage: $0 [port]"
    exit 1
fi
