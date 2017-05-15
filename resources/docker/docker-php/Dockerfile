FROM fpfis/php56-dev

RUN groupadd -g 1000 jenkins
RUN useradd -g jenkins -u 1000 -m jenkins
#RUN gpasswd -a jenkins apache
RUN usermod apache --append --groups jenkins

RUN chown -R jenkins:jenkins /composer /var/www

#RUN yum -y install httpd-devel
#RUN yum -y groupinstall 'Development Tools'
#RUN yum -y install wget nano

#ADD configs/suphp.conf /etc/httpd/conf.d/suphp.conf
#ADD configs/etc/suphp.conf /etc/suphp.conf

#RUN wget http://suphp.org/download/suphp-0.7.2.tar.gz && \
#    wget https://lists.marsching.com/pipermail/suphp/attachments/20130520/74f3ac02/attachment.patch && \
#    tar zxvf suphp-0.7.2.tar.gz && \
#    patch -Np1 -d suphp-0.7.2 < attachment.patch && \
#    mv suphp-0.7.2 /usr/local/src && \
#    autoreconf -if /usr/local/src/suphp-0.7.2

#RUN /usr/local/src/suphp-0.7.2/configure --prefix=/usr/ --sysconfdir=/etc/ --with-apr=/usr/bin/apr-1-config --with-apache-user=apache --with-setid-mode=owner --with-logfile=/var/log/httpd/suphp_log

#RUN make && \
#    make install

USER jenkins

ADD configs/composer.json /composer/composer.json

RUN composer global install

RUN curl -o ~/registry_rebuild-7.x-2.5.tar.gz -sS "https://ftp.drupal.org/files/projects/registry_rebuild-7.x-2.5.tar.gz" && \
    mkdir -p ~/.drush/registry_rebuild && \
    tar xzf ~/registry_rebuild-7.x-2.5.tar.gz -C ~/.drush/registry_rebuild && \
    rm ~/registry_rebuild-7.x-2.5.tar.gz

USER root
