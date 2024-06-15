# Installing

To host LibreY using Docker, you can follow the instructions in the [Docker directory](https://github.com/Ahwxorg/LibreY/tree/main/docker).

To host LibreY using your OS's native package manager and PHP+NGINX, you can use the following steps as a guide.

These instructions are specific to Debian GNU/Linux but should be similar on most *nix variants (differences may include commands for package management, init system, php-fpm.sock location and availability of fastcgi-php.conf)

Install the packages (Debian GNU/Linux):

```sh
apt install php php-fpm php-dom php-curl php-apcu nginx git
```

Install the packages (Arch Linux):
```sh
pacman -S php php-fpm php-apcu nginx git
```

Clone LibreY:

```sh
mkdir -p /var/www/html
git clone https://github.com/Ahwxorg/LibreY.git /var/www/html/LibreY
```

Rename the config and opensearch files, edit manually if needed:

```sh
cd /var/www/html/LibreY/
mv config.php.example config.php
mv opensearch.xml.example opensearch.xml
```

Change opensearch.xml to point to your domain:

```sh
sed -i 's/http:\/\/localhost:80/https:\/\/your.domain/g' opensearch.xml
```

An nginx configuration similar to the one below should be placed in your `http { ... }` block or a file that is automatically detected as such.

```sh
server {
        listen 80;

        server_name your.domain;

        root /var/www/html/LibreY;
        index index.php;

        location ~ \.php$ {
               include snippets/fastcgi-php.conf;
               fastcgi_pass unix:/run/php/php-fpm.sock;
        }
}
```

You can optionally remove the .php extension in URLs by adding this code your `server { ... }` block.

```sh
location / {
       try_files $uri $uri/ @extensionless-php;
}

location @extensionless-php {
       rewrite ^(.*)$ $1.php last;
}
```

Start php-fpm and nginx immediately and on every boot:

```sh
systemctl enable --now php-fpm nginx
```

Now LibreY should be running at the port you specified!
