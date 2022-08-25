<?php

namespace Stepanenko3\LaravelLogViewer;

use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Stepanenko3\LaravelLogViewer\Helpers\LogFile;

class LogViewer
{
    public static ?Collection $_cachedFiles = null;

    public function rglob($pattern)
    {
        $files = glob($pattern);

        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge(
                [],
                ...[$files, $this->rglob($dir . "/" . basename($pattern))],
            );
        }

        return $files;
    }

    /**
     * @return Collection|LogFile[]
     */
    public function getFiles()
    {
        if (!isset(self::$_cachedFiles)) {
            $files = [];

            foreach (config('log-viewer.include_files', []) as $pattern) {
                $files = array_merge($files, $this->rglob(storage_path() . '/logs/' . $pattern));
            }

            foreach (config('log-viewer.exclude_files', []) as $pattern) {
                $files = array_diff($files, $this->rglob(storage_path() . '/logs/' . $pattern));
            }

            $files = array_reverse($files);
            $files = array_filter($files, 'is_file');

            $files = collect($files ?? [])
                ->unique()
                ->map(fn ($file) => LogFile::fromPath($file));

            switch (config('log-viewer.files_order')) {
                case 'name':
                case 'name_asc':
                    $files = $files->sortBy('name');
                    break;

                case 'name_desc':
                    $files = $files->sortByDesc('name');
                    break;

                case 'size':
                case 'size_desc':
                    $files = $files->sortByDesc(fn ($file) => $file->size());
                    break;

                case 'size_asc':
                    $files = $files->sortBy(fn ($file) => $file->size());
                    break;

                case 'oldest':
                    $files = $files->sortBy(fn ($file) => $file->lastModified());
                    break;

                case 'newest':
                case 'last_modified':
                default:
                    $files = $files->sortByDesc(fn ($file) => $file->lastModified());
            }

            static::$_cachedFiles = $files->values();
        }

        return static::$_cachedFiles;
    }

    public function getFile(string $fileName): ?LogFile
    {
        return $this->getFiles()
            ->where('name', $fileName)
            ->first();
    }

    public function clearCacheAll()
    {
        self::getFiles()->each->clearIndexCache();
    }

    public static function clearFileCache(): void
    {
        self::$_cachedFiles = null;
    }
}
