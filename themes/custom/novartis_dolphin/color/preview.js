/**
 * DO NOT EDIT THIS FILE.
 * See the following change record for more information,
 * https://www.drupal.org/node/2815083
 * @preserve
 **/

(function($, Drupal, drupalSettings) {
    Drupal.color = {
        logoChanged: false,
        callback: function callback(context, settings, $form) {

            var $colorPreview = $form.find('.color-preview');

            $colorPreview.find('.color-preview-page-title, .color-preview-main h2, .color-preview .preview-content').css('color', $colorPalette.find('input[name="palette[text]"]').val());
        }
    };
})(jQuery, Drupal, drupalSettings);
