<?php
namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\CommonMark\CommonMarkConverter;

class FileService {

    public function getContents($dirname, $level = 2): array {
        $topLevelFiles = $this->files($dirname);
        $subsections = $this->directories($dirname);
        return array_merge($this->directoryInfo($dirname), [
            'level' => 'h' . $level,
            'files' => $topLevelFiles->map(function ($file) { return $this->fileInfo($file); }),
            'subsections' => $subsections->map(function ($subsection) use($level) {
                return $this->getContents($subsection, $level + 1);
            })
        ]);
    }

    public function directoryInfo(string|array $dir): array {
        if (is_array($dir)) return $dir;
        return [
            'displayName' => $this->displayName($dir),
            'sortName' => $this->sortName($dir),
            'path' => $this->slug($dir),
        ];
    }

    public function fileInfo(string $file): array {
        $path = $this->slug($file);
        $displayName = $this->displayName($file);
        if (Str::endsWith($file, ['.url.txt', '.link.txt'])) {
            $path = Storage::disk('content')->get($file);
            $displayName = preg_replace('/\.(url|link)$/', '', $displayName);
        }
        if (Str::endsWith($file, ['.web'])) {
            $path = json_decode(Storage::disk('content')->get($file))->url;
            $displayName = preg_replace('/\.(url|link)$/', '', $displayName);
        }
        if (Str::endsWith($file, ['.md', '.html'])) {
            $html = Storage::disk('content')->get($file);
            if (Str::endsWith($file, '.md')) {
                $converter = new CommonMarkConverter();
                $html = $converter->convert($html);
            }
            return [
                'type' => Str::endsWith($file, ['.list.md', '.list.html']) ? 'list-html' : 'html',
                'sortName' => $this->sortName($file),
                'html' => $html,
            ];
        }
        if (Str::endsWith($file, ['.mp3', '.wav', '.m4a'])) {
            return [
                'type' => 'audio',
                'displayName' => $displayName,
                'sortName' => $this->sortName($file),
                'path' => $path,
            ];
        }
        return [
            'displayName' => $displayName,
            'sortName' => $this->sortName($file),
            'path' => $path,
        ];
    }

    public function findDirBySlug(string $slug): ?string {
        return $this->directories('/')
            ->first(function($dir) use ($slug) {
                return $this->slug($dir) === $slug;
            });
    }

    public function findFileBySlug(string $dir, string $pathSlug): ?string {
        $fullPathSlug = $this->slug($dir) . '/' . $pathSlug;
        return $this->files($dir, true)
            ->first(function($file) use ($fullPathSlug) {
                return $this->slug($file) === $fullPathSlug;
            });
    }

    protected function filterPublic(Collection $files): Collection {
        return $files->filter(function ($file) {
            if (preg_match('/^\.|\/\./', $file)) return false;
            return true;
        });
    }

    protected function filterWithin(string $dir, Collection $files, bool $deep = false): Collection {
        if (!Str::endsWith($dir, '/')) $dir = $dir . '/';
        $dir = preg_replace('/^\/*/', '', $dir); // remove any leading slashes
        $quotedDir = preg_quote($dir, '/');
        $regexp = $deep ? "/^{$quotedDir}.+$/" : "/^{$quotedDir}[^\/]+$/";
        return $files->filter(function ($file) use ($regexp) {
            $result = preg_match($regexp, $file);
            return $result;
        });
    }

    public function slug(string $string): string {
        return $this->piecewise($string, '/', function($pathPart) {
            return $this->piecewise($pathPart, '.', function($part) {
                return Str::slug($part, '-', 'de');
            });
        });
    }

    public function displayName(string $filename): string {
        return preg_replace('/^\d+_/', '', pathinfo($filename,PATHINFO_FILENAME));
    }

    public function sortName(string $filename): string {
        return strtolower(basename($filename));
    }

    protected function piecewise(string $string, string $separator, callable $operation): string {
        return join($separator, array_map($operation, explode($separator, $string)));
    }

    public function files($dirname, $deep = false) {
        return $this->filterWithin(
            $dirname,
            $this->filterPublic(collect(Storage::disk('content')->allFiles())),
            $deep
        );
    }

    public function directories($dirname) {
        return $this->filterWithin(
            $dirname,
            $this->filterPublic(collect(Storage::disk('content')->allDirectories()))
        );
    }
}
