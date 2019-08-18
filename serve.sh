#!/bin/bash

CUR_DIR=$(dirname $0)
cd $CUR_DIR/oroques
bundle exec jekyll serve
