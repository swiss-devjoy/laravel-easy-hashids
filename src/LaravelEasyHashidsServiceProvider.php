<?php

namespace SwissDevjoy\LaravelEasyHashids;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelEasyHashidsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-easy-hashids')
            ->hasConfigFile();
    }

    public function packageBooted(): void
    {
        if (class_exists(Livewire::class)) {
            Livewire::propertySynthesizer(LivewireModelHashidSynth::class);
        }
    }
}
