<?php /** @noinspection PhpUnused */
namespace CommonPHP\Configuration\Drivers\IniConfigurationDriver;

use CommonPHP\Configuration\Attributes\ConfigurationDriverAttribute;
use CommonPHP\Configuration\Contracts\ConfigurationDriverContract;
use CommonPHP\Configuration\Exceptions\ConfigurationException;
use Override;

/**
 * Class IniConfigurationDriver
 *
 * This class implements the ConfigurationDriverContract interface and provides methods to load and save configuration data
 * from/to INI files.
 *
 * @package CommonPHP\Configuration\Drivers\IniConfigurationDriver
 */
#[ConfigurationDriverAttribute('ini')]
class IniConfigurationDriver implements ConfigurationDriverContract
{

    /**
     * Checks whether the object can be saved.
     *
     * @return bool Returns true if the object can be saved, false otherwise.
     */
    #[Override] function canSave(): bool
    {
        return true;
    }

    /**
     * Loads an INI file and returns its contents as an associative array.
     *
     * @param string $filename The path to the INI file to load.
     * @return array The contents of the INI file as an associative array.
     * @throws ConfigurationException If the file does not appear to be a properly formatted INI file.
     */
    #[Override] function load(string $filename): array
    {
        $result = parse_ini_file($filename);
        if ($result === false)
        {
            throw new ConfigurationException('File does not appear to be a properly formatted INI file: '.$filename);
        }
        return $result;
    }

    /**
     * Saves an array of data to an INI file.
     *
     * @param string $filename The path to the INI file to be saved.
     * @param array $data The data to be saved to the INI file.
     *
     * @return void
     * @throws ConfigurationException
     */
    #[Override] function save(string $filename, array $data): void
    {
        $result = $this->generateIniLines($data);
        $this->writeToFile($filename, $result);
    }

    /**
     * Generate INI lines based on the provided data.
     *
     * @param array $data The data to generate INI lines from.
     * @return array The generated INI lines.
     */
    private function generateIniLines(array $data): array
    {
        $result = [];
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $result[] = "[$key]";
                $result = array_merge($result, $this->generateSubLines($val));
                continue;
            }
            $result[] = $this->generateLine($key, $val);
        }
        return $result;
    }

    /**
     * Generates an array of sublines based on the given subData array.
     *
     * @param array $subData The array containing subData.
     * @return array The array of generated sublines.
     */
    private function generateSubLines(array $subData): array
    {
        $result = [];
        foreach ($subData as $sKey => $sVal) {
            $result[] = $this->generateLine($sKey, $sVal);
        }
        return $result;
    }

    /**
     * Generates a formatted line for a key-value pair.
     *
     * @param string $key The key of the pair.
     * @param string|int $value The value of the pair.
     * @return string The formatted line.
     */
    private function generateLine(string $key, string|int $value): string
    {
        return "$key = " . (is_numeric($value) ? $value : '"' . $value . '"');
    }

    /**
     * Writes an array of lines to a file.
     *
     * @param string $filename The path to the file to write to.
     * @param array $lines The lines to be written to the file.
     * @throws ConfigurationException if an unexpected error occurs while trying to save the file.
     */
    private function writeToFile(string $filename, array $lines): void
    {
        $success = file_put_contents($filename, implode("\r\n", $lines));
        if ($success === false) {
            throw new ConfigurationException('An unexpected error occurred while trying to save INI file: ' . $filename);
        }
    }
}