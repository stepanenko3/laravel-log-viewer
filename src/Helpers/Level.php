<?php

namespace Stepanenko3\LaravelLogViewer\Helpers;

class Level
{
    public const Debug = 'debug';

    public const Info = 'info';

    public const Notice = 'notice';

    public const Warning = 'warning';

    public const Error = 'error';

    public const Critical = 'critical';

    public const Alert = 'alert';

    public const Emergency = 'emergency';

    public const Processing = 'processing';

    public const Processed = 'processed';

    public const Failed = 'failed';

    public const None = '';

    public string $value;

    public function __construct(?string $value = null)
    {
        $this->value = $value ?? self::None;
    }

    public static function cases(): array
    {
        return [
            self::Debug,
            self::Info,
            self::Notice,
            self::Warning,
            self::Error,
            self::Critical,
            self::Alert,
            self::Emergency,
            self::Processing,
            self::Processed,
            self::Failed,
            self::None,
        ];
    }

    public static function from(?string $value = null): self
    {
        return new self($value);
    }

    public static function caseValues(): array
    {
        return self::cases();
    }

    public function getName(): string
    {
        return match ($this->value) {
            self::None => 'None',
            default => ucfirst($this->value),
        };
    }

    public function getClass(): string
    {
        return match ($this->value) {
            self::Processed => 'success',
            self::Debug, self::Info, self::Notice, self::Processing => 'info',
            self::Warning, self::Failed => 'warning',
            self::Error, self::Critical, self::Alert, self::Emergency => 'danger',
            default => 'none',
        };
    }
}
