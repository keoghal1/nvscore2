<?php

namespace Drupal\novartis_dolphin_webform\Plugin\WebformHandler;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\novartis_dolphin_webform\GpgEncryption;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionConditionsValidatorInterface;
use Drupal\webform\WebformSubmissionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Site\Settings;

/**
 * Webform submission file write handler.
 *
 * @WebformHandler(
 *   id = "novartis_dolphin_webform_file_write",
 *   label = @Translation("Dolphin Webform Submissions to File"),
 *   category = @Translation("External"),
 *   description = @Translation("Posts webform submissions to server in CSV form."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_OPTIONAL,
 *   tokens = TRUE,
 * )
 */
class FileWriteWebformHandler extends WebformHandlerBase {
  /**
   * The messenger service.
   *
   * @var Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

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
   * The encryption service container.
   *
   * @var Drupal\novartis_dolphin_webform\GpgEncryption
   */
  protected $encryption;

  /**
   * Store connection.
   *
   * @var Drupal\novartis_dolphin_webform\Plugin\WebformHandler\FileWriteWebformHandler
   */
  protected $connection = FALSE;

  /**
   * Store sftp connection.
   *
   * @var Drupal\novartis_dolphin_webform\Plugin\WebformHandler\FileWriteWebformHandler
   */
  protected $sftpConn = FALSE;

