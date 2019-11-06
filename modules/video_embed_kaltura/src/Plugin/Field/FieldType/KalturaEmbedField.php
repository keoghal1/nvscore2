<?php

namespace Drupal\video_embed_kaltura\Plugin\Field\FieldType;

use Drupal\video_embed_field\Plugin\Field\FieldType\VideoEmbedField;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * A field type extending videoembed field.
 */
class KalturaEmbedField extends VideoEmbedField {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'text',
          'size' => 'big',
        ],
      ],
    ];
  }

}
