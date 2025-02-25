FROM escolasoft/php:8.1-nginx
ADD . /var/www/html
RUN composer i
RUN chown -R www-data:www-data *

COPY ./docker/php/memory-limit.ini /usr/local/etc/php/conf.d/memory-limit.ini
