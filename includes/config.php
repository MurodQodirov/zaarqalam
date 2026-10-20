<?php

declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));
define('DATA_PATH', ROOT_PATH . '/data');
define('UPLOAD_PATH', ROOT_PATH . '/public/uploads');

define('SITE_NAME', 'Zaarqalam');
define('SITE_URL', 'https://zaarqalam.uz');
define('SITE_LANG', 'uz');

date_default_timezone_set('Asia/Tashkent');

ini_set('display_errors', '0');
error_reporting(E_ALL);
