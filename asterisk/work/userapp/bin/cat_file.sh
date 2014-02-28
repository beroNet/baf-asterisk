#!/bin/bash

file=$1

if ! test -d /apps/ 2>/dev/null ; then 
	exit 0
fi
  
for i in /apps/* ; do     
	appname=$(basename $i)
	if ! test -d /apps/$appname/etc/asterisk/ ; then 
#		echo "no asterisk directory found in app $appname"
		continue	
	fi
#	echo "found asterisk directory in app $appname" 
	if [ "$appname" == "asterisk" ] ; then
		continue;
	fi	
	appfile=/apps/$appname/etc/asterisk/$file
 	if test -f $appfile ; then 
 		cat $appfile	
 	fi
done
           
           
