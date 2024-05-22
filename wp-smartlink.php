<?php

/**
 * Plugin Name
 *
 * @package           WP-Smartlink
 * @author            Alec Seidemann
 * @copyright         2023 Alec Seidemann
 * @license           GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       WP Smartlink
 * Plugin URI:        https://werbe-sofa.de
 * Description:       WP Smartlink ist ein benutzerfreundliches Plugin, mit dem du eine Seite zur Auflistung aller deiner Social Media Links und wichtigen Links erstellen kannst. Verwalte Bild, Titel und Links im WordPress-Dashboard und füge sie mithilfe des Shortcodes [wp_smartlink] an beliebiger Stelle deiner Website ein.
 * Version:           1.0.1
 * Requires at least: 6.0.0
 * Requires PHP:      7.4
 * Author:            Alec Seidemann
 * Author URI:        https://agentur-seidemann.de
 * Text Domain:       wp-smartlink
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * GitHub Plugin URI: https://github.com/supaaboy/WP-Smartlink
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define smartlink variables
define('SMARTLINK_ROOT', dirname(__FILE__));
define('SMARTLINK_URL', plugins_url('', __FILE__));

// Require smartlink libraries
require_once SMARTLINK_ROOT . '/src/libraries/config.php';
require_once SMARTLINK_ROOT . '/src/libraries/core.php';
require_once SMARTLINK_ROOT . '/src/libraries/bootstrap.php';
