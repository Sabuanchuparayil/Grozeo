<?php

if (!defined('DSN')) {
    $db_host = getenv('DB_HOST') ?: 'localhost';
    $db_user = getenv('DB_USER') ?: 'root';
    $db_pass = getenv('DB_PASS') ?: '';
    $db_name = getenv('DB_NAME') ?: 'grozeo_manage';

    define('DSN', "mysql://{$db_user}:{$db_pass}@{$db_host}/{$db_name}");
}

if (!defined('PARENTDSN')) {
    $parent_db = getenv('PARENT_DB_NAME') ?: 'grozeo_bizadmin';
    $db_host = getenv('DB_HOST') ?: 'localhost';
    $db_user = getenv('DB_USER') ?: 'root';
    $db_pass = getenv('DB_PASS') ?: '';

    define('PARENTDSN', "mysql://{$db_user}:{$db_pass}@{$db_host}/{$parent_db}");
}

if (!defined('SUPPORTDSN')) {
    $db_host = getenv('DB_HOST') ?: 'localhost';
    $db_user = getenv('DB_USER') ?: 'root';
    $db_pass = getenv('DB_PASS') ?: '';
    $db_name = getenv('DB_NAME') ?: 'grozeo_manage';

    define('SUPPORTDSN', "mysql://{$db_user}:{$db_pass}@{$db_host}/{$db_name}");
}
