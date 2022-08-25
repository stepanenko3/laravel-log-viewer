<?php

namespace Stepanenko3\LaravelLogViewer\Helpers;

class LevelCount
{
    public function __construct(
        public Level $level,
        public int $count = 0,
        public bool $selected = false,
    ) {
    }
}
