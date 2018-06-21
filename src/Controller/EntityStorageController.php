<?php

namespace Drupal\spam_filter\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityInterface;
use \Drupal\Core\Entity\Query\QueryInterface;
use Drupal\Core\Url;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntityStorageController extends ControllerBase {
  
  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function SpamList() {

    $header = [
      $this->t('Message'),
      $this->t('Classified By'),
      $this->t('Classification'),
    ];

    $query = \Drupal::entityQuery('spam_filter_storage')
      ->execute();

    $build['admin_spam_filter_list_table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No result available.'),
    ];
    $build['admin_spam_filter_list_pager'] = ['#theme' => 'pager'];
    return $build;
  }

}
