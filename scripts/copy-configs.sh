#!/bin/sh

for path in $(find . -name install); do
  echo "Check files in folder $path"
  for filename in $(ls $path); do
    if [ -f configs/$filename ]; then
      cp configs/$filename $path
      echo "- Copy file $filename"
    fi
  done
done
