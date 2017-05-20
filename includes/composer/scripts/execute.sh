#!/bin/sh

# Post install or update.

PHING=$(pwd)/bin/phing
if [ -f $PHING ] && [ -x $PHING ] ; then
  $PHING composer-$1-cmd -Dproject.basedir=$PROJECT -logger phing.listener.AnsiColorLogger -find
else
  echo "Phing unavailable"
fi
