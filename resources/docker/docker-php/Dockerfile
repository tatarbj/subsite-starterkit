FROM fpfis/php56

RUN useradd -G jenkins  -u 1000 jenkins
RUN gpasswd -a jenkins apache

RUN chown -R jenkins:jenkins /composer

RUN rm -rf /var/www/html

USER jenkins

ADD configs/composer.json /composer/composer.json

RUN composer global install
