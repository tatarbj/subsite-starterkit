FROM fpfis/php56

RUN useradd  -u 2000 jenkins
RUN gpasswd -a jenkins apache

RUN chown -R jenkins:jenkins /composer

USER jenkins

ADD configs/composer.json /composer/composer.json

RUN composer global install
