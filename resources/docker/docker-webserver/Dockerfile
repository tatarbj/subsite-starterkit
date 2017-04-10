FROM ubuntu:14.04.1
MAINTAINER Ivan Pushkin <imetalguardi+docker@gmail.com>

ENV LC_ALL en_US.UTF-8
ENV LANG en_US.UTF-8
ENV TERM xterm
ENV MYSQL_USER root
ENV MYSQL_PASSWORD ""
ENV MYSQL_MAJOR 5.6
ENV HOSTNAME docker.dev

# add php configuration file in specified position
COPY configs/custom.php.ini /etc/php5.6/mods-available/custom.ini

# utf locale
RUN locale-gen $LC_ALL

# add nginx repository
RUN apt-key adv --keyserver pgp.mit.edu --recv-keys 573BFD6B3D8FBC641079A6ABABF5BD827BD9BF62 && \
    echo "deb http://nginx.org/packages/mainline/ubuntu/ $(lsb_release -cs) nginx" > /etc/apt/sources.list.d/nginx.list

# add mysql repository
RUN apt-key adv --keyserver pool.sks-keyservers.net --recv-keys A4A9406876FCBD3C456770C88C718D3B5072E1F5 && \
    echo "deb http://repo.mysql.com/apt/ubuntu/ $(lsb_release -cs) mysql-${MYSQL_MAJOR}" > /etc/apt/sources.list.d/mysql.list

# add ondrej php repository
RUN apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 14AA40EC0831756756D7F66C4F4EA0AAE5267A6C && \
    echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu trusty main" > /etc/apt/sources.list.d/php.list

# update
RUN apt-get update

# install all packages
RUN DEBIAN_FRONTEND=noninteractive apt-get -y -q --no-install-recommends install \
	nano \
	ssh \
	bash-completion \
	build-essential \
	chrpath \
	libssl-dev \
	libxft-dev \
	libfreetype6 \
	libfreetype6-dev \
	libfontconfig1 \
	libfontconfig1-dev \
	openssl \
	ca-certificates \
	nginx \
	supervisor \
	mysql-server \
	mysql-client \
	curl \
	wget \
	git \
	php5.6-fpm \
	php5.6-cli \
	php5.6-mysql \
	php5.6-curl \
	php5.6-gd \
	php5.6-intl \
	php5.6-imagick \
        php5.6-ldap \
        php5.6-mbstring \
	php5.6-mcrypt \
	php5.6-memcached \
	php5.6-json \
	php-pear \
	php5.6-dev \
	php5.6-xdebug \
        php5.6-xml \
	php5.6-sqlite \
	phpmyadmin \
	unzip \
	npm

# install phantomjs
RUN wget https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-2.1.1-linux-x86_64.tar.bz2
RUN tar xvjf phantomjs-2.1.1-linux-x86_64.tar.bz2 -C /usr/local/share/
RUN ln -s /usr/local/share/phantomjs-2.1.1-linux-x86_64/bin/phantomjs /usr/local/bin/

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# add user "docker" to use it as default user for working with files
RUN yes "" | adduser --uid=1000 --disabled-password jenkins && \
    echo "jenkins   ALL=(ALL:ALL) NOPASSWD: ALL" >> /etc/sudoers

# install composer assets plugin
RUN sudo -H -u jenkins bash -c "/usr/local/bin/composer global require fxp/composer-asset-plugin:~1.1"

# create and set access to the folder
RUN mkdir -p /web/jenkins && \
    echo "<?php echo 'web server is running';" > /web/jenkins/index.php && \
    chown -R jenkins:jenkins /web

# add custom php configuration
RUN php5enmod custom

# enable mcrypt module
RUN php5enmod mcrypt

# set access and error nginx logs to stdout and stderr
RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log

# clean apt cache and temps
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# add supervisord configuration file 
COPY configs/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# add mysql start script
COPY configs/mysql.sh /opt/mysql.sh

# replace php-fpm configuration file
COPY configs/php-fpm.conf /etc/php5.6/fpm/php-fpm.conf

# add phpmyadmin configuration file 
COPY configs/phpmyadmin.php /etc/phpmyadmin/conf.d/phpmyadmin.php

# replace nginx virtual host configuration file
COPY configs/default.conf /etc/nginx/conf.d/default.conf

COPY configs/xdebug.ini /etc/php5.6/mods-available/xdebug.ini

EXPOSE 80 443 3306 9000

VOLUME ["/web", "/var/lib/mysql"]

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

