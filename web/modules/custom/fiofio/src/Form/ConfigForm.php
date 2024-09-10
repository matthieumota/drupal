<?php

namespace Drupal\fiofio\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ConfigForm extends ConfigFormBase
{
    protected function getEditableConfigNames()
    {
        return ['fiofio.settings'];
    }

    public function getFormId()
    {
        return 'fiofio_config_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('fiofio.settings');
        // $config = \Drupal::state();

        $form['fiofio'] = [
            '#type' => 'details',
            '#title' => $this->t('Détails'),
            '#open' => true,
            'message' => [
                '#type' => 'textarea',
                '#title' => $this->t('Message'),
                '#default_value' => $config->get('message') ?? 'fio',
                // '#default_value' => $config->get('fiofio.settings')['message'] ?? 'fio',
            ],

            'upper' => [
                '#type' => 'checkbox',
                '#title' => $this->t('Prénom en majuscule'),
                '#default_value' => $config->get('upper') ?? false,
            ],
        ];

        return parent::buildForm($form, $form_state);
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        if ($form_state->isValueEmpty('message')) {
            $form_state->setErrorByName('message', $this->t('Le message est obligatoire'));
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        parent::submitForm($form, $form_state);

        $config = $this->config('fiofio.settings');

        $config->set('message', $form_state->getValue('message'));
        $config->set('upper', (bool) $form_state->getValue('upper'));

        $config->save();
        // \Drupal::state()->set('fiofio.settings', array_merge(\Drupal::state()->get('fiofio.settings') ?? [], ['message' => $form_state->getValue('message')]));
    }
}
