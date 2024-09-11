<?php

namespace Drupal\event_city_manager\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\event_city_manager\Form\EventForm;

/**
 * @Block(
 *   id = "event_city_manager_block",
 *   admin_label = @Translation("Block pour ajouter un événement"),
 * )
 */
class EventFormBlock extends BlockBase
{
    public function build()
    {
        return \Drupal::formBuilder()->getForm(EventForm::class);
    }
}
