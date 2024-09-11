<?php

namespace Drupal\fiofio\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Security\TrustedCallbackInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\fiofio\Person;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Block(
 *   id = "fiofio_block",
 *   admin_label = @Translation("Fiofio Block"),
 * )
 */
class FiofioBlock extends BlockBase implements ContainerFactoryPluginInterface, TrustedCallbackInterface
{
    public function __construct(
        array $configuration,
        $plugin_id,
        $plugin_definition,
        private ConfigFactory $config,
        private Person $person
    ) {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
    }

    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('config.factory'),
            $container->get('fiofio.person')
        );
    }

    public function build()
    {
        // $config = \Drupal::config('fiofio.settings');
        $config = $this->config->get('fiofio.settings');
        $name = $config->get('message');

        $texts = ['A', 'B', 'C'];

        return [
            'cache' => [
                '#type' => 'markup',
                '#markup' => $texts[array_rand($texts)],
            ],
            'text' => [
                '#lazy_builder' => [self::class.'::renderText', []],
                '#create_placeholder' => true,
            ],
        ];

        return [
            '#theme' => 'my_template',
            '#firstname' => $this->person->present($name, '2019-12-31', $config->get('upper')),
        ];
    }

    public static function renderText()
    {
        $texts = ['A', 'B', 'C'];

        return [
            '#type' => 'markup',
            '#markup' => $texts[array_rand($texts)],
        ];
    }

    public static function trustedCallbacks() {
        return [
            'renderText',
        ];
    }

    protected function blockAccess(AccountInterface $account)
    {
        // return AccessResult::forbiddenIf(true);
        return AccessResult::allowedIfHasPermission($account, 'view fiofio');
    }
}
