<?php

$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';
$db_name = getenv('DB_NAME') ?: 'grozeo_bizadmin';
$support_db = getenv('SUPPORT_DB_NAME') ?: $db_name;

define('DSN', "mysql://{$db_user}:{$db_pass}@{$db_host}/{$db_name}");
define('SUPPORTDSN', "mysql://{$db_user}:{$db_pass}@{$db_host}/{$support_db}");

define('MODULES_PATH', ROOT . "/modules");
define('CACHE_PATH', "/jscache");
define('DEFAULT_OPERATION', false);
define('EXTERNAL_LIBRARY_PATH', ROOT . '/finascop_config/lib.php');

define('MAIN_TITLE', getenv('MAIN_TITLE') ?: 'Grozeo Bizadmin');
define('VERSION_NO', '1.0.0');
define('SUPPORT_MAIL', getenv('SUPPORT_MAIL') ?: 'support@grozeo.com');

define('SES_SMTP_USERNAME', getenv('SES_SMTP_USERNAME') ?: '');
define('SES_SMTP_PASSWORD', getenv('SES_SMTP_PASSWORD') ?: '');
