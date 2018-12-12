#!/bin/bash

# the agbiePlugin uses php-curl (this line has no effect if php-curl is already installed)
apt-get install php-curl

# copy the agbie subdir into CA plugins
cp -r agbie /var/www/providence/app/plugins/

# adjust the installed plugin's user:group ownership
chown -R www-data:www-data /var/www/providence/app/plugins/agbie
