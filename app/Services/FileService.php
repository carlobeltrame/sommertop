<?php
namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService {

    public function getContents($dirname, $level = 2): array {
        $topLevelFiles = $this->files($dirname);
        $subsections = $this->directories($dirname);
        return array_merge($this->directoryInfo($dirname), [
            'files' => $topLevelFiles->map(function ($file) { return $this->fileInfo($file); }),
            'subsections' => $subsections->map(function ($subsection) use($level) {
                return $this->getContents($subsection, $level + 1);
            })
        ]);
    }

    public function directoryInfo(string $dir): array {
        return [
            'name' => basename($dir),
            'path' => $this->slug($dir),
        ];
    }

    public function fileInfo(string $file): array {
        return [
            'name' => basename($file),
            'path' => $this->slug($file),
        ];
    }

    public function findDirBySlug(string $slug): ?string {
        return $this->filterPublic(collect(Storage::disk('content')->directories('/')))
            ->first(function($dir) use ($slug) {
                return $this->slug($dir) === $slug;
            });
    }

    public function findFileBySlug(string $dir, string $pathSlug): ?string {
        $fullPathSlug = $this->slug($dir) . '/' . $pathSlug;
        return $this->filterPublic(collect(Storage::disk('content')->files($dir)))
            ->first(function($file) use ($fullPathSlug) {
                return $this->slug($file) === $fullPathSlug;
            });
    }

    public function filterPublic(Collection $files): Collection {
        return $files->filter(function ($file) {
            if (preg_match('/^\.|\/\./', $file)) return false;
            return true;
        });
    }

    public function slug(string $string): string {
        return $this->piecewise($string, '/', function($pathPart) {
            return $this->piecewise($pathPart, '.', function($part) {
                return Str::slug($part, '-', 'de');
            });
        });
    }

    public function piecewise(string $string, string $separator, callable $operation): string {
        return join($separator, array_map($operation, explode($separator, $string)));
    }

    public function files($dirname) {
        return $this->filterPublic(collect(Storage::disk('content')->files($dirname)));
    }

    public function directories($dirname) {
        return $this->filterPublic(collect(Storage::disk('content')->directories($dirname)));
    }
}
