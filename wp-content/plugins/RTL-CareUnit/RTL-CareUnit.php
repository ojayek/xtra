<?php defined('ABSPATH') || die;
/**
 * RTL Care Unit Plugin
 *
 * @package           RTL-CareUnit
 * @author            RTL-Theme
 * @copyright         RTL-Theme
 * @license           RTL-Theme
 *
 * @wordpress-plugin
 *
 * Plugin Name:         مدیریت لایسنس راستچین
 * Plugin URI:          https://www.rtl-theme.com/
 * Description:         افزونه ای جهت فعالسازی، بروزرسانی و مراقبت از محصولات وردپرس راستچین
 * Version:             1.3.4
 * Requires at least:   5.0
 * Requires PHP:        7.2
 * Author:              RTL-Theme
 * Author URI:          https://www.rtl-theme.com/
 * License:             RTL-Theme License
 * License URI:         https://www.rtl-theme.com/
 * Text Domain:         rtltheme
 * Domain Path:         /languages
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

register_activation_hook(__FILE__, 'rcuPluginActivationAction');
