#!/bin/sh

export DYLD_LIBRARY_PATH=""

BASEDIR=`dirname $0`

osascript -s s $BASEDIR/localtags.applescript $@
