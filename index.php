<?php
/**
 * App main file
 * 
 * @category   Main
 * @package    App
 * @author     Ricardo Striquer Soares <rstriquer.gmail>
 * @license    https://github.com/rstriquer/php-ps4-sample/blob/master/LICENSE
 * @version    Release: @package_version@
 */

use DI\ContainerBuilder;
use FastRoute\RouteCollector;
use App\Controllers\QuestionsController;
use App\Session;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

if (getenv('APP_DEBUG')) {
    ini_set('display_errors', 'On');
    ini_set('ignore_repeated_errors', 'On');
    ini_set('html_errors', 'On');
    error_reporting(E_ALL|E_STRICT);
}

Session::start();

$containerBuilder = new ContainerBuilder;
$container = $containerBuilder->build();

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('POST', '/start', [QuestionsController::class, 'startRound']);
    $r->addRoute('POST', '/yes', [QuestionsController::class, 'getYes']);
    $r->addRoute('POST', '/no', [QuestionsController::class, 'getNo']);
    $r->addRoute('GET', '/', [QuestionsController::class, 'initGame']);
    $r->addRoute('POST', '/add', [QuestionsController::class, 'addLink']);
    $r->addRoute('GET', '/knowledge', [QuestionsController::class, 'showKnowledge']);
});

$route = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

switch ($route[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
    echo '404 Not Found';
    break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo '405 Method Not Allowed';
        break;

    case FastRoute\Dispatcher::FOUND:
        $controller = $route[1];
        $parameters = $route[2];

        $container->call($controller, $parameters);
        break;
}
