<?php

namespace Stepanenko3\LaravelLogViewer\Helpers;

use Stepanenko3\LaravelLogViewer\Events\LogFileDeleted;

class LogFile
{
    public function __construct(
        public string $name,
        public string $path,
    ) {
    }

    public static function fromPath(string $filePath): LogFile
    {
        return new self(
            self::relativePath(storage_path('logs'), $filePath),
            $filePath,
        );
    }

    public static function relativePath($from, $to, $separator = DIRECTORY_SEPARATOR)
    {
        $from = str_replace(array('/', '\\'), $separator, $from);
        $to = str_replace(array('/', '\\'), $separator, $to);

        $arFrom = explode($separator, rtrim($from, $separator));
        $arTo = explode($separator, rtrim($to, $separator));

        while (count($arFrom) && count($arTo) && ($arFrom[0] == $arTo[0])) {
            array_shift($arFrom);
            array_shift($arTo);
        }

        return str_pad('', count($arFrom) * 3, '..' . $separator) . implode($separator, $arTo);
    }

    public function logs(): LogReader
    {
        return LogReader::instance($this);
    }

    public function size(): int
    {
        return filesize($this->path);
    }

    public function lastModified(): int
    {
        return filemtime($this->path);
    }

    public function sizeFormatted(): string
    {
        $size = $this->size();

        if ($size > ($gb = 1024 * 1024 * 1024)) {
            return number_format($size / $gb, 2) . ' GB';
        } elseif ($size > ($mb = 1024 * 1024)) {
            return number_format($size / $mb, 2) . ' MB';
        } elseif ($size > ($kb = 1024)) {
            return number_format($size / $kb, 2) . ' KB';
        }

        return $size . ' bytes';
    }

    public function download()
    {
        return response()->download($this->path);
    }

    public function clearIndexCache(): void
    {
        $this->logs()->clearIndexCache();
    }

    public function delete()
    {
        unlink($this->path);
        $this->clearIndexCache();
        LogFileDeleted::dispatch($this);
    }
}
