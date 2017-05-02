FROM fpfis/php56

ADD configs/httpd.conf /etc/httpd/conf/httpd.conf

RUN groupadd -g 1000 jenkins
RUN useradd -g jenkins  -u 1000 jenkins
RUN gpasswd -a jenkins apache

RUN chown -R jenkins:jenkins /composer

USER jenkins

ADD configs/composer.json /composer/composer.json

RUN composer global install
