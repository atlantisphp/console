# Console

## Introduction
Atlantis Console is a Command Line Interface Tool built on top of Symfony's Console Application.

## Getting Started
Require the package
```
composer require atlantisphp/console
```

*console file in the `root` directory*
```
<?php

use AtlantisPHP\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$application = new Application('My Application');
$application->load(__DIR__ . '/Commands'); // if commands fail to register, add "true" after the first argument: ->load(__DIR__ . '/Commands', true);
$application->run();
```

*NewUser.php command file in the `Commands` directory*
```
<?php

namespace App\Commands;

use AtlantisPHP\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NewUser extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:create-user {username}';

  /**
   * The full command description.
   *
   * @var string
   */
  protected $help = 'This command allows you to create a user...';

  /**
   * The descriptions of the console commands.
   *
   * @var array
   */
  protected $descriptions = [
    'app:create-user' => 'Create a new user',
    'username' => 'Username of the new user'
  ];

  /**
   * @param InputInterface  $input
   * @param OutputInterface $output
   *
   * @return void
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $output->writeln('Succesfully created user "' . $input->getArgument('username') . '".');
  }

}
```

Now run the following command
```
php console app:create-user donaldpakkies
```

## Application

### load
| Argument      | Type   | Description  |
| ------------- | -------| ------------ |
| directory     | String | The directory where commands are stored |
| require     | Boolean | Require commands (default: false) |

Example:
```
use AtlantisPHP\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$application = new Application('My Application');
$application->load(__DIR__ . '/Commands');
$application->run();
```

## Command

### Variables
| Name      | Type   | Description  |
| ------------- | -------| ------------ |
| signature     | String | The name and signature of the console command. |
| help     | String | The full command description (optional). |
| descriptions | Array | The descriptions of the console commands (optional). |

For more information, visit: https://symfony.com/doc/current/console