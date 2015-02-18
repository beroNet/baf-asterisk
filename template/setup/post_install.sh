#!/bin/bash

if [ ! -f /apps/asterisk/etc/asterisk/asterisk.conf ]; then
	echo "Copying default confs."
	mkdir -p /apps/asterisk/etc/asterisk/
	cp -a /apps/asterisk/conf/* /apps/asterisk/etc/asterisk/
	sync
fi

