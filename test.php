<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Yiisoft\Di\Container;
use Yiisoft\EventDispatcher\Dispatcher\Dispatcher;
use Yiisoft\EventDispatcher\Provider\Provider;
use Yiisoft\Yii\Console\Config\EventConfigurator;

require_once __DIR__ . '/vendor/autoload.php';

class Event {}

class EventHandler {
    public int $count = 0;

    public function handle(Event $event): void
    {
        $this->count++;
    }
}

$config = [
    ContainerInterface::class => fn(ContainerInterface $container) => $container,
    ListenerProviderInterface::class => Provider::class,
];
$container = new Container($config);
$handler = $container->get(EventHandler::class);
$configurator = $container->get(EventConfigurator::class);
$configurator->registerListeners([Event::class => [[$handler, 'handle']]]);
$dispatcher = $container->get(Dispatcher::class);

$event = new Event();
$dispatcher->dispatch($event);

echo $handler->count . "\n";
