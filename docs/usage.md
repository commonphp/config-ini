# Usage

`comphp/config-ini` provides `CommonPHP\Drivers\Config\INI\IniConfigurationDriver` for INI configuration files.

## Encode and Decode

```php
use CommonPHP\Drivers\Config\INI\IniConfigurationDriver;

$driver = new IniConfigurationDriver();

$config = [
    'name' => 'demo',
    'database' => [
        'host' => 'localhost',
    ],
];

$data = $driver->encode($config);
$decoded = $driver->decode($data);
```

## Read and Write

```php
$driver->write(__DIR__ . '/config.ini', $config);
$config = $driver->read(__DIR__ . '/config.ini');
```

## Notes

The driver supports scalar top-level values and one level of INI sections. Deeper nested arrays are rejected during encoding. Typed values are parsed through `INI_SCANNER_TYPED`.

Failures throw CommonPHP config exceptions instead of returning `false`.
