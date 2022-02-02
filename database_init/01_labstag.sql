CREATE DATABASE IF NOT EXISTS `labstag_bdd`;
CREATE USER IF NOT EXISTS 'labstag'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON `labstag_bdd`.* TO 'labstag'@'%';