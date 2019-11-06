<?php

namespace Drupal\video_embed_kaltura\Plugin\video_embed_field\Provider;

use DOMDocument;
use Drupal\video_embed_field\ProviderPluginBase;
use GuzzleHttp\ClientInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A Kaltura provider plugin.
 *
 * @VideoEmbedProvider(
 *   id = "kaltura",
 *   title = @Translation("Kaltura")
 * )
 */
class Kaltura extends ProviderPluginBase {

  /**
   * Config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Create a plugin with the given input.
   *
   * @param string $configuration
   *   The configuration of the plugin.
   * @param string $plugin_id
   *   The plugin id.
   * @param array $plugin_definition
   *   The plugin definition.
   * @param \GuzzleHttp\ClientInterface $http_client
   *   An HTTP client.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory service.
   *
   * @throws \Exception
   */
  public function __construct($configuration, $plugin_id, array $plugin_definition, ClientInterface $http_client, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $http_client);
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('http_client'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function renderEmbedCode($width, $height, $autoplay) {
    $kalturaSettings = $this->configFactory->get('video_embed_kaltura.kalturasettings');
    $input = $this->getInput();
    $url = Kaltura::getSrcFromInput($input);
    $embed_code = [
      '#type' => 'video_embed_iframe',
      '#provider' => 'kaltura',
      '#url' => sprintf('%s', $url),
      '#query' => [
        'iframeembed' => 'true',
        'entry_id' => $this->getEntryId(),
        'wid' => $this->getWid(),
      ],
      '#attributes' => [
        'width' => $width,
        'height' => $height,
        'allowfullscreen' => 'allowfullscreen',
      ],
    ];
    $flashVars = $this->getFlashVars();
    foreach ($flashVars as $var => $var_value) {
      $embed_code['#query']['flashvars[' . $var . ']'] = $var_value;
    }
    // Embed External CSS file to kaltura video.
    if ($css_path = $kalturaSettings->get('css_path')) {
      $embed_code['#query']['flashvars[IframeCustomPluginCss1]'] = $css_path;
    }
    return $embed_code;
  }

  /**
   * {@inheritdoc}
   */
  public function getRemoteThumbnailUrl() {
    $partner_id = $this->getPartnerId();
    $entry_id = $this->getEntryId();
    return sprintf('http://cfvod.kaltura.com/p/%s/sp/%s00/thumbnail/entry_id/%s/width/560/height/395', $partner_id, $partner_id, $entry_id);
  }

  /**
   * {@inheritdoc}
   */
  public static function getIdFromInput($input) {
    $url = Kaltura::getSrcFromInput($input);
    if (!$url) {
      $url = $input;
    }
    $matches = Kaltura::getMetaFromUrl($url);
    // Using uiconfId here as it seems to be unique for various videos.
    return isset($matches['uiConfId']) ? $matches['uiConfId'] : FALSE;
  }

  /**
   * A function to return kaltura partner id.
   */
  public function getPartnerId() {
    $input = $this->getInput();
    $url = Kaltura::getSrcFromInput($input);
    if (!$url) {
      $url = $input;
    }
    $matches = Kaltura::getMetaFromUrl($url);
    return $matches['partnerId'];
  }

  /**
   * A function to return kaltura uiconf id.
   */
  public function getUiconfId() {
    $input = $this->getInput();
    $url = Kaltura::getSrcFromInput($input);
    if (!$url) {
      $url = $input;
    }
    $matches = Kaltura::getMetaFromUrl($url);
    return $matches['uiconf_id'];
  }

  /**
   * A function to return kaltura entry id.
   */
  public function getEntryId() {
    $input = $this->getInput();
    $options = Kaltura::getJsonOptions($input);
    if (isset($options['entry_id'])) {
      return $options['entry_id'];
    }
    else {
      $url = Kaltura::getSrcFromInput($input);
      preg_match('/entry_id=([a-zA-Z0-9_]*)/', $url, $matches);
      if (!count($matches)) {
        return FALSE;
      }
      return $matches[1];
    }
    return FALSE;
  }

  /**
   * A function to return kaltura flash vars.
   */
  public function getFlashVars() {
    $input = $this->getInput();
    $options = Kaltura::getJsonOptions($input);
    if (isset($options['flashvars'])) {
      return $options['flashvars'];
    }
    else {
      $url = Kaltura::getSrcFromInput($input);
      preg_match_all('/flashvars\[([A-Za-z0-9_\-\.]+)\]=([A-Za-z0-9_\-\.]+)/', $url, $matches);
      if (!count($matches)) {
        return FALSE;
      }
      $vars = [];
      foreach ($matches[0] as $i => $m) {
        $vars[$matches[1][$i]] = $matches[2][$i];
      }
      return $vars;
    }
    return FALSE;
  }

  /**
   * A function to return Src from input for kaltura.
   */
  public static function getSrcFromInput($input_string) {
    $count = preg_match('/src=(["\'])(.*?)\1/', $input_string, $match);
    if ($count === FALSE) {
      return FALSE;
    }
    else {
      return isset($match[2]) ? $match[2] : FALSE;
    }
  }

  /**
   * A helper function to extract metadata from kaltura URL.
   */
  public static function getMetaFromUrl($url) {
    $pattern = '';
    $base = '/^https?:\/\/([a-zA-Z]*\.)kaltura.com';
    $end = '/';
    $pattern .= $base;

    $data = [
      'p' => '?<partnerId>[a-z0-9]{2,100}',
      'sp' => '?<partnerIdsp>[a-z0-9]{2,100}00',
      'embedIframeJs' => '',
      'uiconf_id' => '?<uiConfId>[a-z0-9]{2,100}',
      'partner_id' => '?<partner_id>[a-z0-9]{2,100}',
    ];
    foreach ($data as $param => $regex) {
      $pattern .= '\/' . $param;
      if (!empty($regex)) {
        $pattern .= '\/(' . $regex . ')';
      }
    }
    $pattern .= $end;
    preg_match($pattern, $url, $matches);
    return $matches;
  }

  /**
   * This method will parse and return options from kWidget.
   */
  public static function getJsonOptions($input) {
    $domd = new DOMDocument();
    libxml_use_internal_errors(TRUE);
    $domd->loadHTML($input);
    libxml_use_internal_errors(FALSE);

    $items = $domd->getElementsByTagName('script');
    foreach ($items as $item) {
      $kwidget = $item->nodeValue;
      if ($kwidget) {
        // @todo Add checks here to see that we actually have kWidget.embed.
        $js_encode = str_replace('kWidget.embed(', '', $kwidget);
        $js_encode = str_replace(');', '', $js_encode);

        return json_decode($js_encode, TRUE);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getLocalThumbnailUri() {
    return $this->thumbsDirectory . '/' . $this->getEntryId() . '.jpg';
  }

  /**
   * A function to return widget id.
   */
  public function getWid() {
    $input = $this->getInput();
    $options = Kaltura::getJsonOptions($input);
    if (isset($options['wid'])) {
      return $options['wid'];
    }
    else {
      $url = Kaltura::getSrcFromInput($input);
      preg_match('/wid=([a-zA-Z0-9_]*)/', $url, $matches);
      if (!count($matches)) {
        return FALSE;
      }
      return $matches[1];
    }
    return FALSE;
  }

}
