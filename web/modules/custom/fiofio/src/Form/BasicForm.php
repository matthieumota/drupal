<?php

namespace Drupal\fiofio\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class BasicForm extends FormBase
{
    public function getFormId()
    {
        return 'fiofio_basic_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['email'] = [
            '#type' => 'textfield',
            '#title' => t('Email'),
            '#required' => true,
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => t('Envoyer'),
        ];

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $email = $form_state->getValue('email');

        if (!\Drupal::service('email.validator')->isValid($email)) {
            $form_state->setErrorByName('email', $this->t('L\'email est invalide'));
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->messenger()->addMessage(t('Formulaire envoyé avec @email', ['@email' => $form_state->getValue('email')]));

        // Ajout en BDD...
        // Envoyer un mail...
    }
}
