#!/bin/bash

CUR_DIR=$(dirname $0)
cd $CUR_DIR/oroques
bundle install
bundle update
