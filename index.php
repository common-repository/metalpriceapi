<?php
/*
 * Plugin Name: MetalPriceAPI
 * Author: metalpriceapi.com
 * Version: 1.0.9
 * Description: Official <a href="https://metalpriceapi.com/">metalpriceapi.com</a> plugin.
 * Author URL: https://metalpriceapi.com
*/

class MetalpriceAPI {

  private $opt_api_key = 'mpa_api_key';
  private $opt_api_status = 'mpa_api_status';

  private $opt_data_success = 'mpa_data_success';
  private $opt_data_none = 'mpa_data_none';
  private $opt_data_error = 'mpa_data_error';

  private $opt_data_carat_success = 'mpa_data_carat_success';

  function __construct() {
    add_shortcode('metalpriceapi',[$this, 'shortcode']);
    add_shortcode('metalpriceapi_carat',[$this, 'shortcode_carat']);
    add_action('admin_menu', [$this, 'action']);
  }

  // helper

  function saveOption($name) {
    if (isset($_POST[$name])) {
      update_option($name, sanitize_text_field($_POST[$name]));
    }
  }

  function saveHTMLOption($name) {
    if(isset($_POST[$name])){
      update_option($name, wp_kses_post($_POST[$name]));
    }
  }

  // hooks

  function shortcode($attrs) {
    $attrs = shortcode_atts(array(
      'base' =>  'USD',
      'symbol' =>  'XAU',
      'date_format' => 'Y-m-d',
      'date' => null,
      'days' => null,
      'price_round' => 2,
      'unit' => null,
      'purity' => null,
      'operation' => null,
    ), $attrs);

    $endpoint = 'latest';
    if (isset($attrs['date']) && !empty(trim($attrs['date']))) {
      $endpoint = date('Y-m-d', strtotime($attrs['date']));
    } else if (isset($attrs['days']) && !empty(trim($attrs['days'])) && is_numeric($attrs['days']) && $attrs['days'] <= 0) {
      $endpoint = date('Y-m-d', strtotime("-{$attrs['days']} days", strtotime('now')));
    }

    $api_key = get_option($this->opt_api_key);

    $uri = "https://api.metalpriceapi.com/v1/$endpoint?api_key=$api_key&base=".$attrs['base']."&currencies=".$attrs['symbol']."";

    if (isset($attrs['unit'])) {
      $uri .= "&unit=".$attrs['unit']."";
    }
    if (isset($attrs['purity'])) {
      $uri .= "&purity=".$attrs['purity']."";
    }

    $response = wp_remote_get($uri);
    $body = wp_remote_retrieve_body($response);

    if(empty(trim($body))) {
      return get_option($this->opt_data_error, 'Response was empty.');
    }
    $json = json_decode($body, true);

    if (isset($json['success']) && $json['success'] == true) {
      $value = get_option($this->opt_data_success, '{{symbol}} {{price}}');

      $base = $json['base'];
      $symbol = $attrs['symbol'];
      $timestamp = $json['timestamp'];
      $rate = $json['rates'][$symbol];

      include(plugin_dir_path(__FILE__). 'module/currency_symbols.php');
      if(isset($currency_symbols[$base])) {
        $value = str_replace('{{base}}', $currency_symbols[$base], $value);
      } else {
        $value = str_replace('{{base}}', $base, $value);
      }

      $value = str_replace('{{symbol}}', $symbol, $value);

      if (isset($timestamp)) {
        $value = str_replace('{{timestamp}}', $timestamp, $value);

        $date = date($attrs['date_format'], $timestamp);
        $value = str_replace('{{date}}', $date, $value);
      }

      $price = 1.0/$rate;

      if (isset($attrs['operation'])) {
        $operation = $attrs['operation'];
        $operation = str_replace('(value)', $price, $operation);
        eval('$price = ' . $operation . ';');
      }

      $price = number_format($price, $attrs['price_round'], '.', '');

      update_option($this->opt_api_status, 'API key is working.');

      $value = str_replace('{{price}}', $price, $value);
    } else if (isset($json['success']) && $json['success'] == false) {
      $value = get_option($this->opt_data_error, 'Error: {{error}}');

      $status_code = $json['error']['statusCode'];
      $message = $json['error']['message'];
      if(isset($status_code) && isset($message)) {
        $value = str_replace('{{error}}', "$status_code: ${message}", $value);

        if ($status_code == 101 || $status_code == 102) {
          update_option($this->opt_api_status, $message);
        }
      }
    } else {
      $value = get_option($this->opt_data_error, 'Response was empty.');
    }

    return $value;
  }

