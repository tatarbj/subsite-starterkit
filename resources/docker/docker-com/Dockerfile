FROM composer/composer

RUN groupadd -g 1000 jenkins
RUN useradd -g jenkins -u 1000 -m jenkins
RUN chown -R jenkins:jenkins /composer

USER jenkins

ADD configs/composer.json /composer/composer.json

RUN composer global install
