FROM nginxinc/nginx-unprivileged:${NGINX_VERSION:-latest}

ARG WWWUSER
ARG WWWGROUP

WORKDIR /usr/share/nginx/html

USER root

RUN groupadd --force -g $WWWGROUP nginxuser
RUN useradd -ms /usr/sbin/nologin --no-user-group -g $WWWGROUP -u $WWWUSER nginxuser
RUN mkdir -p /var/www/html/app
RUN chown -R nginxuser:nginxuser /var/www
RUN chown -R nginxuser:nginxuser /etc/nginx

# COPY nginx.conf /etc/nginx/nginx.conf
# COPY conf.d/default.conf.template /etc/nginx/templates/

USER nginxuser
