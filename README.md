# LaraSniffer (Laravel Package)
Detect violations of a defined coding standard. It helps your code remains clean and consistent.

## Quick start

### Required setup

In the `require` key of `composer.json` file add the following

    "leroy-merlin-br/larasniffer": "dev-master"

Run the Composer update comand

    $ composer update

In your `config/app.php` add `'LeroyMelin\LaraSniffer\ServiceProvider'` to the end of the `$providers` array

    'providers' => array(

        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        ...
        'LeroyMelin\LaraSniffer\ServiceProvider',

    ),

Publish the AssetWatcher configuration file:

    php artisan config:publish leroy-merlin-br/larasniffer

Edit the configuration file `app/config/packages/leroy-merlin-br/larasniffer/config.php` adding the behavior you want.

### Usage

    php artisan sniff
