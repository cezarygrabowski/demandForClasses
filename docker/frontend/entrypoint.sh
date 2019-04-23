#!/bin/bash

cd /var/www/frontend
cp  src/environments/environment.local.ts.dist src/environments/environment.local.ts
sed -ri 's;apiUrl:(.*?)("|'"'"')(.*?)("|'"'"');apiUrl: "'$API_URL'";' src/environments/environment.local.ts
npm install

if [ ! "$NG_HOST" ]; then
    NG_HOST=$(ifconfig | grep eth0 -A 1 | tail -n 1 | sed -r 's/.*inet ([0-9.]+?) .*$/\1/')
fi

cd /var/www/frontend
ng serve --host=0.0.0.0 --port 4200 --disable-host-check
# ng serve --host=0.0.0.0 --port 4200 --public-host=$NG_HOST --disable-host-check
