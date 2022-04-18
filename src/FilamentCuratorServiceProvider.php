<?php

namespace FilamentCurator;

use Livewire\Livewire;
use FilamentCurator\Models\Media;
use Filament\Facades\Filament;
use FilamentCurator\Observers\MediaObserver;
use Filament\PluginServiceProvider;
use Illuminate\Support\Facades\File;
use Filament\Navigation\UserMenuItem;
use FilamentCurator\Commands\MakeInstallCuratorCommand;
use FilamentCurator\Commands\MakePublishCuratorCommand;
use Spatie\LaravelPackageTools\Package;
use FilamentCurator\Commands\RegenerateThumbnails;

class FilamentCuratorServiceProvider extends PluginServiceProvider
{
    protected array $beforeCoreScripts = [
        'https://unpkg.com/@alpinejs/intersect@3.x.x/dist/cdn.min.js',
    ];

    protected array $styles = [
        'filament-curator-styles' => __DIR__ . '/../resources/dist/filament-curator.css',
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-curator')
            ->hasConfigFile()
            ->hasAssets()
            ->hasViews()
            ->hasRoute("web")
            ->hasCommands([
                RegenerateThumbnails::class,
                MakeInstallCuratorCommand::class,
                MakePublishCuratorCommand::class,
            ])
            ->hasMigrations(['create_media_table']);
    }

    public function boot()
    {
        parent::boot();

        Livewire::component('media-picker-modal', Forms\Components\MediaPickerModal::class);
        Livewire::component('create-media-form', Forms\Components\CreateMediaForm::class);

        Media::observe(MediaObserver::class);
    }
}