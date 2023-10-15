<?php

namespace Monkeyslegion\Env;

class env {
  /**
   * Load environment variables from a .env file.
   *
   * @param string $filePath Path to .env file
   */
    public static function load(string $filePath): void {
        if (!file_exists($filePath)) {
          throw new \InvalidArgumentException("File not found: {$filePath}");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
          throw new \RuntimeException("Unable to read file: {$filePath}");
        }

        foreach ($lines as $line) {
          if (str_starts_with($line, '#')) {
            continue;
          }

          list($name, $value) = explode('=', $line, 2);
          $name = trim($name);
          $value = trim($value);

          if (!putenv("{$name}={$value}")) {
            throw new \RuntimeException("Unable to set environment variable: {$name}");
          }

          $_ENV[$name] = $value;
          $_SERVER[$name] = $value;
        }
    }

    /**
     * Get the value of an environment variable.
     *
     * @param string $name Name of environment variable
     * @param mixed|null $default Default value to return if environment variable does not exist
     * @return mixed
     */
    public static function get(string $name, mixed $default = null): mixed {
        $value = getenv($name);

        return $value !== false ? $value : $default;
    }
}
