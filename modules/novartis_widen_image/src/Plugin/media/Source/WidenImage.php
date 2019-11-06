<?php

namespace Drupal\novartis_widen_image\Plugin\media\Source;

use DOMDocument;
use Drupal\media\MediaInterface;
use Drupal\media\MediaSourceBase;
use Drupal\lightning_media\InputMatchInterface;
use Drupal\media\MediaTypeInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\novartis_widen_image\WidenImageValidation;

/**
 * External image entity media source.
 *
 * @see \Drupal\file\FileInterface
 *
 * @MediaSource(
 *   id = "widen_image",
 *   label = @Translation("Widen Image"),
 *   description = @Translation("Use remote images."),
 *   allowed_field_types = {"string_long"},
 *   thumbnail_alt_metadata_attribute = "alt",
 *   default_thumbnail_filename = "no-thumbnail.png"
 * )
 */
class WidenImage extends MediaSourceBase implements InputMatchInterface {

  /**
   * The widen image validation service.
   *
   * @var \Drupal\novartis_widen_image\WidenImageValidation
   */
  protected $widenImageValidation;

  /**
   * Constructs a new class instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager service.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   Entity field manager service.
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $field_type_manager
   *   The field type plugin manager service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\novartis_widen_image\WidenImageValidation $widen_image_validation
   *   The widen image validation service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, EntityFieldManagerInterface $entity_field_manager, FieldTypePluginManagerInterface $field_type_manager, ConfigFactoryInterface $config_factory, WidenImageValidation $widen_image_validation) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $entity_field_manager, $field_type_manager, $config_factory);
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
        $container->get('entity_type.manager'),
        $container->get('entity_field.manager'),
        $container->get('plugin.manager.field.field_type'),
        $container->get('config.factory'),
        $container->get('novartis_widen_image.widen_image_validation')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function appliesTo($value, MediaTypeInterface $media_type) {

    if ($this->widenImageValidation->validateWidenImage($value)) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Widen local images directory.
   */
  protected static $directory = 'public://widen-thumbnails';

  /**
   * {@inheritdoc}
   */
  public function getMetadataAttributes() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getMetadata(MediaInterface $media, $attribute_name) {
    $source_field = $media->get($this->configuration['source_field'])->getValue();
    $source_code = $source_field[0]['value'];
    $source_code_attributes = $this->getAttributes($source_code);

    if ($attribute_name == 'thumbnail_uri') {
      return $this->getMetadata($media, 'thumbnail_local');
    }

    switch ($attribute_name) {

      case 'thumbnail_local':
        $local_uri = $this->getMetadata($media, 'thumbnail_local_uri');

        if ($local_uri) {
          if (file_exists($local_uri)) {
            return $local_uri;
          }
          else {

            if (!file_exists(static::$directory)) {
              file_prepare_directory(static::$directory, FILE_CREATE_DIRECTORY | FILE_MODIFY_PERMISSIONS);
            }

            $image_data = file_get_contents($source_code_attributes['src']);
            if ($image_data) {
              return file_unmanaged_save_data($image_data, $local_uri, FILE_EXISTS_REPLACE);
            }
          }
        }
        return FALSE;

      case 'thumbnail_local_uri':
        if (isset($source_field) && !empty($source_code_attributes)) {
          $file_info = parse_url($source_code_attributes['src']);
          // Get ext from path.
          $extension = pathinfo($file_info['path'], PATHINFO_EXTENSION);
          // Get name from path.
          $filename = pathinfo($file_info['path'], PATHINFO_FILENAME);
          return static::$directory . '/' . $filename . '.' . $extension;
        }
        return FALSE;
    }

    return FALSE;
  }

  /**
   * Helper function to get image source attributes.
   */
  public function getAttributes($source_code) {

    $result = [];
    if ($source_code == '') {
      return $result;
    }
    $dom = new DOMDocument();
    @$dom->loadHTML($source_code);

    $img = $dom->getElementsByTagName('img')->item(0);
    if ($img->hasAttributes()) {
      foreach ($img->attributes as $attr) {
        $name = $attr->nodeName;
        $value = $attr->nodeValue;
        $result[$name] = $value;
      }
    }

    return $result;
  }

}
