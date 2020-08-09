#!/bin/bash

SCRIPT_DIR=$(dirname "$0")
BASE_URL="http://localhost/"

cd "$SCRIPT_DIR"/root || exit 1

if [[ "$#" -eq 0 ]]; then
  hugo server -v -b "$BASE_URL"
elif [[ "$#" -eq 1 ]]; then
  hugo server -v -b "$BASE_URL" -p "$1"
else
  echo "Usage: $0 [port]"
  exit 1
fi
