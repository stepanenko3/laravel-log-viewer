<?php

namespace Stepanenko3\LaravelLogViewer\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Stepanenko3\LaravelLogViewer\LogFile;

/**
 * @see \Stepanenko3\LaravelLogViewer\LogViewer
 *
 * @method static Collection|LogFile[] getFiles()
 * @method static LogFile|null getFile(string $fileName)
 * @method static void clearFileCache()
 * @method static array getRouteMiddleware()
 * @method static string getRoutePrefix()
 */
class LogViewer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Stepanenko3\LaravelLogViewer\LogViewer::class;
    }
}
