<?php

namespace Drupal\novartis_dolphin_webform\Plugin\WebformElement;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Render\ElementInfoManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TypedData\TypedDataManagerInterface;
use Drupal\webform_image_select\Plugin\WebformElement\WebformImageSelect;
use Drupal\webform\Plugin\WebformElementManagerInterface;
use Drupal\webform\WebformLibrariesManagerInterface;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform\WebformTokenManagerInterface;
use Drupal\webform_score\Plugin\WebformScoreManagerInterface;
use Drupal\webform_score\QuizInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'image select' quiz element.
 *
 * @WebformElement(
 *   id = "novartis_dolphin_webform_score_image_select",
 *   label = @Translation("Quiz Image select"),
 *   description = @Translation("Provides a form element for image selection."),
 *   category = @Translation("Quiz"),
 * )
 */
class WebformImageSelectQuiz extends WebformImageSelect implements QuizInterface {

  use \Drupal\webform_score\Plugin\WebformElement\QuizTrait;

  /**
   * RadiosQuiz constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Render\ElementInfoManagerInterface $element_info
   *   The element info manager.
   * @param \Drupal\Core\TypedData\TypedDataManagerInterface $typed_data_manager
   *   Typed data manager.
   * @param \Drupal\webform\Plugin\WebformElementManagerInterface $element_manager
   *   The webform element manager.
   * @param \Drupal\webform\WebformTokenManagerInterface $token_manager
   *   The webform token manager.
   * @param \Drupal\webform\WebformLibrariesManagerInterface $libraries_manager
   *   The webform libraries manager.
   * @param \Drupal\webform_score\Plugin\WebformScoreManagerInterface $webform_score_manager
   *   Webform score plugin manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerInterface $logger, ConfigFactoryInterface $config_factory, AccountInterface $current_user, EntityTypeManagerInterface $entity_type_manager, ElementInfoManagerInterface $element_info, TypedDataManagerInterface $typed_data_manager, WebformElementManagerInterface $element_manager, WebformTokenManagerInterface $token_manager, WebformLibrariesManagerInterface $libraries_manager, WebformScoreManagerInterface $webform_score_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $logger, $config_factory, $current_user, $entity_type_manager, $element_info, $element_manager, $token_manager, $libraries_manager);

    $this->typedDataManager = $typed_data_manager;
    $this->webformScoreManager = $webform_score_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory')->get('webform'),
      $container->get('config.factory'),
      $container->get('current_user'),
      $container->get('entity_type.manager'),
      $container->get('plugin.manager.element_info'),
      $container->get('typed_data_manager'),
      $container->get('plugin.manager.webform.element'),
      $container->get('webform.token_manager'),
      $container->get('webform.libraries_manager'),
      $container->get('plugin.manager.webform_score')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getAnswerDataTypeId() {
    return 'list';
  }

  /**
   * A method from WebformElementInterface::prepare().
   */
  public function prepare(array &$element, WebformSubmissionInterface $webform_submission = NULL) {
    $element['#type'] = 'webform_image_select';
  }

  /**
   * {@inheritdoc}
   */
  protected function getAnswer($element, WebformSubmissionInterface $webform_submission) {
    $answer = $webform_submission->getElementData($element['#webform_key']);
    if (!(is_array($answer))) {
      // If the answers is not an array, convert it into an array format.
      $temp[] = $answer;
      $answer = $temp;
    }
    return $this->typedDataManager->create($this->typedDataManager->createDataDefinition($this->getAnswerDataTypeId()), $answer);
  }

}
