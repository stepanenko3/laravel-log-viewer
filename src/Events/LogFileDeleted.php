<?php

namespace Stepanenko3\LaravelLogViewer\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Stepanenko3\LaravelLogViewer\Helpers\LogFile;

class LogFileDeleted
{
    use Dispatchable;

    public function __construct(
        public LogFile $file
    ) {
    }
}