  function shortcode_carat($attrs) {
    $attrs = shortcode_atts(array(
      'base' =>  'USD',
      'date_format' => 'Y-m-d',
      'date' => null,
      'price_round' => 2,
      'purity' => '24k',
    ), $attrs);

    $endpoint = isset($attrs['date']) && !empty(trim( $attrs['date'])) ?
      date('Y-m-d', strtotime($attrs['date'])) :
      'latest';

    $api_key = get_option($this->opt_api_key);

    $uri = "https://api.metalpriceapi.com/v1/carat?api_key=$api_key&base=".$attrs['base']."";
    if (isset($attrs['date']) && !empty(trim( $attrs['date']))) {
      $uri .= "&date=".$attrs['date']."";
    }

    $response = wp_remote_get($uri);
    $body = wp_remote_retrieve_body($response);

    if(empty(trim($body))) {
      return get_option($this->opt_data_error, 'Response was empty.');
    }
    $json = json_decode($body, true);

    if (isset($json['success']) && $json['success'] == true) {
      $value = get_option($this->opt_data_carat_success, '{{price}}');

      $base = $json['base'];
      $timestamp = $json['timestamp'];
      $rate = $json['data'][$attrs['purity']];

      include(plugin_dir_path(__FILE__). 'module/currency_symbols.php');
      if(isset($currency_symbols[$base])) {
        $value = str_replace('{{base}}', $currency_symbols[$base], $value);
      } else {
        $value = str_replace('{{base}}', $base, $value);
      }

      if (isset($timestamp)) {
        $value = str_replace('{{timestamp}}', $timestamp, $value);

        $date = date($attrs['date_format'], $timestamp);
        $value = str_replace('{{date}}', $date, $value);
      }

      $price = $rate;
      $price = number_format($price, $attrs['price_round'], '.', '');

      update_option($this->opt_api_status, 'API key is working.');

      $value = str_replace('{{price}}', $price, $value);
    } else if (isset($json['success']) && $json['success'] == false) {
      $value = get_option($this->opt_data_error, 'Error: {{error}}');

      $status_code = $json['error']['statusCode'];
      $message = $json['error']['message'];
      if(isset($status_code) && isset($message)) {
        $value = str_replace('{{error}}', "$status_code: ${message}", $value);

        if ($status_code == 101 || $status_code == 102) {
          update_option($this->opt_api_status, $message);
        }
      }
    } else {
      $value = get_option($this->opt_data_error, 'Response was empty.');
    }

    return $value;
  }

  function action() {
    add_menu_page(
      'MetalpriceAPI Settings',
      'MetalpriceAPI',
      'administrator',
      'main',
      [$this, 'render_settings']
    );

    add_submenu_page(
      'main',
      'MetalpriceAPI Settings',
      'Settings',
      'administrator',
      'settings',
      [$this, 'render_settings']
    );

    add_submenu_page(
      'main',
      'Instructions',
      'Instructions',
      'administrator',
      'info',
      [$this, 'render_info']
    );

    remove_submenu_page("main", "main");
  }

  // render

  function render_settings() {
    $is_saved = 0;
    if (isset($_POST['save'])) {
      if (!isset( $_POST['mpa_nonce']) || !wp_verify_nonce($_POST['mpa_nonce'])) {
        wp_die("ERROR: Invalid request");
      }
      $this->saveOption($this->opt_api_key);
      $this->saveHTMLOption($this->opt_data_success);
      $this->saveHTMLOption($this->opt_data_none);
      $this->saveHTMLOption($this->opt_data_error);
      $this->saveHTMLOption($this->opt_data_carat_success);

      $is_saved = 1;
    }

    $api_status = get_option($this->opt_api_status);

    require plugin_dir_path(__FILE__). 'module/settings.php';
  }

  function render_info() {
    require plugin_dir_path(__FILE__). 'module/info.php';
  }
}

new MetalpriceAPI();
