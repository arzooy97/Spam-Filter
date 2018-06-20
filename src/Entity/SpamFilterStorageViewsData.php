<?php

namespace Drupal\spam_filter\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Spam filter storage entities.
 */
class SpamFilterStorageViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
