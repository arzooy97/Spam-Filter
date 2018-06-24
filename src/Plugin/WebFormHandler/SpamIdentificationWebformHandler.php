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
use Drupal\spam_filter\Entity\SpamFilterStorage;

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
    'get_url' => '',
    'submission_url' => '',
    'site_url' => '',
    'field_to_classify' => '',
    ];
  }

  
  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['get_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Spam Identification URL'),
      '#description' => $this->t('The API which checks whether the content is spam or not.'),
      '#default_value' => $this->configuration['get_url'],
      '#required' => TRUE,
    ];

    $form['submission_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Verification URL'),
      '#description' => $this->t('The API where the user sends a post request to submit his own response'),
      '#default_value' => $this->configuration['submission_url'],
      '#required' => TRUE,
    ];

    $form['site_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Site URL'),
      '#description' => $this->t('Address of site'),
      '#default_value' => $this->configuration['site_url'],
      '#required' => TRUE,
    ];
    $form['field_to_classify'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Field to classify'),
      '#description' => $this->t('The field which is to be classified as spam, ham or doubt'),
      '#default_value' => $this->configuration['field_to_classify'],
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
    $data = $webform_submission->getdata();
    $field = $this->configuration['field_to_classify'];
    $webform_id = $this->getWebform()->id();

    $request = $client->post($this->configuration['get_url'], [
      'form_params' => [
        'comment'=> $data[$field],
        'mail'=> \Drupal::currentUser()->getEmail(),
        'url'=> $this->configuration['site_url']
       ]
    ]);
    $response = json_decode($request->getBody(),true);

    $entity_fill = SpamFilterStorage::create([
      'field_classification' => $response['label'],
      'field_classified_by' => $response['classified_by'],
      'field_message' => $data[$field],
      'field_webform_id' => $webform_id
    ]);
    $entity_fill->save();
    
  }
}
