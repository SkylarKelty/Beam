#!/usr/bin/env bash

# No SELinux
echo "
SELINUX=disabled
SELINUXTYPE=targeted
" > /etc/selinux/config

# EPEL.
yum install rpm epel-release -y

echo "
[epel]
name=Extra Packages for Enterprise Linux 7 - \$basearch
baseurl=http://download.fedoraproject.org/pub/epel/7/\$basearch
#mirrorlist=https://mirrors.fedoraproject.org/metalink?repo=epel-7&arch=\$basearch
failovermethod=priority
enabled=1
gpgcheck=1
gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-EPEL-7
" > /etc/yum.repos.d/epel.repo

# Remi.
yum install http://rpms.famillecollet.com/enterprise/remi-release-7.rpm -y

echo "
[remi]
name=Les RPM de remi pour Enterprise Linux 7 - \$basearch
#baseurl=http://rpms.famillecollet.com/enterprise/7/remi/\$basearch/
mirrorlist=http://rpms.famillecollet.com/enterprise/7/remi/mirror
enabled=1
gpgcheck=1
gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-remi

[remi-php56]
name=Les RPM de remi de PHP 5.6 pour Enterprise Linux 7 - \$basearch
#baseurl=http://rpms.famillecollet.com/enterprise/7/php56/\$basearch/
mirrorlist=http://rpms.famillecollet.com/enterprise/7/php56/mirror
enabled=1
gpgcheck=1
gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-remi
" > /etc/yum.repos.d/remi.repo

# Percona.
yum install http://www.percona.com/downloads/percona-release/redhat/0.1-3/percona-release-0.1-3.noarch.rpm -y
rm /etc/my.cnf
yum install Percona-Server-server-56 -y

# Install stuff.
yum install vim curl git Percona-Server-server-56 memcached httpd php php-pecl-memcached php-mysqlnd php-opcache php-mcrypt -y

# Install Composer.
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Configure services.
systemctl enable httpd
systemctl enable memcached
systemctl enable mysqld
systemctl start httpd
systemctl start memcached
systemctl start mysqld

# Configure MySQL.
mysql -e "CREATE FUNCTION fnv1a_64 RETURNS INTEGER SONAME 'libfnv1a_udf.so'"
mysql -e "CREATE FUNCTION fnv_64 RETURNS INTEGER SONAME 'libfnv_udf.so'"
mysql -e "CREATE FUNCTION murmur_hash RETURNS INTEGER SONAME 'libmurmur_udf.so'"
mysql -e "CREATE DATABASE beam_development;"
mysql -e "GRANT ALL ON beam_development.* TO beam@localhost IDENTIFIED BY 'password';"

# Configure httpd.
echo "
ServerName localhost

<VirtualHost *:80>
    ServerAdmin webmaster@dummy-host.example.com

    <Directory /vagrant>
        Require all granted
    </Directory>

    DocumentRoot /vagrant/
</VirtualHost>
" > /etc/httpd/conf.d/sites.conf

systemctl restart httpd
