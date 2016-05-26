tuttotracker
============

A Symfony project created on May 20, 2016, 10:30 pm.
this is a test project using symfony3 behat3 react redux react-redux router and webpack
the purpose of this project is purely ludic

enjoy!

how to run it
=============

Requirements:
node > v5.5.9
webpack (npm install -g webpack webpack-dev-server)
php > v5.5
mysql > v5.5

    git clone git@github.com:KernelFolla/tuttotracker.git
    cd tuttotracker
    composer install
    npm install

And then, run a live server with Webpack hot-reloading of assets:

    webpack --progress --colors --config webpack.config.js
    webpack-dev-server --progress --colors --config webpack.config.js

* Also, you may want to run the Symfony server:

    bin/console server:start

After this, visit [http://127.0.0.1:8000](http://127.0.0.1:8000).

raw server setup backlog
=============
    #as root ofc
    apt-get update
    apt-get upgrade
    curl -sL https://deb.nodesource.com/setup_5.x | sudo -E bash -
    sudo apt-get install -y nodejs
    apt-get install -y mysql-client mysql-server git tmux vim apache2 php5 php5-mysql php5-curl libapache2-mod-php5
    sed -i 's/\/var\/www\/html/\/var\/www\/tuttotracker\/web/' /etc/apache2/sites-enabled/000-default.conf

    ####
    #by hand replace in /etc/apache2/apache2.conf
    #from
    <Directory /var/www/>
            Options Indexes FollowSymLinks
            AllowOverride None
            Require all granted
    </Directory>
    #to
    <Directory /var/www/>
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted
    </Directory>
    ####
    a2enmod headers
    a2enmod rewrite
    service apache2 restart
    service mysql restart

    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '92102166af5abdb03f49ce52a40591073a7b859a86e8ff13338cf7db58a19f7844fbc0bb79b2773bf30791e935dbd938') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    mv composer.phar /usr/local/bin
    chown -R www-data:www-data tuttotracker/
    cd /var/www/
    git clone https://github.com/KernelFolla/tuttotracker.git
    cd tuttotracker
    composer.phar install
    npm install
    php bin/console doctrine:database:create
    php bin/console doctrine:schema:update --force
    echo "y" | php bin/console doctrine:fixtures:load
    php bin/console assets:install
    npm install -g webpack webpack-dev-server
    webpack --progress --colors --config webpack.config.js
    chown -R www-data:www-data tuttotracker/