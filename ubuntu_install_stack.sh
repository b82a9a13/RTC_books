#!/bin/bash
sudo apt update -y
sudo apt upgrade -y
sudo apt install -y apache2 mariadb-server php php-curl php-mysql
sudo systemctl enable apache2
sudo systemctl start apache2
sudo systemctl enable mariadb
sudo systemctl start mariadb
sudo mysql_secure_installation
