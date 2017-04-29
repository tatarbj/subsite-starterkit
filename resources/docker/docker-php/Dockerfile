FROM fpfis/php56

RUN useradd -g apache -s /sbin/nologin jenkins

ADD configs/composer.json /composer/composer.json

RUN composer global install

USER jenkins
