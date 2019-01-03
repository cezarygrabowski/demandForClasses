#!/bin/bash

find /etc/init-scripts -type f | xargs -r bash -c
cd /

/usr/bin/supervisord -c /etc/supervisor/supervisord.conf