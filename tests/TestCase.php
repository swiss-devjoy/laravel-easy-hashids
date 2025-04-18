<?php

namespace SwissDevjoy\LaravelEasyHashids\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use SwissDevjoy\LaravelEasyHashids\LaravelEasyHashidsServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            LaravelEasyHashidsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config([
            'app.key' => 'base64:f2lupX5ecwW919amtuqsETEC3SJY54+9B/qE3/43EVk=',
            'database.default' => 'testing',
        ]);

        foreach (File::allFiles(__DIR__.'/fixture_migrations') as $migration) {
            (include $migration->getRealPath())->up();
        }

        Model::unguard();

        $app['config']->set('view.paths', [__DIR__.'/resources/views']);
    }
}
