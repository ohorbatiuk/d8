#!/bin/sh

for path in $(find . -name install); do
  echo "Check files in folder $path"
  for filename in $(ls $path); do
    if [ -f configs/$filename ]; then
      echo "- Copy file $filename"
      cp configs/$filename $path
    fi
  done
done
