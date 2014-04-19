<?php

/** Configuration Variables **/

define('DEVELOPMENT_ENVIRONMENT', false);
define('GWPM_ENABLE_DEBUGGING', false);

define('GWPM_APPLICATION_URL', GWPM_ROOT . DS . 'application');
define('GWPM_LIBRARY_URL', GWPM_ROOT . DS . 'library');
define('GWPM_LOG_URL', GWPM_ROOT . DS . 'logs');
define('GWPM_PUBLIC_URL', GWPM_CONTENT_URL . URL_S . 'public');
define('GWPM_PUBLIC_CSS_URL', GWPM_PUBLIC_URL . URL_S . 'css');
define('GWPM_PUBLIC_JS_URL', GWPM_PUBLIC_URL . URL_S . 'js');
define('GWPM_PUBLIC_IMG_URL', GWPM_PUBLIC_URL . URL_S . 'images');
define('GWPM_PAGE_TITLE', 'Matrimony');
define('GWPM_META_KEY', 'genie_wp_matrimony');
define('GWPM_META_VALUE', 'matrimony_page_meta');
define('GWPM_USER_ROLE', 'matrimony_user');
define('GWPM_USER_PREFIX', 'gwpm_');
define('GWPM_GALLERY_DIR', WP_CONTENT_DIR . URL_S . 'uploads' . URL_S . 'gwpm_gallery');
define('GWPM_GALLERY_URL', WP_CONTENT_URL . URL_S . 'uploads' . URL_S . 'gwpm_gallery');
define("GWPM_IMAGE_MAX_SIZE", "500");
define("GWPM_GALLERY_MAX_IMAGES", "12");
define("GWPM_CONVERSE_MAX_NOS", "10");
define("GWPM_ACTIVITY_MAX_NOS", "10");
define("GWPM_DYNA_KEY_PREFIX", "gwpm_dyna_field_");
define("GWPM_DYNA_FIELD_COUNT", "gwpm_dyna_field_count");
define("GWPM_AVATAR", "gwpm_avatar");
define("GWPM_MAX_USER_MESSAGES", 5);

$gwpm_db_version = 0.1;

/*
define('DB_NAME', 'yourdatabasename');
define('DB_USER', 'yourusername');
define('DB_PASSWORD', 'yourpassword');
define('DB_HOST', 'localhost');
*/