<?php

namespace Drupal\spam_filter\Plugin\WebformHandler;

use Drupal\Component\Utility\Unicode;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\webform\Element\WebformMessage;
use Drupal\webform\Element\WebformOtherBase;
use Drupal\webform\Plugin\WebformHandler\EmailWebformHandler;
use Drupal\webform\Utility\WebformDateHelper;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform_scheduled_email\WebformScheduledEmailManagerInterface;

/**
 * Schedules a webform submission's email.
 *
 * @WebformHandler(
 *   id = "submit",
 *   label = @Translation("Spam Identification"),
 *   category = @Translation("Notification"),
 *   description = @Translation("Spam identification."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_REQUIRED,
 * )
 */
class SpamIdentificationWebformHandler extends EmailWebformHandler {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() +[
    'url' => 'http://205.147.99.79:3000/classify',
  ];
  }

  
  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['url'] = [
    '#type' => 'textfield',
    '#title' => $this->t('Spam Identification URL'),
    '#description' => $this->t('The API which checks whether the content is spam or not.'),
    '#default_value' => $this->configuration['url'],
    '#required' => TRUE,
  ];
  return $form;
    
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {

    
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
    $client = \Drupal::httpClient();
    $temp= ($webform_submission->data)['message'];
    $request = $client->post($this->configuration['url'], [
    'form_params' => [
      'comment'=> $temp,
      'mail'=> \Drupal::currentUser()->getEmail(),
      'url'=>'https://opensenselabs.com'
    ]
  ]);
  $response = json_decode($request->getBody(),true);

  echo $response["classified_by"];
  echo $response["label"];
  die();
    
  }
}
