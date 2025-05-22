<?php

namespace App\Providers\Dropbox;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Spatie\Dropbox\RefreshableTokenProvider;

class AutoRefreshingTokenProvider implements RefreshableTokenProvider
{
    private string $access_token;

    public function __construct(string $access_token)
    {
        $this->access_token = $access_token;
    }

    public function refresh(ClientException $exception): bool
    {
        $client = Http::withBasicAuth(config('filesystems.disks.content.app_key'), config('filesystems.disks.content.app_secret'))
            ->asForm()
            ->post('https://api.dropbox.com/oauth2/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => config('filesystems.disks.content.refresh_token'),
            ]);

        if ($client->status() !== 200) {
            return false; // couldn't refresh
        }

        $this->access_token = $client->json('access_token');
        Cache::put('dropbox_access_token', $this->access_token, 3*60*60); // cache new token for 3 hours (TTL of access tokens is around 4 hours)
        return true; // confirm token was refreshed
    }

    public function getToken(): string
    {
        return $this->access_token;
    }
}
