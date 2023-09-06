#!/bin/bash

# Function to display error messages
function error() {
    mariadb -e "DROP DATABASE kosma_panel";
    mariadb -e "DROP USER 'KosmaPanel'@'127.0.0.1';";
    rm -r /var/www/KosmaPanel
    rm /etc/apache2/sites-available/KosmaPanel.conf
    rm /etc/apache2/sites-enabled/KosmaPanel.conf
    rm /etc/apt/sources.list.d/mariadb.list
    apt -y remove apache2* libapache2-mod-php8.2* certbot* php8.2* mariadb-server* redis* 
    apt -y autoremove 
    rm /etc/apt/sources.list.d/redis.list
    apt update 
    rm /usr/local/bin/composer 
    rm /usr/share/keyrings/redis-archive-keyring.gpg
    echo "[ERROR] $1"
    echo "Failed to install KosmaPanel Please make sure you contact support at: https://discord.gg/7BZTmSK2D8"
    exit 1
}

# Function to install dependencies on Ubuntu
function install_dependencies_ubuntu() {
    # Add "add-apt-repository" command
    apt -y install software-properties-common curl apt-transport-https ca-certificates lsb-release gnupg gpg || error "Failed to install software-properties-common"

    # Add additional repositories for PHP, Redis, and MariaDB
    LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php || error "Failed to add PHP repository"

    # MariaDB repo setup script can be skipped on Ubuntu 22.04
    curl -sS https://downloads.mariadb.com/MariaDB/mariadb_repo_setup | sudo bash || error "Failed to add MariaDB repository"

    # Update repositories list
    apt update || error "Failed to update repositories list"

    # Add universe repository if you are on Ubuntu 18.04
    apt-add-repository universe || error "Failed to add universe repository"

    #Add the repository to the apt index, update it, and then install:
    curl -fsSL https://packages.redis.io/gpg | sudo gpg --dearmor -o /usr/share/keyrings/redis-archive-keyring.gpg || error "Failed to add redis to APT"
    echo "deb [signed-by=/usr/share/keyrings/redis-archive-keyring.gpg] https://packages.redis.io/deb $(lsb_release -cs) main" | sudo tee /etc/apt/sources.list.d/redis.list || error "Failed to add redis to APT"

    # Update repositories list
    apt update || error "Failed to update repositories list"

    # Install Dependencies
    apt -y install apache2 libapache2-mod-php8.2 certbot python3-certbot-apache php8.2 php8.2-{common,cli,gd,mysql,mbstring,bcmath,xml,fpm,curl,zip} mariadb-server tar unzip zip git redis || error "Failed to install dependencies"

    # Composer
    curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer || error "Failed to install Composer"
}

# Function to install dependencies on Debian
function install_dependencies_debian() {
    # Update package lists
    apt update -y || error "Failed to update package lists"

    # Install necessary packages
    apt -y install software-properties-common curl ca-certificates gnupg2 sudo lsb-release || error "Failed to install necessary packages"

    # Add repository for PHP
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/sury-php.list
    curl -fsSL  https://packages.sury.org/php/apt.gpg | sudo gpg --dearmor -o /etc/apt/trusted.gpg.d/sury-keyring.gpg || error "Failed to add PHP repository"

    # Add repository for Redis
    curl -fsSL https://packages.redis.io/gpg | sudo gpg --dearmor -o /usr/share/keyrings/redis-archive-keyring.gpg || error "Failed to install necessary packages"
    echo "deb [signed-by=/usr/share/keyrings/redis-archive-keyring.gpg] https://packages.redis.io/deb $(lsb_release -cs) main" | sudo tee /etc/apt/sources.list.d/redis.list || error "Failed to install necessary packages"

    # Update package lists
    apt update -y || error "Failed to update package lists"

    # Install PHP and required extensions
    apt install -y apache2 libapache2-mod-php8.2 php8.2 php8.2-{common,cli,gd,mysql,mbstring,bcmath,xml,fpm,curl,zip} || error "Failed to install PHP and required extensions"

    # MariaDB repo setup script
    curl -sS https://downloads.mariadb.com/MariaDB/mariadb_repo_setup | sudo bash || error "Failed to add MariaDB repository"

    apt -y install certbot python3-certbot-apache mariadb-server tar unzip zip git redis || error "Failed to install dependencies"

    # Composer 
    curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
}


# Function to download project files from Git
function download_project_files() {
    cd /var/www || error "Failed to change directory to /var/www"
    git clone https://github.com/MythicalLTD/KosmaPanel.git || error "Failed to clone project files"
    cd /var/www/KosmaPanel || error "Failed to change directory to /var/www/KosmaPanel"
}

function pma_fix() {
    cd /var/www/KosmaPanel/public/pma
    chmod 777 tmp
}

