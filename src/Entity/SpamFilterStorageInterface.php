<?php

namespace Drupal\spam_filter\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Spam filter storage entities.
 *
 * @ingroup spam_filter
 */
interface SpamFilterStorageInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Spam filter storage name.
   *
   * @return string
   *   Name of the Spam filter storage.
   */
  public function getName();

  /**
   * Sets the Spam filter storage name.
   *
   * @param string $name
   *   The Spam filter storage name.
   *
   * @return \Drupal\spam_filter\Entity\SpamFilterStorageInterface
   *   The called Spam filter storage entity.
   */
  public function setName($name);

  /**
   * Gets the Spam filter storage creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Spam filter storage.
   */
  public function getCreatedTime();

  /**
   * Sets the Spam filter storage creation timestamp.
   *
   * @param int $timestamp
   *   The Spam filter storage creation timestamp.
   *
   * @return \Drupal\spam_filter\Entity\SpamFilterStorageInterface
   *   The called Spam filter storage entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Spam filter storage published status indicator.
   *
   * Unpublished Spam filter storage are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Spam filter storage is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Spam filter storage.
   *
   * @param bool $published
   *   TRUE to set this Spam filter storage to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\spam_filter\Entity\SpamFilterStorageInterface
   *   The called Spam filter storage entity.
   */
  public function setPublished($published);

}
