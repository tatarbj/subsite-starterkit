FROM fpfis/php56

RUN groupadd -g 1000 jenkins
RUN useradd -g jenkins -u 1000 jenkins
RUN gpasswd -a jenkins apache

USER jenkins

ADD configs/composer.json /composer/composer.json

RUN composer global install

USER root
