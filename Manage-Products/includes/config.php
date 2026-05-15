<?php

$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';
$db_name = getenv('DB_NAME') ?: 'grozeo_manage';
$parent_db = getenv('PARENT_DB_NAME') ?: 'grozeo_bizadmin';

define('DSN', "mysql://{$db_user}:{$db_pass}@{$db_host}/{$db_name}");
define('PARENTDSN', "mysql://{$db_user}:{$db_pass}@{$db_host}/{$parent_db}");
define('SUPPORTDSN', "mysql://{$db_user}:{$db_pass}@{$db_host}/{$db_name}");

define('MODULES_PATH', ROOT . "/modules");
define('CACHE_PATH', "/jscache");
define('DEFAULT_OPERATION', false);
define('EXTERNAL_LIBRARY_PATH', ROOT . '/finascop_config/lib.php');

define('MAIN_TITLE', getenv('MAIN_TITLE') ?: 'Grozeo Manage Products');
define('VERSION_NO', '1.0.0');
define('SUPPORT_MAIL', getenv('SUPPORT_MAIL') ?: 'support@grozeo.com');

define('SES_SMTP_USERNAME', getenv('SES_SMTP_USERNAME') ?: '');
define('SES_SMTP_PASSWORD', getenv('SES_SMTP_PASSWORD') ?: '');
define('AWS_ACCESS_KEY_ID', getenv('AWS_ACCESS_KEY_ID') ?: '');
define('AWS_SECRET_ACCESS_KEY', getenv('AWS_SECRET_ACCESS_KEY') ?: '');
define('AWS_DEFAULT_REGION', getenv('AWS_DEFAULT_REGION') ?: 'ap-south-1');
