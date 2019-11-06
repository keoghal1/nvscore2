<?php

namespace Drupal\novartis_widen_image\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'widen_image_embed' formatter.
 *
 * @FieldFormatter(
 *   id = "widen_image_embed",
 *   label = @Translation("Widen image embed"),
 *   field_types = {
 *     "string_long"
 *   }
 * )
 */
class WidenImageEmbedFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {

      $elements[$delta] = [
        '#theme' => 'widen_image_formatter',
        '#widen_embed_code' => $item->value,
      ];
    }

    return $elements;
  }

}
