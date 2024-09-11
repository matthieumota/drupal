<?php

namespace Drupal\fiofio\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class DeleteForm extends ConfirmFormBase
{
    public function getFormId()
    {
        return 'fiofio_delete_form';
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $id = \Drupal::request()->get('id');

        \Drupal::database()->delete('fiofio')->condition('id', (int) $id)->execute();

        $form_state->setRedirect('fiofio.list');
    }

    public function getQuestion()
    {
        return t('Êtes-vous sûr de vouloir supprimer le %id ?', ['%id' => 10]);
    }

    public function getCancelUrl()
    {
        return new Url('fiofio.list');
    }
}
