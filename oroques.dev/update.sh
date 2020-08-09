#!/bin/bash

SCRIPT_DIR=$(dirname "$0")
THEMES_DIR="$SCRIPT_DIR"/root/themes

if [[ ! -d $THEMES_DIR ]]; then
  mkdir -v "$THEMES_DIR"
fi

cd "$THEMES_DIR" || exit 1
rm -rf researcher
git clone https://github.com/ojroques/hugo-researcher researcher
rm -rf researcher/.git
rm -rf researcher/exampleSite
rm -rf researcher/images
rm -rf researcher/README.md
