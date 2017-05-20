#!/bin/sh

# Post install or update.
PHING=$(pwd)/bin/phing
if [ -f $PHING ] && [ -x $PHING ] ; then
  $PHING $1 -Dproject.basedir=$PROJECT -find
else
  echo "Phing unavailable"
fi