  /**
   * Store sftp remote stream object.
   *
   * @var Drupal\novartis_dolphin_webform\Plugin\WebformHandler\FileWriteWebformHandler
   */
  protected $sftpRemoteStream = FALSE;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerChannelFactoryInterface $logger_factory, ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager, WebformSubmissionConditionsValidatorInterface $conditions_validator, MessengerInterface $messenger, Settings $settings, GpgEncryption $encryption) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $logger_factory, $config_factory, $entity_type_manager, $conditions_validator);
    $this->messenger = $messenger;
    $this->settings = $settings;
    $this->configFactory = $config_factory;
    $this->encryption = $encryption;
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
      $container->get('messenger'),
      $container->get('settings'),
      $container->get('novartis_dolphin_webform.gpg_encryption')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'success_message' => 'Form submitted successfully.',
      'failure_message' => 'Something went wrong. Please try again after some time.',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $this->applyFormStateToConfiguration($form_state);
    $form['success_message'] = [
      '#type' => 'webform_html_editor',
      '#title' => $this->t('Success Message'),
      '#description' => $this->t('This message is displayed when remote file is created successfully.'),
      '#default_value' => $this->configuration['success_message'],
    ];

    $form['failure_message'] = [
      '#type' => 'webform_html_editor',
      '#title' => $this->t('Failure Message'),
      '#description' => $this->t('This message is displayed when file is not created on remote server.'),
      '#default_value' => $this->configuration['failure_message'],
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
    $webform = $this->getWebform();
    $public_key = $this->encryption->getPublicKey();
    if ($public_key != '') {
      $host = $this->settings->get('webform_submission_host');
      $port = $this->settings->get('webform_submission_server_port', 22);
      $username = $this->settings->get('webform_submission_server_user');
      $password = $this->settings->get('webform_submission_server_password');
      $remoteDir = $this->settings->get('webform_submission_server_directory');
      $webform_settings = $webform->getSettings();
      $data = $webform_submission->getData();
      $csv_header = array_keys($data);

      $time = time();
      $webform_csv_file = $remoteDir . '/' . $webform->id() . '-' . $time . '.csv.gpg';
      // Setup connection.
      $this->setupConnection($host, $port, $username, $password);
      if ($this->getSsh2SftpConnection()) {
        $this->setRemoteFileStream($this->sftpConn, $webform_csv_file);
        $options = [
          'data' => $data,
          'csv_header' => $csv_header,
        ];
        if ($this->writeFileToServer($options)) {
          $webform_settings['confirmation_message'] = $this->configuration['success_message'];
        }
        else {
          $webform_settings['confirmation_message'] = $this->configuration['failure_message'];
        }
        // Close sftp connection.
        $this->closeConnection();
      }
    }
    $webform->setSettings($webform_settings);
    $this->setWebform($webform);
  }

  /**
   * Function to configure and setup sftp connection.
   *
   * @param string $host
   *   Host name.
   * @param string $port
   *   Server port number.
   * @param string $username
   *   Server username.
   * @param string $password
   *   Server user password.
   */
  public function setupConnection($host, $port, $username, $password) {
    try {
      if (!function_exists("ssh2_connect")) {
        throw new \Exception('Function ssh2_connect does not exist.');
      }
    }
    catch (\Exception $e) {
      $this->getLogger()->notice('Exception: ' . $e->getMessage());
    }
    // Establish connection.
    $this->setConnection($host, $port);

    // Perform authentication.
    $this->doAuthentication($username, $password);

    // Establish sftp connection.
    $this->setSsh2SftpConnection();
  }

  /**
   * Setup remote file stream object.
   *
   * @param object $sftp_conn
   *   SFTP connection object.
   * @param string $webform_csv_file
   *   File path and file name.
   */
  public function setRemoteFileStream($sftp_conn, $webform_csv_file) {
    try {
      $this->sftpRemoteStream = fopen("ssh2.sftp://" . intval($sftp_conn) . "/$webform_csv_file", 'w');
    }
    catch (\Exception $e) {
      $this->getLogger()->notice('Exception: ' . $e->getMessage());
    }
  }

  /**
   * Get remote file stream object.
   *
   * @return object
   *   File stream object.
   */
  public function getRemoteFileStream() {
    return $this->sftpRemoteStream;
  }

  /**
   * Get connection object.
   *
   * @return object
   *   Returns connection object.
   */
  public function getConnection() {
    return $this->connection;
  }

  /**
   * Setup connection.
   *
   * @param string $host
   *   Server/Host name.
   * @param string $port
   *   Port number to connect.
   */
  public function setConnection($host, $port) {
    try {
      if (!$this->connection = ssh2_connect($host, $port)) {
        throw new \Exception('Failed to connect.');
      }
    }
    catch (\Exception $e) {
      $this->getLogger()->notice('Exception: ' . $e->getMessage());
    }
  }

  /**
   * Performs authentication on connection based on username and password.
   *
   * @param string $username
   *   Username string to authenticate.
   * @param string $password
   *   Password string to authenticate.
   *
   * @return bool
   *   Returns boolean value based on authentication success.
   */
  public function doAuthentication($username, $password) {
    $status = TRUE;
    try {
      if (!$this->getConnection()) {
        $status = FALSE;
      }
      if (!ssh2_auth_password($this->getConnection(), $username, $password)) {
        throw new \Exception('Failed to authenticate.');
      }
    }
    catch (\Exception $e) {
      $this->getLogger()->notice('Exception: ' . $e->getMessage());
      $status = FALSE;
    }
    return $status;
  }

  /**
   * Get sftp connection object.
   *
   * @return object
   *   Returns sftp connection object.
   */
  public function getSsh2SftpConnection() {
    return $this->sftpConn;
  }

  /**
   * Establish sftp connection.
   */
  public function setSsh2SftpConnection() {
    try {
      if (!$this->sftpConn = ssh2_sftp($this->connection)) {
        throw new \Exception('Failed to create a sftp connection.');
      }
    }
    catch (\Exception $e) {
      $this->getLogger()->notice('Exception: ' . $e->getMessage());
    }
  }

  /**
   * Close sftp connection.
   */
  public function closeConnection() {
    try {
      if (!ssh2_disconnect($this->getConnection())) {
        throw new \Exception('Failed to disconnect.');
      }
    }
    catch (\Exception $e) {
      $this->getLogger()->notice('Exception: ' . $e->getMessage());
    }
  }

  /**
   * Write file to server.
   *
   * @param array $data
   *   CSV data to write to file.
   *
   * @return bool
   *   Return boolean value based on file creation success.
   */
  public function writeFileToServer(array $data) {
    $status = TRUE;
    $memory_stream = '';
    try {
      $memory_stream = fopen('php://memory', 'w');
      fputcsv($memory_stream, $data['csv_header']);
      fputcsv($memory_stream, $data['data']);
      rewind($memory_stream);
      $csv_content = stream_get_contents($memory_stream);
      if ($encrypted_content = $this->encryption->encrypt($csv_content)) {
        fwrite($this->getRemoteFileStream(), $encrypted_content);
      }
      else {
        $this->getLogger()->notice('Content can not write to server due to failure in Encryption.');
      }
      fclose($memory_stream);
      fclose($this->getRemoteFileStream());
    }
    catch (\Exception $e) {
      error_log('Exception: ' . $e->getMessage());
      fclose($memory_stream);
      fclose($this->getRemoteFileStream());
      $status = FALSE;
    }
    return $status;
  }

}
