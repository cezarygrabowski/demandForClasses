FROM centos:7

RUN yum -y install epel-release && \
    yum -y install http://rpms.remirepo.net/enterprise/remi-release-7.rpm && \
    yum -y update && \
    yum -y install yum-utils && \
    yum-config-manager --enable remi-php71 && \
    yum -y install php-mbstring php-common php-gmp php php-cli php-pdo php-pecl-mysql php-opcache php-xml php-pecl-zip php-gd php-mongodb && \
    yum -y install nginx php-fpm && \
    yum -y install supervisor && \
    mkdir -p /var/www /etc/init-scripts

ADD nginx/nginx.conf /etc/nginx/nginx.conf
ADD php-fpm/php-fpm.conf /etc/php-fpm.conf
ADD php-fpm/php-fpm.d    /etc/php-fpm.d

COPY ./supervisord.conf /etc/supervisor/supervisord.conf

ADD php.ini /etc/php.ini

RUN mkdir -p /var/log/php-fpm /var/log/nginx /var/lib/nginx /var/run/php-fpm /var/www && \
    touch /run/nginx.pid /supervisord.log /supervisord.pid && \
    chown -R root:root /var/log/php-fpm /var/log/nginx /var/lib/nginx /var/run/php-fpm /var/www /run/nginx.pid /var/run/php-fpm /supervisord.log /supervisord.pid && \
    chmod -R g=u /var/log/php-fpm /var/log/nginx /var/lib/nginx /var/run/php-fpm /var/www /run/nginx.pid /var/run/php-fpm /supervisord.log /supervisord.pid

#### CREATE SEPARATE php.ini FOR CLI
ADD php-cli-ini.sh /etc/profile.d
RUN cp /etc/php.ini /etc/php-cli.ini && \
    sed -i 's/memory_limit = 512M/memory_limit = 2048M/g' /etc/php-cli.ini

RUN useradd demand && \
    usermod -a -G root demand && \
    echo 'user demand;' >> /etc/nginx/nginx.conf && \
    sed -i 's/\[global\]/[global]\nuser=demand/g' /etc/php-fpm.d/www.conf && \
    echo 'listen.owner = demand' >> /etc/php-fpm.d/www.conf && \
    echo 'listen.group = demand' >> /etc/php-fpm.d/www.conf

#### INSTALL COMPOSER ####
RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
  && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
  && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
  && php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer \
  && rm -rf /tmp/composer-setup.php

#### INSTALL EXTENSIONS REQUIRED BY COMPOSER ####
RUN yum -y install php-pecl-zip php-xml

#### XDebug
RUN yum -y install php-xdebug
RUN echo -e "\nxdebug.remote_enable=on" >> /etc/php.ini \
    && echo "\nxdebug.remote_autostart=off" >> /etc/php.ini \
    && echo -e '\nxdebug.remote_enable = off' >> /etc/php-cli.ini

#### ADD APPLICATION INITIALIZATION SCRIPTS ####
ADD init/init-app.sh /etc/init-scripts

### BASH AUTOCOMPLETE ####
RUN yum install bash-completion -y

#### CHANGE WORKDIR
WORKDIR /var/www/api
USER demand

#### ENSURE THAT DEVELOPMENT ENVIRONMENT IS RECOGNIZED BY APPLICATION ####
ENV demand_env=dev


ADD entrypoint.sh /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
