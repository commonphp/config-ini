# CommonPHP INI Config Driver

Configuration driver for CommonPHP that encodes and decodes INI configuration data.

## Requirements

- PHP `^8.5`
- `comphp/config:^0.3`

## Installation

Once this package is available through your Composer repositories, install it with:

```bash
composer require comphp/config-ini
```

## Usage

```php
<?php

use CommonPHP\Drivers\Config\INI\IniConfigurationDriver;

$driver = new IniConfigurationDriver();

$config = [
    'app' => 'demo',
    'debug' => true,
    'database' => [
        'host' => 'localhost',
    ],
];

$ini = $driver->encode($config);
$decoded = $driver->decode($ini);

$driver->write(__DIR__ . '/config.ini', $config);
$fromFile = $driver->read(__DIR__ . '/config.ini');
```

## Format Notes

This driver uses PHP's INI parser with typed scanning. Scalar values are supported at the top level, and one level of sections is supported for grouped values. Nested arrays beyond one section level are not supported by INI and are rejected during encoding.

## Error Handling

Read, write, parse, validation, and unsupported value failures throw CommonPHP config exceptions such as `ConfigReadException`, `ConfigWriteException`, `ConfigValidationException`, or `ConfigException`.

## Documentation

- [Usage](docs/usage.md)
- [Testing](TESTING.md)
- [Contributing](CONTRIBUTING.md)
- [Security](SECURITY.md)

## License

MIT. See [LICENSE.md](LICENSE.md).
