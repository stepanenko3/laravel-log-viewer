<?php

namespace Stepanenko3\LaravelLogViewer;

use Illuminate\Support\Str;
use Stepanenko3\LaravelLogViewer\Exceptions\InvalidRegularExpression;
use Stepanenko3\LaravelLogViewer\Facades\LogViewer;
use Stepanenko3\LaravelLogViewer\Helpers\LogReader;

class LogFile
{
    public const OLDEST_FIRST = 'asc';
    public const NEWEST_FIRST = 'desc';

    public static function all()
    {
        return LogViewer::getFiles();
    }

    public static function download(string $fileName)
    {
        return LogViewer::getFile($fileName)?->download();
    }

    public static function deleteFile(string $fileName): void
    {
        LogViewer::getFile($fileName)?->delete();
    }

    public static function clearCache(string $fileName): void
    {
        LogViewer::getFile($fileName)?->clearIndexCache();
    }

    public static function get(
        string $selectedFileName = '',
        string $query = '',
        ?array $selectedLevels = null,
        ?int $page = null,
        ?int $perPage = null,
        string $direction = self::NEWEST_FIRST,
    ) {
        if ($selectedLevels === null) {
            $selectedLevels = LogReader::getDefaultLevels();
        }

        $file = LogViewer::getFile($selectedFileName);
        $logQuery = $file?->logs()->only($selectedLevels);

        if ($query && $query !== 'null') {
            try {
                $logQuery?->search($query);

                if (Str::startsWith($query, 'log-index:')) {
                    $logIndex = explode(':', $query)[1];
                }
            } catch (InvalidRegularExpression $exception) {
                $queryError = $exception->getMessage();
            }
        }

        if ($direction === self::NEWEST_FIRST) {
            $logQuery?->reverse();
        }

        if ($perPage === null) {
            $perPage = config('log-viewer.per_page');
        }

        $levels = $logQuery?->getLevelCounts();
        $logs = $logQuery?->paginate($perPage, $page);
        $startTime = defined('LARAVEL_START') ? LARAVEL_START : request()->server('REQUEST_TIME_FLOAT');

        $memoryUsage = number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB';
        $requestTime = number_format((microtime(true) - $startTime) * 1000, 0) . 'ms';

        return [
            'file' => $file,
            'levels' => $levels,
            'logs' => $logs,
            'memoryUsage' => $memoryUsage,
            'requestTime' => $requestTime,
        ];
    }
}
