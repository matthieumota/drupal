<?php

namespace Drupal\fiofio\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class UserForm extends FormBase
{
    public function getFormId()
    {
        return 'fiofio_user_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['name'] = [
            '#type' => 'textfield',
            '#title' => t('Nom'),
            '#required' => true,
        ];

        $form['email'] = [
            '#type' => 'textfield',
            '#title' => t('Email'),
            '#required' => true,
        ];

        $form['birthday'] = [
            '#type' => 'date',
            '#title' => t('Date de naissance'),
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
        $name = $form_state->getValue('name');
        if (mb_strlen($name) <= 0) {
            $form_state->setErrorByName('name', $this->t('Le nom est invalide'));
        }

        $email = $form_state->getValue('email');
        if (!\Drupal::service('email.validator')->isValid($email)) {
            $form_state->setErrorByName('email', $this->t('L\'email est invalide'));
        }

        $birthday = new DrupalDateTime($form_state->getValue('birthday'));
        if ($birthday->hasErrors()) {
            $form_state->setErrorByName('birthday', $this->t('La date de naissance est invalide'));
        } else if ($birthday->diff(new DrupalDateTime())->y < 18) {
            $form_state->setErrorByName('birthday', $this->t('Vous devez être majeur'));
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->messenger()->addMessage(t('Formulaire envoyé avec @email', ['@email' => $form_state->getValue('email')]));

        $log = "Name: {$form_state->getValue('name')}; Email: {$form_state->getValue('email')}; Date de naissance: {$form_state->getValue('birthday')}";
        $this->logger('default')->info($log);
    }
}
