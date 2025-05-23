<?php

namespace App\View\Composers;

use App\Services\FileService;
use Illuminate\View\View;

class NavigationViewComposer
{
    /**
     * Create a new profile composer.
     */
    public function __construct(
        protected FileService $files,
    ) {}

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $view->with(
            'menuEntries',
            $this->files->directories('/')
                ->map(function($dir) {
                    return $this->files->directoryInfo($dir);
                })
        );
        $view->with(
            'activePage',
            request()->path()
        );
    }
}
