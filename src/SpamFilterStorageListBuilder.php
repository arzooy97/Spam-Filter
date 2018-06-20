<?php

namespace Drupal\spam_filter;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Spam filter storage entities.
 *
 * @ingroup spam_filter
 */
class SpamFilterStorageListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Spam filter storage ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\spam_filter\Entity\SpamFilterStorage */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.spam_filter_storage.edit_form',
      ['spam_filter_storage' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
