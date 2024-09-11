<?php

namespace Drupal\event_city_manager\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EventController extends ControllerBase
{
    public function __construct(
        private readonly Connection $connection,
    ) {}

    public static function create(ContainerInterface $container): self
    {
        return new self(
            $container->get('database'),
        );
    }

    public function __invoke(): array
    {
        $nids = \Drupal::entityQuery('node')->accessCheck()->condition('type', 'event')->execute();
        $nodes = Node::loadMultiple($nids);

        return [
            '#theme' => 'events',
            '#events' => $nodes,
        ];
    }
}
