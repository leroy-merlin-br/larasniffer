# LaraSniffer (Laravel Package)
Detect violations of a defined coding standard. It helps your code remains clean and consistent. Available options are: **PSR2**, **PSR1**, **Zend**, **PEAR**, **Squiz**, **PHPCS** and **MySource**.

[![Build Status](https://api.travis-ci.org/leroy-merlin-br/larasniffer.png)](https://travis-ci.org/leroy-merlin-br/larasniffer)
[![Coverage Status](https://coveralls.io/repos/leroy-merlin-br/larasniffer/badge.png?branch=master)](https://coveralls.io/r/leroy-merlin-br/larasniffer?branch=master)
[![Latest Stable Version](https://poser.pugx.org/leroy-merlin-br/larasniffer/v/stable.png)](https://packagist.org/packages/leroy-merlin-br/larasniffer)
[![Total Downloads](https://poser.pugx.org/leroy-merlin-br/larasniffer/downloads.png)](https://packagist.org/packages/leroy-merlin-br/larasniffer)
[![License](https://poser.pugx.org/leroy-merlin-br/larasniffer/license.png)](http://opensource.org/licenses/MIT)

![php artisan sniff](https://dl.dropboxusercontent.com/u/12506137/libs_bundles/php_artisan_sniff.png)

## Quick start

### Required setup

In the `require` key of `composer.json` file add the following

    "leroy-merlin-br/larasniffer": "dev-master"

Run the Composer update comand

    $ composer update

In your `config/app.php` add `'LeroyMerlin\LaraSniffer\ServiceProvider'` to the end of the `$providers` array

    'providers' => array(

        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        ...
        'LeroyMerlin\LaraSniffer\ServiceProvider',

    ),

Publish the configuration file:

    php artisan config:publish leroy-merlin-br/larasniffer

Edit the configuration file `app/config/packages/leroy-merlin-br/larasniffer/config.php` to tweak the sniffer behavior.

### Usage

    php artisan sniff

## License

LaraSniffer is free software distributed under the terms of the MIT license

## Aditional information

Any issues, please [report here](https://github.com/leroy-merlin-br/larasniffer/issues)
