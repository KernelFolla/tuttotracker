tuttotracker
============

A Symfony project created on May 20, 2016, 10:30 pm.


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
