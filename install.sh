#!/bin/bash

if [[ $EUID -ne 0 ]]; then
    echo "This script must be run as root."
    exit 1
fi

apt update
apt install -y ruby-full build-essential zlib1g-dev
gem install jekyll bundler --verbose
apt autoremove -y
