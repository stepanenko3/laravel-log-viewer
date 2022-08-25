# Laravel Log Viewer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stepanenko3/laravel-log-viewer.svg?style=flat-square)](https://packagist.org/packages/stepanenko3/laravel-log-viewer)
[![Total Downloads](https://img.shields.io/packagist/dt/stepanenko3/laravel-log-viewer.svg?style=flat-square)](https://packagist.org/packages/stepanenko3/laravel-log-viewer)
[![License](https://poser.pugx.org/stepanenko3/laravel-log-viewer/license)](https://packagist.org/packages/stepanenko3/laravel-log-viewer)

## Description

[Log Viewer](https://github.com/opcodesio/log-viewer) version without UI and with some modifications

## Features

- Without UI (Without frontend)
- Support subfolders
- Files sorting by name, size, modification time
- Using [custom pagination](https://github.com/stepanenko3/laravel-pagination)

## Requirements

- `php: >=8.0`

## Installation

```bash
composer require stepanenko3/laravel-log-viewer
```

## Usage

``` php
use Stepanenko3\LaravelLogViewer\LogViewer;
use Stepanenko3\LaravelLogViewer\LogFile;

// Getting all log files
LogViewer::getFiles();

// Get specific log file by name
LogViewer::getFile('laravel.log');

// Clear cache of all files
LogViewer::clearCacheAll();

// Download specific log file by name
LogFile::download('laravel.log');

// Delete specific log file by name
LogFile::delete('laravel.log');

// Clear cache for specific log file by name
LogFile::clearCache('laravel.log');

// Query logs
LogFile::get(
    selectedFileName: 'laravel.log', // File name
    query: 'exception[0-9]+', // Filter logs by regex query string
    selectedLevels: ['warning', 'alert'], // Filter logs by level
    page: 2, // Page
    perPage: 25, // Logs per page
    direction: LogFile::NEWEST_FIRST, // Logs order. NEWEST_FIRST::OLDEST_FIRST,
);
```

## Credits

- [Artem Stepanenko](https://github.com/stepanenko3)
- [Arunas Skirius](https://github.com/arukompas)

## Contributing

Thank you for considering contributing to this package! Please create a pull request with your contributions with detailed explanation of the changes you are proposing.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).
