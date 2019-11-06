<?php

namespace Drupal\novartis_dolphin_webform;

use Drupal\Core\Site\Settings;
use Drupal\Core\Config\ConfigFactoryInterface;
use gnupg;

/**
 * Custom service for encrypting string.
 */
class GpgEncryption {
  /**
   * The settings array.
   *
   * @var \Drupal\Core\Site\Settings
   */
  protected $settings;

  /**
   * The config factory service.
   *
   * @var Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new CustomService object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory service container.
   * @param \Drupal\Core\Site\Settings $settings
   *   Settings service container.
   */
  public function __construct(ConfigFactoryInterface $config_factory, Settings $settings) {
    $this->settings = $settings;
    $this->configFactory = $config_factory;
  }

  /**
   * Encrypt string.
   *
   * @param string $data
   *   String to encrypt.
   *
   * @return string
   *   Return encrypted string.
   */
  public function encrypt($data) {
    $gnupg_home_directory = $this->settings->get('novartis_pgp_public_key', '/tmp');
    putenv("GNUPGHOME=$gnupg_home_directory");
    $gpg = new gnupg();
    $status = $gpg->import($this->getPublicKey());
    if ($status) {
      $gpg->addencryptkey($status['fingerprint']);
      return $gpg->encrypt($data);
    }
    else {
      return FALSE;
    }
  }

  /**
   * Fetch configured public key.
   *
   * @return string
   *   Returns Public key string.
   */
  public function getPublicKey() {
    $public_key = '';
    $global_webform_settings = $this->configFactory->get('novartis_dolphin_webform.webform_global_settings');
    // Check if pgp key is configured. If not, read from settings.
    if ($global_webform_settings->get('pgp_public_key')) {
      $public_key = $global_webform_settings->get('pgp_public_key');
    }
    else {
      $public_key = $this->settings->get('novartis_pgp_public_key');
    }
    return $public_key;
  }

}
