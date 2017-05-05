FROM fpfis/php56

RUN groupadd -g 1000 jenkins
RUN useradd -g jenkins -u 1000 -m jenkins
#RUN gpasswd -a jenkins apache
RUN usermod apache --append --groups jenkins

RUN chown -R jenkins:jenkins /composer /var/www

USER jenkins

ADD configs/composer.json /composer/composer.json

RUN composer global install

RUN curl -L "https://ftp.drupal.org/files/projects/registry_rebuild-7.x-2.5.tar.gz" | tar xzf registry_rebuild-7.x-2.5.tar.gz && \
    rm registry_rebuild-7.x-2.5.tar.gz && \
    mkdir -p ~/.drush && \
    mv registry_rebuild ~/.drush

USER root
