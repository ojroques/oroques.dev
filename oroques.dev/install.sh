#!/bin/bash

set -e

sudo apt update
sudo apt install -y ruby-full build-essential zlib1g-dev
sudo gem install jekyll bundler --verbose
sudo apt autoremove -y
