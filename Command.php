<?php

namespace AtlantisPHP\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class Command extends SymfonyCommand
{
  /**
   * The name of the console command
   *
   * @var string
   */
  private $name;

  /**
   * The list of the console command arguments
   *
   * @var array
   */
  private $commandArguments = [];

  /**
   * @return void
   */
  protected function configure(): void
  {
    $this->getArguments();
    $this->setName($this->name);

    if (isset($this->descriptions[$this->name])) {
      $this->setDescription($this->descriptions[$this->name]);
    }

    if (isset($this->help)) {
      $this->setHelp($this->help);
    }

    foreach($this->commandArguments as $name => $argument) {
      if ($argument['default'] === null) {
        $this->addArgument(
          $argument['value'],
          $argument['type'] == 'req' ? InputArgument::REQUIRED : InputArgument::OPTIONAL,
          isset($this->descriptions[$argument['value']]) ? $this->descriptions[$argument['value']] : ''
        );
      }
      else if ($argument['default'] !== null) {
        $this->addOption(
          $argument['value'],
          null,
          InputArgument::OPTIONAL,
          isset($this->descriptions[$argument['value']]) ? $this->descriptions[$argument['value']] : '',
          $argument['default'] == '' ? null : $argument['default']
        );
      }
    }
  }

  /**
   * Build command arguments and get name
   *
   * @return void
   */
  private function getArguments(): void
  {
    $this->name = trim(preg_replace('/\{(.*?)\}/', '', $this->signature));
    preg_match_all('/\{(.*?)\}/', $this->signature, $match);

    foreach($match[1] as $m) {
      $l = strlen($m);
      if (substr($m, $l - 2, 2) == ':?') {
        $this->commandArguments[] = [
          'value' => substr($m, 0, $l - 2),
          'type' => 'opt',
          'default' => null
        ];
      }
      else if (strstr($m, '=')) {
        $this->commandArguments[] = [
          'value' => explode('=', $m)[0],
          'type' => 'req',
          'default' => explode('=', $m)[1]
        ];
      }
      else {
        $this->commandArguments[] = [
          'value' => $m,
          'type' => 'req',
          'default' => null
        ];
      }
    }
  }
}