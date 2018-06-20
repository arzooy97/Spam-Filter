<?php

namespace Drupal\spam_filter\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityInterface;

class EntityStorageController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   */
  public function content() {

    $storage = \Drupal::entityTypeManager()->getStorage('spam_filter_storage');
    $uids = \Drupal::entityQuery('spam_filter_storage')
          ->execute();
    $entities = $storage->loadMultiple($uids);
    $view_builder = \Drupal::entityTypeManager()->getViewBuilder('spam_filter_storage');

    $counter = 0;
   
    foreach($entities as $entity) {
      $output[$counter] = $view_builder->view($entity);
      $counter++;
    }

    return $output;
  }

}
