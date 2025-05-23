<?php
namespace App\Http\Controllers;

use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller {

    public function __construct(protected FileService $files) {}

    public function clearCache(Request $request) {
        if ($request->has('challenge')) {
            header('Content-Type: text/plain');
            header('X-Content-Type-Options: nosniff');
            die($request->query('challenge'));
        }

        Cache::flush();
        return response()->noContent();
    }

    public function list(string $dirSlug) {
        $dir = $this->files->findDirBySlug($dirSlug);
        abort_unless(!!$dir, 404);
        return view('page', [
            'title' => $dir,
            'contents' => $this->files->getContents($dir),
            'slug' => $dirSlug,
        ]);
    }

    public function download(string $dirSlug, string $pathSlug) {
        $dir = $this->files->findDirBySlug($dirSlug);
        abort_unless(!!$dir, 404);
        $file = $this->files->findFileBySlug($dir, $pathSlug);
        abort_unless(!!$file, 404);
        return Storage::disk('content')->download($file);
    }
}
