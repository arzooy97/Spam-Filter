<?php

/**
 * @file
 * Contains spam_filter_storage.page.inc.
 *
 * Page callback for Spam filter storage entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Spam filter storage templates.
 *
 * Default template: spam_filter_storage.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_spam_filter_storage(array &$variables) {
  // Fetch SpamFilterStorage Entity Object.
  $spam_filter_storage = $variables['elements']['#spam_filter_storage'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
