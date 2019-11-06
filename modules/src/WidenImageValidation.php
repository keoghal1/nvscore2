<?php

namespace Drupal\novartis_widen_image;

/**
 * Class WidenImageValidation.
 */
class WidenImageValidation {

  /**
   * Validate widen image source.
   *
   * @param string $source
   *   Widen image source.
   *
   * @return bool
   *   TRUE if is a valid widen image source.
   */
  public function validateWidenImage($source) {
    if (preg_match('/^<img\s([\w\W]+)?(src="https:\/\/embed.widencdn.net\/)([\w\W]+)?>$/', $source)) {
      return TRUE;
    }
    return FALSE;
  }

}
