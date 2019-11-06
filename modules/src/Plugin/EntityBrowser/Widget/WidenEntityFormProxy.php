<?php

namespace Drupal\novartis_widen_image\Plugin\EntityBrowser\Widget;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\PrependCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\entity_browser\WidgetBase;
use Drupal\inline_entity_form\ElementSubmit;
use Drupal\media\MediaTypeInterface;

/**
 * Base class for EB widgets which wrap around an (inline) entity form.
 */
abstract class WidenEntityFormProxy extends WidgetBase {

  /**
   * Widen media type.
   */
  const TYPE = 'widen';

  /**
   * {@inheritdoc}
   */
  public function getForm(array &$original_form, FormStateInterface $form_state, array $additional_widget_parameters) {
    $form = parent::getForm($original_form, $form_state, $additional_widget_parameters);

    if (isset($form['actions'])) {
      $form['actions']['#weight'] = 100;

      // Allow the form to be rebuilt without using AJAX interactions. This means
      // we can do a lot of testing without JavaScript, which is WAY easier.
      $form['actions']['update'] = [
        '#type' => 'submit',
        '#value' => $this->t('Update'),
        '#attributes' => [
          'class' => ['js-hide'],
        ],
        '#submit' => [
          [static::class, 'update'],
        ],
      ];
    }

    $form['#type'] = 'container';
    $form['#attributes']['id'] = 'entity-form';

    $entity = $this->getCurrentEntity($form_state);
    if ($entity) {
      $form['entity'] = [
        '#type' => 'inline_entity_form',
        '#entity_type' => 'media',
        '#default_value' => $entity,
        '#form_mode' => $this->configuration['form_mode'],
        '#weight' => 90,
      ];
      $form['entity']['#bundle'] = static::TYPE;

      // Without this, IEF won't know where to hook into the widget. Don't pass
      // $original_form as the second argument to addCallback(), because it's not
      // just the entity browser part of the form, not the actual complete form.
      ElementSubmit::addCallback($form['actions']['submit'], $form_state->getCompleteForm());
    }

    return $form;
  }

  /**
   * Helper function to get the current selected entity.
   */
  protected function getCurrentEntity(FormStateInterface $form_state) {
    $value = $this->getCurrentValue($form_state);
    $widen_media = $this->entityTypeManager
      ->getStorage('media_type')->load(static::TYPE);
    if ($value && $widen_media) {
      return $this->createMedia($value, $widen_media);
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareEntities(array $form, FormStateInterface $form_state) {
    $entity_form = &$form['widget']['entity_form']['entity'];
    if (isset($entity_form['#entity'])) {
      return [
        $entity_form['#entity'],
      ];
    }
    return [];
  }

  /**
   * Submit callback for the Update button.
   *
   * @param array $form
   *   The complete form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current form state.
   */
  public static function update(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild();
  }

  /**
   * {@inheritdoc}
   */
  public function submit(array &$element, array &$form, FormStateInterface $form_state) {
    // IEF will take care of creating the entity upon submission. All we need to
    // do is send it upstream to Entity Browser.
    $entity = $form['widget']['entity']['#entity'];
    $this->selectEntities([$entity], $form_state);
  }

  /**
   * AJAX callback. Returns the rebuilt inline entity form.
   *
   * @param array $form
   *   The complete form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current form state.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   The AJAX response.
   */
  public static function ajax(array &$form, FormStateInterface $form_state) {
    if ($form_state::hasAnyErrors()) {
      $form['widget']['bundle']['#access'] = FALSE;
    }

    return (new AjaxResponse())
      ->addCommand(
        new ReplaceCommand('#entity-form', $form['widget'])
      )
      ->addCommand(
        new PrependCommand('#entity-form', ['#type' => 'status_messages'])
      );
  }

  /**
   * Returns the current input value, if any.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current form state.
   *
   * @return mixed
   *   The input value, ready for further processing. Nothing will be done with
   *   the value if it's empty.
   */
  protected function getCurrentValue(FormStateInterface $form_state) {
    $input = $form_state->getUserInput();
    if (isset($input['widen_input'])) {
      return $input['widen_input'];
    }
    return '';
  }

  /**
   * Creates a new, unsaved media entity from a source field value.
   *
   * @param mixed $value
   *   The source field value.
   * @param \Drupal\media\MediaTypeInterface $type
   *   The media type.
   *
   * @return \Drupal\media\MediaInterface
   *   The unsaved media entity.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function createMedia($value, MediaTypeInterface $type) {
    $values = [
      'bundle' => $type->id(),
    ];

    $field = $type->getSource()->getSourceFieldDefinition($type)->getName();
    $values[$field] = $value;

    return $this->entityTypeManager->getStorage('media')->create($values);
  }

}
