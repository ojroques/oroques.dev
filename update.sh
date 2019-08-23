#!/bin/bash

SCRIPT_DIR=$(dirname $0)
cd $SCRIPT_DIR/oroques

bundle install
bundle update
