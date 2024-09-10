<?php

use Drupal\Core\Form\FormStateInterface;

function fiorella_form_system_theme_settings_alter(&$form, FormStateInterface $form_state) {
    dump(theme_get_setting(''));

    $form['settings'] = [
        '#type' => 'details',
        '#open' => true,
        '#title' => 'Réglages Fiorella',
        '#summary_attributes' => ['class' => 'toto'],
        'setting1' => [
            '#type' => 'textfield',
            '#title' => 'Exemple',
            '#description' => 'Un exemple de réglage',
            '#default_value' => theme_get_setting('setting1'),
        ],
        'setting2' => [
            '#type' => 'checkbox',
            '#title' => 'Exemple',
            '#description' => 'Un exemple de réglage',
            '#default_value' => theme_get_setting('setting2'),
        ],
    ];

    $form['#validate'][] = 'fiorella_validate_form';
}

function fiorella_validate_form(&$form, FormStateInterface $form_state) {
    if ($form_state->getValue('setting1') === 'toto') {
        $form_state->setErrorByName('setting1', 'Toto est une valeur interdite');
    }
}

