CREATE DATABASE IF NOT EXISTS gitea;
CREATE USER IF NOT EXISTS 'gitea'@'gitea.oroquesdev_default' IDENTIFIED BY '';
GRANT ALL PRIVILEGES ON gitea.* TO 'gitea'@'gitea.oroquesdev_default';
