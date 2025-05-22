<?php
namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageController extends Controller {
    public function list(string $dirSlug) {
        $dir = $this->findDirBySlug($dirSlug);
        return view('page', [
            'title' => $dir,
            'contents' => $this->getContents($dir)->map(function ($item) {
                return [
                    'name' => basename($item),
                    'path' => $this->slug($item),
                ];
            }),
            'slug' => $dirSlug,
        ]);
    }

    public function download(string $dirSlug, string $pathSlug) {
        $dir = $this->findDirBySlug($dirSlug);
        abort_unless(!!$dir, 404);
        $file = $this->findFileBySlug($dir, $pathSlug);
        abort_unless(!!$file, 404);
        return Storage::disk('content')->download($file);
    }

    private function getContents($dirname): Collection {
        return $this->filterPublic(
            collect(Storage::disk('content')->directories($dirname))
                ->concat(Storage::disk('content')->files($dirname))
        );
    }

    private function findDirBySlug(string $slug): ?string {
        return $this->filterPublic(collect(Storage::disk('content')->directories('/')))
            ->first(function($dir) use ($slug) {
                return $this->slug($dir) === $slug;
            });
    }

    private function findFileBySlug(string $dir, string $pathSlug): ?string {
        $fullPathSlug = $this->slug($dir) . '/' . $pathSlug;
        return $this->filterPublic(collect(Storage::disk('content')->files($dir)))
            ->first(function($file) use ($fullPathSlug) {
                return $this->slug($file) === $fullPathSlug;
            });
    }

    private function filterPublic(Collection $files): Collection {
        return $files->filter(function ($file) {
           if (preg_match('/^\.|\/\./', $file)) return false;
           return true;
        });
    }

    private function slug(string $string): string {
        return $this->piecewise($string, '/', function($pathPart) {
            return $this->piecewise($pathPart, '.', function($part) {
                return Str::slug($part, '-', 'de');
            });
        });
    }

    private function piecewise(string $string, string $separator, callable $operation): string {
        return join($separator, array_map($operation, explode($separator, $string)));
    }
}
