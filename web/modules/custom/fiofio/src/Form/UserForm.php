<?php

namespace Drupal\fiofio\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\PrependCommand;
use Drupal\Core\Ajax\RemoveCommand;
use Drupal\Core\Ajax\ReplaceCommand;
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
            '#ajax' => [
                'callback' => '::ajaxCallback',
                'event' => 'input', // événement javascript
                'wrapper' => 'output', // id de l'élément qui va recevoir la "sortie" de la requête ajax
                'progress' => [
                    'type' => 'throbber',
                    'message' => $this->t('Chargement...'),
                ],
            ],
        ];

        // La partie AJAX
        $form['message'] = [ // <div id="output">COOL</div> => COOL
            '#type' => 'markup',
            '#markup' => '',
            '#prefix' => '<div id="output">',
            '#suffix' => '</div>',
        ];

        /*if ($email = $form_state->getValue('email')) {
            $form['message']['#markup'] = $email;
        }*/

        $form['birthday'] = [
            '#type' => 'date',
            '#title' => t('Date de naissance'),
            '#required' => true,
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => t('Envoyer'),
            '#ajax' => [
                'callback' => '::ajaxCallback',
                'progress' => [
                    'type' => 'throbber',
                    'message' => $this->t('Chargement...'),
                ],
            ],
        ];

        return $form;
    }

    public function ajaxCallback(array &$form, FormStateInterface $form_state)
    {
        $form['message']['#markup'] = $form_state->getValue('email');

        // Vérifier l'email en BDD...

        $response = new AjaxResponse();
        $response->addCommand(new ReplaceCommand('#output', $form['message']))
            /*->addCommand(new OpenModalDialogCommand('Titre', [
                '#markup' => $form_state->getValue('email'),
                '#attached' => [
                    'library' => ['core/drupal.dialog.ajax'],
                ],
            ]))*/
            ->addCommand(new RemoveCommand('#messages'))
            ->addCommand(new PrependCommand('#fiofio-user-form', [
                '#type' => 'container',
                '#attributes' => ['id' => 'messages'],
                ['#type' => 'status_messages'],
            ]));

        return $response;
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
