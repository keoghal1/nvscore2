<?php

namespace Drupal\novartis_dolphin_webform\Plugin\WebformHandler;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionConditionsValidatorInterface;
use Drupal\webform\WebformSubmissionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Webform submission file write handler.
 *
 * @WebformHandler(
 *   id = "novartis_dolphin_webform_quiz_result",
 *   label = @Translation("Dolphin Webform Quiz results generator"),
 *   category = @Translation("Quiz"),
 *   description = @Translation("Generates the results for a quiz."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_OPTIONAL,
 *   tokens = TRUE,
 * )
 */
class WebformQuizResultHandler extends WebformHandlerBase {
  /**
   * The messenger service.
   *
   * @var Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerChannelFactoryInterface $logger_factory, ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager, WebformSubmissionConditionsValidatorInterface $conditions_validator, MessengerInterface $messenger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $logger_factory, $config_factory, $entity_type_manager, $conditions_validator);
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory'),
      $container->get('config.factory'),
      $container->get('entity_type.manager'),
      $container->get('webform_submission.conditions_validator'),
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'quiz_threshold' => 5,
      'affirmative_message' => 'This is an affirmative message.',
      'negative_message' => 'This is a negative message.',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $this->applyFormStateToConfiguration($form_state);
    $form['quiz_threshold'] = [
      '#type' => 'number',
      '#title' => $this->t('Quiz threshold'),
      '#description' => $this->t('Based on this score an affirmative / negative message will be shown.'),
      '#default_value' => $this->configuration['quiz_threshold'],
    ];

    $form['affirmative_message'] = [
      '#type' => 'webform_html_editor',
      '#title' => $this->t('Affirmative Message'),
      '#description' => $this->t('This message is displayed when quiz score is more than equal to threshold.'),
      '#default_value' => $this->configuration['affirmative_message'],
    ];

    $form['negative_message'] = [
      '#type' => 'webform_html_editor',
      '#title' => $this->t('Negative Message'),
      '#description' => $this->t('This message is displayed when quiz score is less than threshold.'),
      '#default_value' => $this->configuration['negative_message'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->applyFormStateToConfiguration($form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
    $score = $webform_submission->get('webform_score')->first()->get('numerator')->getValue();
    $this->messenger->addStatus("Your quiz score is $score");
    $webform = $this->getWebform();
    $webform_settings = $webform->getSettings();
    if ($score >= $this->configuration['quiz_threshold']) {
      $webform_settings['confirmation_message'] = $this->configuration['affirmative_message'];
    }
    else {
      $webform_settings['confirmation_message'] = $this->configuration['negative_message'];
    }
    $webform->setSettings($webform_settings);
    $this->setWebform($webform);
  }

}
