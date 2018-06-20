<?php

namespace Drupal\spam_filter;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Spam filter storage entity.
 *
 * @see \Drupal\spam_filter\Entity\SpamFilterStorage.
 */
class SpamFilterStorageAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\spam_filter\Entity\SpamFilterStorageInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished spam filter storage entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published spam filter storage entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit spam filter storage entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete spam filter storage entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add spam filter storage entities');
  }

}
