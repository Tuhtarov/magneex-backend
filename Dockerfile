FROM php:8.1-cli

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY php-local.ini "$PHP_INI_DIR/conf.d/php-local.ini"

RUN \
	mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" && \
    	apt-get -o Acquire::Check-Valid-Until=false -o Acquire::Check-Date=false update && \
	apt-get install -y \
	git unzip wget vim && \
	wget https://get.symfony.com/cli/installer -O - | bash && \
	mv /root/.symfony/bin/symfony /usr/local/bin/symfony && \
	docker-php-ext-install pdo_mysql && \
	apt-get remove -y wget && \
	apt-get autoremove -y && \
	rm -rf /var/lib/apt/lists/*

WORKDIR /home/user/app

COPY . .

RUN \
	rm composer.lock symfony.lock && \
	composer install && \
	symfony console lexik:jwt:generate-keypair --overwrite

EXPOSE 80:80

VOLUME [ "src", "config" ]

CMD ["symfony", "server:start", "--no-tls", "--port=80"]

