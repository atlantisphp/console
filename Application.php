<?php

namespace AtlantisPHP\Console;

use AtlantisPHP\Console\ClassName;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\Command as CommandTemplate;

class Application extends SymfonyApplication
{
  /**
   * Auto load all commands
   *
   * @param  string  $path
   * @param  boolean $require
   * @return void
   */
  public function load(string $path, $require = false): void
  {
    foreach(glob($this->cleanPath($path) . "*.php") as $command) {
      if ($require) {
        require_once $command;
      }

      $class = ClassName::command($command);

      if (class_exists($class)) {
        $class = new $class;

        if ($class instanceof CommandTemplate) {
          $this->add($class);
        }
      }
    }
  }

  /**
   * Clean path
   *
   * @param  string $path
   * @return string $path
   */
  private function cleanPath($path)
  {
    $path = substr($path, 0, 1) == '/' ? substr($path, 1, strlen($path) - 1) : $path;
    $path = substr($path, strlen($path) - 1, 1) == '/' ? substr($path, 0, strlen($path) - 1) : $path;

    $path = substr($path, 0, 1) == '\\' ? substr($path, 1, strlen($path) - 1) : $path;
    $path = substr($path, strlen($path) - 1, 1) == '\\' ? substr($path, 1, strlen($path) - 1) : $path;

    $path = preg_replace('#' . DIRECTORY_SEPARATOR . '+#', DIRECTORY_SEPARATOR, $path);
    return $path . DIRECTORY_SEPARATOR;
  }
}