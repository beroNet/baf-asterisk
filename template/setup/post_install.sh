#!/bin/bash


if [ ! -f /apps/pbx/etc/asterisk/asterisk.conf ] ; then
    echo "Copying default confs."
    mkdir -p /apps/pbx/etc/asterisk/
    cp -a /apps/pbx/conf/* /apps/pbx/etc/asterisk/
    sync
fi

