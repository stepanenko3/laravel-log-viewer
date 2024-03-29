<?php

namespace Stepanenko3\LaravelLogViewer;

use Illuminate\Support\Collection;
use Stepanenko3\LaravelLogViewer\Helpers\LogFile;

class LogViewer
{
    public static ?Collection $_cachedFiles = null;

    public static function clearFileCache(): void
    {
        self::$_cachedFiles = null;
    }

    /**
     * @return Collection|LogFile[]
     */
    public function getFiles()
    {
        if (! isset(self::$_cachedFiles)) {
            $files = [];

            foreach (config('log-viewer.include_files', []) as $pattern) {
                $files = array_merge($files, rglob(storage_path() . '/logs/' . $pattern));
            }

            foreach (config('log-viewer.exclude_files', []) as $pattern) {
                $files = array_diff($files, rglob(storage_path() . '/logs/' . $pattern));
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

    public function clearCacheAll(): void
    {
        self::getFiles()->each->clearIndexCache();
    }
}
