<?php

declare(strict_types=1);

namespace CommonPHP\Drivers\Config\INI;

use CommonPHP\Config\Contracts\AbstractConfigDriver;
use CommonPHP\Config\Exceptions\ConfigException;
use CommonPHP\Config\Exceptions\ConfigValidationException;
use Throwable;

final class IniConfigurationDriver extends AbstractConfigDriver
{
    public function validate(string $data): bool
    {
        try {
            $this->decode($data);

            return true;
        } catch (Throwable) {
            return false;
        }
    }

    public function encode(array $config): string
    {
        $lines = [];

        foreach ($config as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            $lines[] = $this->encodeEntry((string) $key, $value);
        }

        foreach ($config as $section => $values) {
            if (!is_array($values)) {
                continue;
            }

            if ($lines !== []) {
                $lines[] = '';
            }

            $lines[] = '[' . $this->escapeSectionName((string) $section) . ']';

            foreach ($values as $key => $value) {
                if (is_array($value)) {
                    throw new ConfigValidationException(
                        'INI configuration does not support nested arrays beyond one section level.'
                    );
                }

                $lines[] = $this->encodeEntry((string) $key, $value);
            }
        }

        return implode(PHP_EOL, $lines) . PHP_EOL;
    }

    public function decode(string $data): array
    {
        $error = null;

        set_error_handler(static function (int $severity, string $message) use (&$error): bool {
            $error = $message;

            return true;
        });

        try {
            $decoded = parse_ini_string($data, true, INI_SCANNER_TYPED);
        } finally {
            restore_error_handler();
        }

        if ($decoded === false) {
            throw new ConfigValidationException(
                'Invalid INI configuration data' . ($error !== null ? ': ' . $error : '.')
            );
        }

        return $decoded;
    }

    private function encodeEntry(string $key, mixed $value): string
    {
        return $this->escapeKey($key) . ' = ' . $this->encodeValue($value);
    }

    private function encodeValue(mixed $value): string
    {
        if (is_string($value)) {
            return '"' . addcslashes($value, "\\\"") . '"';
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($value === null) {
            return 'null';
        }

        throw new ConfigException('Unsupported INI configuration value type: ' . get_debug_type($value));
    }

    private function escapeKey(string $key): string
    {
        if ($key === '') {
            throw new ConfigValidationException('INI configuration keys cannot be empty.');
        }

        if (str_contains($key, "\n") || str_contains($key, "\r")) {
            throw new ConfigValidationException('INI configuration keys cannot contain new lines.');
        }

        return $key;
    }

    private function escapeSectionName(string $section): string
    {
        if ($section === '') {
            throw new ConfigValidationException('INI section names cannot be empty.');
        }

        if (
            str_contains($section, '[')
            || str_contains($section, ']')
            || str_contains($section, "\n")
            || str_contains($section, "\r")
        ) {
            throw new ConfigValidationException('Invalid INI section name: ' . $section);
        }

        return $section;
    }
}