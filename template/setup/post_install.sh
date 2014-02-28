#!/bin/bash


if [ ! -f /apps/asterisk/etc/asterisk/asterisk.conf ] ; then
    echo "Copying default confs."
    mkdir -p /apps/asterisk/etc/asterisk/
    cp -a /apps/asterisk/conf/* /apps/asterisk/etc/asterisk/
    sync
fi

if [ ! -d /apps/asterisk/var/lib/asterisk/moh ] ; then
    echo "Copying MOH sound files"
    mkdir -p /apps/asterisk/var/lib/asterisk/moh/
    cp -a /apps/asterisk/moh/* /apps/asterisk/var/lib/asterisk/moh/
    sync
fi

