FROM fpfis/php56

RUN useradd  -u 2000 jenkins
RUN gpasswd -a jenkins apache

ADD configs/composer.json /composer/composer.json

RUN composer global install
