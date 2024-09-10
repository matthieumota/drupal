<?php

namespace Drupal\fiofio\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\fiofio\Person;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Block(
 *   id = "fiofio_block",
 *   admin_label = @Translation("Fiofio Block"),
 * )
 */
class FiofioBlock extends BlockBase implements ContainerFactoryPluginInterface
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

        return [
            '#theme' => 'my_template',
            '#firstname' => $this->person->present($name, '2019-12-31', $config->get('upper')),
        ];
    }

    protected function blockAccess(AccountInterface $account)
    {
        // return AccessResult::forbiddenIf(true);
        return AccessResult::allowedIfHasPermission($account, 'view fiofio');
    }
}
