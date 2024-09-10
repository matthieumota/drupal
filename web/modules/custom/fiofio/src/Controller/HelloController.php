<?php

namespace Drupal\fiofio\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\fiofio\Person;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class HelloController extends ControllerBase
{
    public function __construct(private Person $person)
    {
    }

    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('fiofio.person')
            // ou bien
            // \Drupal::service('fiofio.generator')
        );
    }

    public function __invoke(string $name, string $birthday)
    {
        return new Response(
            $this->person->present($name, $birthday)
        );
    }
}
