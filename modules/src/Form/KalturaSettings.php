<?php

namespace Drupal\video_embed_kaltura\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class KalturaSettings.
 */
class KalturaSettings extends ConfigFormBase {

  /**
   * Store the render cache object.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $renderCache;

  /**
   * Store the current request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $currentRequest;

  /**
   * Constructs a new CommentApproverSettings object.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    CacheBackendInterface $renderCache,
    Request $currentRequest
    ) {
    parent::__construct($config_factory);
    $this->renderCache = $renderCache;
    $this->currentRequest = $currentRequest;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('cache.render'),
      $container->get('request_stack')->getCurrentRequest()
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'video_embed_kaltura.kalturasettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'kaltura_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('video_embed_kaltura.kalturasettings');
    $example_path = $this->currentRequest->getSchemeAndHttpHost() . '/' . drupal_get_path('module', 'video_embed_kaltura') . '/css/kaltura_player.css';

    $form['css_path'] = [
      '#type' => 'url',
      '#title' => $this->t('External Css Path'),
      '#default_value' => $config->get('css_path'),
      '#description' => $this->t('Please enter an absolute URL for the CSS path. For example %example', [
        '%example' => $example_path,
      ]),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('video_embed_kaltura.kalturasettings')
      ->set('css_path', $form_state->getValue('css_path'))
      ->save();

    // Clear the render cache so that settings will take effect.
    $this->renderCache->invalidateAll();
  }

}
