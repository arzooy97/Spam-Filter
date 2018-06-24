<?php

namespace Drupal\spam_filter\Form;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\domain\DomainLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configuration page for Spam Filter settings.
 */
class SpamFilterForm extends ConfigFormBase {

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The Domain loader.
   *
   * @var \Drupal\domain\DomainLoader
   */
  protected $domainLoader;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(ModuleHandlerInterface $module_handler, DomainLoader $domain_loader = NULL, LanguageManagerInterface $language_manager = NULL) {
    $this->moduleHandler = $module_handler;
    if ($domain_loader) {
      $this->domainLoader = $domain_loader;
    }
    if ($language_manager) {
      $this->languageManager = $language_manager;
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $domainServices = NULL;
    $languageServices = NULL;
    if ($container->has('domain.loader')) {
      $domainServices = $container->get('domain.loader');
    }
    if ($container->has('language_manager')) {
      $languageServices = $container->get('language_manager');
    }
    return new static(
      $container->get('module_handler'),
      $domainServices,
      $languageServices
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'spam_filter_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'spam_filter.settings',
    ];
  }

  /**
   * {@inheritdoc}
   *
   * Implements admin settings form.
   *
   * @param array $form
   *   From render array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current state of form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    // Fetch configurations if saved.
    $config = $this->config('spam_filter.settings');

    // Create headers for table.
    $header = [
      $this->t('Webform ID'),
      $this->t('Message'),
      $this->t('Classification'),
      $this->t('Classified By'),
      $this->t('Verify'),
      $this->t('Operation'),

    ];

    // Set table values on Add/Remove or on page load.
    $spt_table = $form_state->get('spt_table');
    if (empty($spt_table)) {
      // Set data from configuration on page load.
      // Set empty element if no configurations are set.
      if (NULL !== $config->get('spt_table')) {
        $spt_table = $config->get('spt_table');
        $form_state->set('spt_table', $spt_table);
      }
      else {
        $spt_table = [''];
        $form_state->set('spt_table', $spt_table);
      }
    }


   // Multi value table form.
    $form['spt_table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#empty' => $this->t('There are no items yet. Add an item.', []),
      '#prefix' => '<div id="spt-fieldset-wrapper">',
      '#suffix' => '</div>',
    ];

    $storage = \Drupal::entityTypeManager()->getStorage('spam_filter_storage');
    $uids = \Drupal::entityQuery('spam_filter_storage')
          ->execute();
    $entities = $storage->loadMultiple($uids);

    $counter = 0;

    // Create row for table.
    foreach ($entities as $entity) {
      foreach ($spt_table as $i => $value) {

        $form['spt_table'][$counter]['webform_id'] = [
          '#type' => 'markup',
          '#markup' => $entity->get("field_webform_id")->getString()
        ];   

        $form['spt_table'][$counter]['message'] = [
          '#type' => 'markup',
          '#markup' => $entity->get("field_message")->getString()
        ];   

        $form['spt_table'][$counter]['Classification'] = [
          '#type' => 'markup',
          '#markup' => $entity->get("field_classification")->getString()
        ]; 

        $form['spt_table'][$counter]['Classified By'] = [
          '#type' => 'markup',
          '#markup' => $entity->get("field_classified_by")->getString()
        ]; 
 
        $form['spt_table'][$counter]['verify']= [
          '#type' => 'radios',
          '#title' => $this->t('Verify'),
          '#title_display' => 'invisible',
          '#options' => array(
            'a' =>t('spam'),
            'b' =>t('ham'),
            'c' =>t('doubt'),
          ),
          '#default_value' => isset($value['verify']) ? $value['verify'] : 'a',
        ];

        $form['spt_table'][$counter]['submitBtn'] = [
          '#type' => 'submit',
          '#value' => $this->t('Submit'),
          '#name' => "submitBtn-" . $counter,
          '#button_type' => 'primary',
          '#submit' => ['::submitElement'],
        ];

      }
      $counter++;
    }
    $form_state->setCached(FALSE);
    return parent::buildForm($form, $form_state);
    
  }

  /**
   * Submit handler for the "Remove" button(s).
   *
   * Remove the element from table and causes a form rebuild.
   */
  public function submitElement(array &$form, FormStateInterface $form_state) {
    $trigger = $form_state->getTriggeringElement();
    $index = (int)(substr($trigger['#name'],10,1));

    if($form['spt_table'][$index]['verify']['#value']["a"] === "a") {
      $lbl = "spam";
    }

    elseif($form['spt_table'][$index]['verify']['#value']["b"] === "b") {
      $lbl = "ham";
    }

    else {
      $lbl = "doubt";
    }
    
    $client = \Drupal::httpClient();

    $request = $client->post('http://205.147.99.79:3000/list_data', [
      'form_params' => [
        'id'=> $form['spt_table'][$index]['message']['#markup'],
        'label'=> $lbl,
      ]
    ]);
    $response = json_decode($request->getBody(),true);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    
  }

}
