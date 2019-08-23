#!/bin/bash

CUR_DIR=$(dirname $0)
cd $CUR_DIR/oroques

if [[ "$#" -eq 0 ]]; then
    bundle exec jekyll serve
elif [[ "$#" -eq 1 ]]; then
    bundle exec jekyll serve -P $1
else
    echo "Usage: ./serve.sh [port]"
    exit 1
fi