function configapp() {
    cd /var/www/KosmaPanel
    ./KosmaPanel -generate-config
    ./KosmaPanel -config-database
    ./KosmaPanel -migrate-database-now
    ./KosmaPanel -key-generate
}

# Function to set correct permissions
function set_permissions() {
    chown -R www-data:www-data /var/www/KosmaPanel/* || error "Failed to set permissions on Panel files"
}

# Function to create SSL certificates
function create_ssl_certificates() {

    # Creating a Certificate
    echo "Creating SSL certificate for domain: $domain"

    # HTTP challenge
     certbot certonly --apache --non-interactive --agree-tos --register-unsafely-without-email -d "$domain" || error "Failed to create SSL certificate"

    echo "SSL certificate successfully created for domain: $domain"
}

function install_dotnet() {
    cd || error "Failed to move to user home dir"
    wget https://dot.net/v1/dotnet-install.sh -O dotnet-install.sh || error "Failed to download dotnet installer script"
    chmod +x ./dotnet-install.sh || error "Failed to download dotnet installer script"
    ./dotnet-install.sh --channel 7.0 || error "Failed to download dotnet installer script"
    export PATH=$PATH:$DOTNET_ROOT:$DOTNET_ROOT/tools || error "Failed to add dotnet to path"
}

function install_composer_packages() {
    cd /var/www/KosmaPanel || error "Failed to change directory to /var/www/KosmaPanel"
    composer install --no-dev --optimize-autoloader || error "Failed to install composer required packages"
}

# Function to configure webserver
function configure_webserver() {
    # Prompt for domain name
    read -p "Enter your domain name: " domain

    # Remove default Apache configuration
    a2dissite 000-default.conf || error "Failed to disable default Apache configuration"

    # Create Apache configuration file
    echo "Creating Apache configuration file..."
    cat <<EOF > /etc/apache2/sites-available/KosmaPanel.conf
<VirtualHost *:80>
  ServerName $domain

  RewriteEngine On
  RewriteCond %{HTTPS} !=on
  RewriteRule ^/?(.*) https://%{SERVER_NAME}/$1 [R,L] 
</VirtualHost>

<VirtualHost *:443>
  ServerName $domain
  DocumentRoot "/var/www/KosmaPanel/public"

  AllowEncodedSlashes On
  
  php_value upload_max_filesize 100M
  php_value post_max_size 100M

  <Directory "/var/www/KosmaPanel/public">
    Require all granted
    AllowOverride all
  </Directory>
  ErrorLog /var/www/KosmaPanel/logs/error.log
  CustomLog /var/www/KosmaPanel/logs/access.log combined
  <FilesMatch \.php$>
      SetHandler "proxy:unix:/run/php/php8.2-fpm.sock|fcgi://localhost"
  </FilesMatch>
  SSLEngine on
  SSLCertificateFile /etc/letsencrypt/live/$domain/fullchain.pem
  SSLCertificateKeyFile /etc/letsencrypt/live/$domain/privkey.pem
</VirtualHost>
EOF

    # Enable Apache configuration
    ln -s /etc/apache2/sites-available/KosmaPanel.conf /etc/apache2/sites-enabled/KosmaPanel.conf || error "Failed to enable Apache configuration"

    # Enable Apache modules
    sudo a2enmod actions fcgid alias proxy_fcgi rewrite ssl || error "Failed to enable Apache modules"

    # Restart Apache
    systemctl restart apache2 || error "Failed to restart Apache"
}

# Prompt for MySQL password
read -s -p "Enter the MySQL password that you want to use: " mysql_password
echo

# Prompt for domain name
read -p "Enter your domain name: " domain

# Prompt for email address
read -p "Enter your email address: " email

# Update package lists
apt update || error "Failed to update package lists"

# Install dependencies based on distribution
if [[ -f "/etc/lsb-release" ]]; then
    install_dependencies_ubuntu
elif [[ -f "/etc/debian_version" ]]; then
    install_dependencies_debian
else
    error "Unsupported distribution"
fi

# Download project files from Git
download_project_files

install_dotnet

apt update
apt upgrade -y
service mariadb start
# Create database and user
mariadb -e "CREATE USER 'KosmaPanel'@'127.0.0.1' IDENTIFIED BY '$mysql_password'; CREATE DATABASE kosma_panel; GRANT ALL PRIVILEGES ON kosma_panel.* TO 'KosmaPanel'@'127.0.0.1' WITH GRANT OPTION;" || error "Failed to create database and user"
sudo apt-get install libapache2-mod-fcgid

# Set permissions on Panel files
set_permissions

install_composer_packages

pma_fix

# Create SSL certificates
create_ssl_certificates

# Configure webserver
configure_webserver

echo "Installation completed successfully!"

configapp
