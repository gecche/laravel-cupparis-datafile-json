<?php namespace Gecche\Cupparis\DatafileJson;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class DatafileJsonServiceProvider extends ServiceProvider {


    public const UNIQUE_DATAFILE_JSON_RULE = 'unique_datafile_json';
    public const EXISTS_DATAFILE_JSON_RULE = 'exists_datafile_json';

    /**
     * Booting
     */
    public function boot()
    {

        $this->publishes([
            __DIR__.'/config/cupparis-datafile-json.php' => config_path('cupparis-datafile-json.php'),
        ]);


    }

    /**
     * Register the commands
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('datafile-json', function($app)
        {
            return new DatafileJsonManager($app->events);
        });
    }


}
