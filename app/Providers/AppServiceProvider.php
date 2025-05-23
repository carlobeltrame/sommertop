<?php

namespace App\Providers;

use App\Providers\Dropbox\AutoRefreshingTokenProvider;
use App\Providers\Dropbox\CachingDropboxClient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Storage::extend('dropbox', function(Application $app, array $config) {
            $tokenProvider = new AutoRefreshingTokenProvider(
                Cache::get('dropbox_access_token', 'none') // reuse cached access token if available
            );

            $adapter = new DropboxAdapter(
                new CachingDropboxClient($tokenProvider),
                $config['root']
            );

            return new FilesystemAdapter(
                new Filesystem($adapter, $config),
                $adapter,
                $config
            );
        });
    }
}
