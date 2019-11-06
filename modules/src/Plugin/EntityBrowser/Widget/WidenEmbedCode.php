<?php

namespace Drupal\novartis_widen_image\Plugin\EntityBrowser\Widget;

use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\entity_browser\WidgetValidationManager;
use Drupal\novartis_widen_image\WidenImageValidation;

/**
 * An Entity Browser widget for creating widen media entities from embed codes.
 *
 * @EntityBrowserWidget(
 *   id = "widen_embed_code",
 *   label = @Translation("Widen Embed Code"),
 *   description = @Translation("Allows creation of widen media entities from embed codes."),
 * )
 */
class WidenEmbedCode extends WidenEntityFormProxy {

  /**
   * The widen image validation service.
   *
   * @var \Drupal\novartis_widen_image\WidenImageValidation
   */
  protected $widenImageValidation;

  /**
   * Constructs widget plugin.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   Event dispatcher service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\entity_browser\WidgetValidationManager $validation_manager
   *   The Widget Validation Manager service.
   * @param \Drupal\novartis_widen_image\WidenImageValidation $widen_image_validation
   *   The widen image validation service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EventDispatcherInterface $event_dispatcher, EntityTypeManagerInterface $entity_type_manager, WidgetValidationManager $validation_manager, WidenImageValidation $widen_image_validation) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $event_dispatcher, $entity_type_manager, $validation_manager);
    $this->widenImageValidation = $widen_image_validation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
        $configuration,
        $plugin_id,
        $plugin_definition,
        $container->get('event_dispatcher'),
        $container->get('entity_type.manager'),
        $container->get('plugin.manager.entity_browser.widget_validation'),
        $container->get('novartis_widen_image.widen_image_validation')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getForm(array &$original_form, FormStateInterface $form_state, array $additional_widget_parameters) {
    $form = parent::getForm($original_form, $form_state, $additional_widget_parameters);

    $form['widen_input'] = [
      '#type' => 'textarea',
      '#placeholder' => $this->t('Enter a widen URL...'),
      '#attributes' => [
        'class' => ['keyup-change'],
      ],
      '#ajax' => [
        'event' => 'change',
        'wrapper' => 'entity-form',
        'method' => 'html',
        'callback' => [static::class, 'ajax'],
      ],
      // I don't know why, but this is needed to display error messages.
      '#limit_validation_errors' => [
        ['input'],
      ],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validate(array &$form, FormStateInterface $form_state) {

    $value = $form_state->getValue('widen_input');

    if ($value) {
      if (!($this->widenImageValidation->validateWidenImage($value))) {
        // Set form error.
        $form_state->setError($form['widget']['input'], 'Enter a valid widen image source code.');
      }
    }
    elseif ($form_state->isSubmitted()) {
      $form_state->setError($form['widget']['input'], $this->t('You must enter an widen embed code.'));
    }
  }

}
