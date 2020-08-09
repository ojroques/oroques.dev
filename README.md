# oroques.dev

My personal server which hosts my website [oroques.dev](https://oroques.dev/) built with [Hugo](https://github.com/gohugoio/hugo).

The server is running:
* Nginx
* [Gitea](https://gitea.io/en-us/), a self-hosted git service.
* MariaDB
* phpMyAdmin

Everything is running inside Docker containers and Docker Compose is used to manage them.

## Installation

#### Install necessary packages
```bash
./install.sh
```

#### Generate TLS certificates
1. Edit [certbot/digitalocean.ini](certbot/digitalocean.ini) with a DigitalOcean API access token.
2. Run setup script:
```bash
sudo certbot/setup.sh
```

#### Configure MariaDB
1. Create the file containing MariaDB root password:
```bash
echo "<password>" > mariadb/root_password
```
2. Create the file containing the user password:
```bash
echo "<password>" > mariadb/user_password
```
3. Edit [mariadb/init.sql](mariadb/init.sql) with `gitea` password:
```mysql
CREATE USER IF NOT EXISTS 'gitea'@'gitea.oroquesdev_default' IDENTIFIED BY '<password>';
```

#### Run Docker Compose
```bash
docker-compose up --detach
```
