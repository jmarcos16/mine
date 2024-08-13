### Mine router

This is a simple router for PHP. It is a project that allows you to create routes using the PHP Attributes, it also allows you to pass parameters and Class that will be executed when the route is called, we also use the PSR-4 standard to autoload and some Libraries to help in the development of the project, such as: [PHP-DI](https://php-di.org/) and [Symfony HttpFoundation](https://symfony.com/doc/current/components/http_foundation.html).

### Requirements

- PHP 8.0 or higher
- Composer

### Installation

```bash
composer install jmarcos161/mine-router
```

### Usage

In the `public/index.php` file, you must include the `vendor/autoload.php` file and the `Router` class, as shown below:

```php
require __DIR__ . '/../../vendor/autoload.php';

use MiniRouter\Router;
use Symfony\Component\HttpFoundation\Request;
use Src\Controllers\YourController;

$routes = [
    YourController::class,
];

$router = new Router($routes);

$router->handle(Request::createFromGlobals());
```

In the `YourController` class, you must create a method with the `#[Route]` attribute, as shown below:

```php
use MiniRouter\Attributes\Route;

class YourController
{
    #[Route('/index')]
    public function index()
    {
        return echo 'Hello World';
    }
}
```

