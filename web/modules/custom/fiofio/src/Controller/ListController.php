<?php

namespace Drupal\fiofio\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

class ListController extends ControllerBase
{
    public function index()
    {
        $database = \Drupal::database();
        $query = $database->select('fiofio', 'f');
        $query->join('users_field_data', 'u', 'f.uid = u.uid');
        $query->addField('f', 'id');
        $query->addField('f', 'email');
        $query->addField('u', 'name');
        $query->addField('f', 'created_at');

        $rows = $query->execute()->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            $row[] = ['data' => [
                '#type' => 'dropbutton',
                '#links' => [
                    'edit' => [
                        'title' => 'Modifier',
                        'url' => Url::fromRoute('fiofio.list'),
                    ],
                    'delete' => [
                        'title' => 'Supprimer',
                        'url' => Url::fromRoute('fiofio.delete', ['id' => $row['id']]),
                        'attributes' => [
                            'class' => ['use-ajax'],
                            'data-dialog-type' => 'modal',
                            'data-dialog-options' => Json::encode([
                                'width' => '800px',
                            ]),
                        ],
                    ],
                ],
                '#attached' => ['library' => ['core/drupal.dialog']],
            ]];
        }

        return [
            '#theme' => 'table',
            '#header' => ['ID', 'Email', 'Utilisateur', 'Date de création', 'Options'],
            '#rows' => $rows,
            '#empty' => t('Pas de données'),
        ];
    }
}
