<?php

namespace App\Providers\Dropbox;

use Exception;
use GrahamCampbell\GuzzleFactory\GuzzleFactory;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\PumpStream;
use GuzzleHttp\Psr7\StreamWrapper;
use Illuminate\Support\Facades\Cache;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Spatie\Dropbox\Client;
use Spatie\Dropbox\Exceptions\BadRequest;

class CachingDropboxClient extends Client
{
    protected int $ttl = 60*60*12; // cache for 12 hours

    /**
     * Search a file or folder in the user's Dropbox.
     *
     * @return array<mixed>
     *
     * @link https://www.dropbox.com/developers/documentation/http/documentation#files-search
     */
    public function search(string $query, bool $includeHighlights = false): array
    {
        $key = __FUNCTION__ . '_' . var_export(func_get_args(), true);
        if (Cache::has($key)) {
            return Cache::get($key);
        }
        $result = call_user_func_array([get_parent_class($this), __FUNCTION__], func_get_args());
        Cache::put($key, $result, $this->ttl);
        return $result;
    }

    /**
     * List shared links.
     *
     * For empty path returns a list of all shared links. For non-empty path
     * returns a list of all shared links with access to the given path.
     *
     * If direct_only is set true, only direct links to the path will be returned, otherwise
     * it may return link to the path itself and parent folders as described on docs.
     *
     * @return array<mixed>
     *
     * @link https://www.dropbox.com/developers/documentation/http/documentation#sharing-list_shared_links
     */
    public function listSharedLinks(?string $path = null, bool $direct_only = false, ?string $cursor = null): array
    {
        $key = __FUNCTION__ . '_' . var_export(func_get_args(), true);
        if (Cache::has($key)) {
            return Cache::get($key);
        }
        $result = call_user_func_array([get_parent_class($this), __FUNCTION__], func_get_args());
        Cache::put($key, $result, $this->ttl);
        return $result;
    }

    /**
     * Returns the metadata for a file or folder.
     *
     * Note: Metadata for the root folder is unsupported.
     *
     * @return array<mixed>
     *
     * @link https://www.dropbox.com/developers/documentation/http/documentation#files-get_metadata
     */
    public function getMetadata(string $path): array
    {
        $key = __FUNCTION__ . '_' . var_export(func_get_args(), true);
        if (Cache::has($key)) {
            return Cache::get($key);
        }
        $result = call_user_func_array([get_parent_class($this), __FUNCTION__], func_get_args());
        Cache::put($key, $result, $this->ttl);
        return $result;
    }

    /**
     * Get a temporary link to stream content of a file.
     *
     * This link will expire in four hours and afterwards you will get 410 Gone.
     * Content-Type of the link is determined automatically by the file's mime type.
     *
     * @link https://www.dropbox.com/developers/documentation/http/documentation#files-get_temporary_link
     */
    public function getTemporaryLink(string $path): string
    {
        $key = __FUNCTION__ . '_' . var_export(func_get_args(), true);
        if (Cache::has($key)) {
            return Cache::get($key);
        }
        $result = call_user_func_array([get_parent_class($this), __FUNCTION__], func_get_args());
        Cache::put($key, $result, $this->ttl);
        return $result;
    }

    /**
     * Get a thumbnail for an image.
     *
     * This method currently supports files with the following file extensions:
     * jpg, jpeg, png, tiff, tif, gif and bmp.
     *
     * Photos that are larger than 20MB in size won't be converted to a thumbnail.
     *
     * @link https://www.dropbox.com/developers/documentation/http/documentation#files-get_thumbnail
     */
    public function getThumbnail(string $path, string $format = 'jpeg', string $size = 'w64h64'): string
    {
        $key = __FUNCTION__ . '_' . var_export(func_get_args(), true);
        if (Cache::has($key)) {
            return Cache::get($key);
        }
        $result = call_user_func_array([get_parent_class($this), __FUNCTION__], func_get_args());
        Cache::put($key, $result, $this->ttl);
        return $result;
    }

    /**
     * Starts returning the contents of a folder.
     *
     * If the result's ListFolderResult.has_more field is true, call
     * list_folder/continue with the returned ListFolderResult.cursor to retrieve more entries.
     *
     * Note: auth.RateLimitError may be returned if multiple list_folder or list_folder/continue calls
     * with same parameters are made simultaneously by same API app for same user. If your app implements
     * retry logic, please hold off the retry until the previous request finishes.
     *
     * @return array<mixed>
     *
     * @link https://www.dropbox.com/developers/documentation/http/documentation#files-list_folder
     */
    public function listFolder(string $path = '', bool $recursive = false): array
    {
        $key = __FUNCTION__ . '_' . var_export(func_get_args(), true);
        if (Cache::has($key)) {
            return Cache::get($key);
        }
        $result = call_user_func_array([get_parent_class($this), __FUNCTION__], func_get_args());
        Cache::put($key, $result, $this->ttl);
        return $result;
    }

    /**
     * Once a cursor has been retrieved from list_folder, use this to paginate through all files and
     * retrieve updates to the folder, following the same rules as documented for list_folder.
     *
     * @return array<mixed>
     *
     * @link https://www.dropbox.com/developers/documentation/http/documentation#files-list_folder-continue
     */
    public function listFolderContinue(string $cursor = ''): array
    {
        $key = __FUNCTION__ . '_' . var_export(func_get_args(), true);
        if (Cache::has($key)) {
            return Cache::get($key);
        }
        $result = call_user_func_array([get_parent_class($this), __FUNCTION__], func_get_args());
        Cache::put($key, $result, $this->ttl);
        return $result;
    }

    /**
     * Get Account Info for current authenticated user.
     *
     * @return array<mixed>
     *
     * @link https://www.dropbox.com/developers/documentation/http/documentation#users-get_current_account
     */
    public function getAccountInfo(): array
    {
        $key = __FUNCTION__ . '_' . var_export(func_get_args(), true);
        if (Cache::has($key)) {
            return Cache::get($key);
        }
        $result = call_user_func_array([get_parent_class($this), __FUNCTION__], func_get_args());
        Cache::put($key, $result, $this->ttl);
        return $result;
    }
}
