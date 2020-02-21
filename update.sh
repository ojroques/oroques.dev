#!/bin/bash

SCRIPT_DIR=$(dirname "$0")
cd "$SCRIPT_DIR"/oroques || exit 1

bundle install
bundle update
