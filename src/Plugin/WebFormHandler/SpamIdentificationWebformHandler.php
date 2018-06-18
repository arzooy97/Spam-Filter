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
 *   label = @Translation("Submit Handler"),
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
    return parent::defaultConfiguration() + [
      'send' => '[date:html_date]',
      'days' => '',
      'unschedule' => FALSE,
    ];
  }

  
  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    
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
  $request = $client->post('http://205.147.99.79:3000/classify', [
    'form_params' => [
      'comment'=> 'Free entry in 2 a wkly comp to win FA Cup final tkts 21st May 2005. Text FA to 87121 to receive entry question(std txt rate)T&C',
      'mail'=> \Drupal::currentUser()->getEmail(),
      'url'=>'https://opensenselabs.com'
    ]
  ]);
  $response = json_decode($request->getBody(),true);

  echo $response["label"];

  die();
    
  }
}
