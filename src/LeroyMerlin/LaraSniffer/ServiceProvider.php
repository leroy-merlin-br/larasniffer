<?php namespace LeroyMerlin\LaraSniffer;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Service provider that registers SniffCommand into Laravel application
 *
 * @author    Zizaco Zizuini <zizaco@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @link      https://github.com/leroy-merlin-br/larasniffer
 */
class ServiceProvider extends BaseServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('leroy-merlin-br/larasniffer');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('command.larasniffer', function($app) {
            return new SniffCommand;
        });

        $this->commands('command.larasniffer');
    }
}
