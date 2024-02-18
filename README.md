# INI Configuration Driver for CommonPHP

This library introduces the INI configuration driver, `IniConfigurationDriver`, as part of the CommonPHP Configuration Management ecosystem. It extends the functionality of CommonPHP by allowing applications to seamlessly load and save configurations using INI files.

## Features

- **Load INI Configurations**: Simplifies the process of reading INI files and converting them into associative arrays for easy access within PHP applications.
- **Save Configurations as INI**: Offers the ability to serialize PHP associative arrays back into INI format, preserving the structure and hierarchies.
- **Structured Error Handling**: Incorporates detailed exception handling to manage potential parsing and file operation errors effectively.
- **Support for Nested Structures**: Through a custom implementation, it supports the representation of nested structures within INI files, providing greater flexibility in configuration management.

## Installation

Use Composer to integrate both the Configuration Manager and the INI driver into your project:

```
composer require comphp/config
composer require comphp/config-ini
```

## Usage

To utilize the INI driver with the Configuration Manager, first ensure the `DriverManager` is configured to recognize the INI driver:

```php
use CommonPHP\Drivers\DriverManager;
use CommonPHP\Configuration\Drivers\IniConfigurationDriver\IniConfigurationDriver;

$driverManager = new DriverManager();
$driverManager->enable(IniConfigurationDriver::class);
```

Upon configuration, the `IniConfigurationDriver` will be automatically used for `.ini` file extensions, thanks to the `#[ConfigurationDriverAttribute('ini')]` annotation.

### Loading a Configuration File

```php
$configManager->loadDriver(IniConfigurationDriver::class);
$config = $configManager->get('path/to/configuration.ini');
```

### Saving a Configuration File

After loading the driver as described above, modifications can be saved back to the INI file:

```php
$config->data['newSection'] = ['newKey' => 'newValue'];
$config->save(); // Persists the changes to 'path/to/configuration.ini'
```

## Exception Handling

The driver includes specific exception handling for common issues such as:

- **ConfigurationException**: Thrown for errors related to INI file parsing or when the file format does not meet the expected structure.
- **General Exceptions**: For file read/write operations or parsing failures.
