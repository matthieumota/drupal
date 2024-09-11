<?php

namespace Drupal\event_city_manager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

class EventForm extends FormBase
{
    public function getFormId(): string
    {
        return 'event_city_manager_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state): array
    {
        $form['title'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Titre'),
        ];

        $form['created'] = [
            '#type' => 'date',
            '#title' => $this->t('Date de création'),
        ];

        $form['body'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Description'),
        ];

        $cities = \Drupal::database()
            ->select('cities', 'c')
            ->fields('c', ['name'])
            ->execute()
            ->fetchAll(\PDO::FETCH_ASSOC);

        $options = [];

        foreach ($cities as $city) {
            $options[$city['name']] = $city['name'];
        }

        $form['city'] = [
            '#type' => 'select',
            '#title' => $this->t('Ville'),
            '#options' => $options,
        ];

        $form['actions'] = [
            '#type' => 'actions',
            'submit' => [
                '#type' => 'submit',
                '#value' => $this->t('Ajouter'),
            ],
        ];

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state): void
    {
        // @todo Validate the form here.
        // Example:
        // @code
        //   if (mb_strlen($form_state->getValue('message')) < 10) {
        //     $form_state->setErrorByName(
        //       'message',
        //       $this->t('Message should be at least 10 characters.'),
        //     );
        //   }
        // @endcode
    }

    public function submitForm(array &$form, FormStateInterface $form_state): void
    {
        $this->messenger()->addStatus($this->t('Votre événement a bien été ajouté.'));

        Node::create([
            'type' => 'event',
            'title' => $form_state->getValue('title'),
            'body' => $form_state->getValue('body'),
            'field_created' => $form_state->getValue('created'),
            'field_city' => $form_state->getValue('city'),
        ])->save();

        $form_state->setRedirect('<front>');
    }
}
