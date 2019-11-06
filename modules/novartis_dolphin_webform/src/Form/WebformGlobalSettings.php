<?php

namespace Drupal\novartis_dolphin_webform\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Site\Settings;
use Drupal\novartis_dolphin_webform\GpgEncryption;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a form that configures forms module settings.
 */
class WebformGlobalSettings extends ConfigFormBase {
  /**
   * The settings array.
   *
   * @var \Drupal\Core\Site\Settings
   */
  protected $settings;

  /**
   * The encryption service container.
   *
   * @var Drupal\novartis_dolphin_webform\GpgEncryption
   */
  protected $encryption;

  /**
   * {@inheritdoc}
   */
  public function __construct(Settings $settings, ConfigFactoryInterface $config_factory, GpgEncryption $encryption) {
    parent::__construct($config_factory);
    $this->settings = $settings;
    $this->encryption = $encryption;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
    // Load the service required to construct this class.
      $container->get('settings'),
      $container->get('config.factory'),
      $container->get('novartis_dolphin_webform.gpg_encryption')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'novartis_dolphin_webform_webform_global_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'novartis_dolphin_webform.webform_global_settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['pgp_public_key'] = [
      '#type' => 'textarea',
      '#title' => $this->t('PGP Public Key'),
      '#default_value' => $this->encryption->getPublicKey(),
      '#rows' => 20,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('novartis_dolphin_webform.webform_global_settings')
      ->set('pgp_public_key', $values['pgp_public_key'])
      ->save();
    parent::submitForm($form, $form_state);
  }

}
