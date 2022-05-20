FROM escolasoft/php:8.1-nginx
ADD . /var/www/html
RUN composer i
RUN chown -R www-data:www-data *
