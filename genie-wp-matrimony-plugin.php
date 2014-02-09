<?php

/*
Plugin Name: Genie WP Matrimony
Plugin URI: http://www.itechgenie.com/myblog/genie-wp-matrimony
Description: Genie WP Matrimony is Wordpress plugin which help in converting your 
Wordpress blog into a complete matrimonial website. This plugin uses the information 
of the default Wordpress users which makes it very easier and faster integration 
with existing Wordpress setup. 
Version: 0.6
Author: prakashm88
Author URI: http://www.itechgenie.com
License: GPLv2
*/

/*  Copyright 2012-2014  prakashm88

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

// Loading only for admin users
/*
 if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
*/
if ( ! defined( 'DS' ) )
	define('DS', DIRECTORY_SEPARATOR);
if ( ! defined( 'URL_S' ) )
	define('URL_S', '/') ;
define('GWPM_PLUGIN_NAME', basename( dirname( __FILE__ )) );

// Pre-2.6 compatibility
if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . URL_S . 'wp-content' );

if (!defined('GWPM_CONTENT_URL'))
	define('GWPM_CONTENT_URL', WP_PLUGIN_URL . URL_S . GWPM_PLUGIN_NAME);
if (!defined('GWPM_ROOT'))
	define('GWPM_ROOT', dirname (__FILE__));

load_plugin_textdomain( 'genie_wp_matrimony', false, basename( dirname( __FILE__ ) ) . '/languages' );

/** Common configs are loaded from gwpm_config.php */

require_once(GWPM_ROOT . DS . 'config' . DS . 'gwpm_config.php') ;

global $genieWPMatrimonyController;
global $gwpm_db_version;
global $gwpm_activity_model;
global $gwpm_setup_model;

require_once (GWPM_LIBRARY_URL . DS . 'gwpm_shared.php');
require_once (GWPM_LIBRARY_URL . DS . 'gwpm_template.class.php');
require_once (GWPM_LIBRARY_URL . DS . 'gwpm_controller.class.php');
require_once (GWPM_LIBRARY_URL . DS . 'gwpm_command.class.php');
require_once (GWPM_LIBRARY_URL . DS . 'gwpm_exception.class.php');
require_once (GWPM_APPLICATION_URL . DS . 'controllers' . DS . 'GenieWPMatrimonyController.php');
require_once (GWPM_APPLICATION_URL . DS . 'controllers' . DS . 'GwpmActivityController.php');
require_once (GWPM_APPLICATION_URL . DS . 'controllers' . DS . 'GwpmGalleryController.php');
require_once (GWPM_APPLICATION_URL . DS . 'controllers' . DS . 'GwpmAjaxController.php');
require_once (GWPM_APPLICATION_URL . DS . 'controllers' . DS . 'GwpmMessagesController.php');
require_once (GWPM_APPLICATION_URL . DS . 'models' . DS . 'GwpmAdminModel.php');
require_once (GWPM_APPLICATION_URL . DS . 'models' . DS . 'GwpmSetupModel.php');
require_once (GWPM_APPLICATION_URL . DS . 'models' . DS . 'GwpmActivityModel.php');
require_once (GWPM_APPLICATION_URL . DS . 'models' . DS . 'GwpmProfileModel.php');
require_once (GWPM_APPLICATION_URL . DS . 'models' . DS . 'GwpmGalleryModel.php');
require_once (GWPM_APPLICATION_URL . DS . 'models' . DS . 'GwpmMessagesModel.php');
require_once (GWPM_APPLICATION_URL . DS . 'vos' . DS . 'GwpmProfileVO.php');
require_once (GWPM_APPLICATION_URL . DS . 'vos' . DS . 'GwpmActivityVO.php');
require_once (GWPM_APPLICATION_URL . DS . 'vos' . DS . 'GwpmGalleryVO.php');
require_once (GWPM_APPLICATION_URL . DS . 'vos' . DS . 'GwpmSearchVO.php');
require_once (GWPM_APPLICATION_URL . DS . 'vos' . DS . 'GwpmAdminVO.php');
require_once (GWPM_APPLICATION_URL . DS . 'vos' . DS . 'GwpmNotificationVO.php');

if (class_exists('GenieWPMatrimonyController') && !$genieWPMatrimonyController) {
	$genieWPMatrimonyController = new GenieWPMatrimonyController();
}

if(class_exists('GwpmActivityModel') && !$gwpm_activity_model) {
	$gwpm_activity_model = new GwpmActivityModel() ;
}

if(class_exists('GwpmSetupModel') && !$gwpm_setup_model) {
	$gwpm_setup_model = new GwpmSetupModel() ;
}

register_activation_hook(__FILE__, 'activate_gwpm_plugin');
register_deactivation_hook(__FILE__, 'deactivate_gwpm_plugin');

function activate_gwpm_plugin() {
	/* global $gwpm_setup_model;
	if(class_exists('GwpmSetupModel') && !$gwpm_setup_model) {
		$gwpm_setup_model = new GwpmSetupModel() ;
	}
	$gwpm_setup_model->gwpm_update_db_check() ; */
}

function deactivate_gwpm_plugin() {
	/* global $gwpm_setup_model;
	if(class_exists('GwpmSetupModel') && !$gwpm_setup_model) {
		$gwpm_setup_model = new GwpmSetupModel() ;
	}
	$gwpm_setup_model->removeGWPMDetails(); */
}

?>