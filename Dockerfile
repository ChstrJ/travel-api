FROM php:8.3-fpm-alpine


# RUN apt-get update && apt-get install -y \

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

RUN mkdir -p /var/www

