<?php

/**
 * SafeQueryGuard — Whitelist-based protection for dynamic SQL in ExtJS grid/combo endpoints.
 *
 * The mkGrid and mkCombo endpoints accept table names, field names, and sort directions
 * from POST parameters. This class validates those values against known-safe identifiers
 * to prevent SQL injection.
 */
class SafeQueryGuard
{
    private static $allowedSortDirs = ['ASC', 'DESC', 'asc', 'desc'];

    private static $allowedTables = null;

    private static function loadAllowedTables()
    {
        if (self::$allowedTables !== null) {
            return;
        }

        $configFile = ROOT . '/includes/allowed_tables.json';
        if (file_exists($configFile)) {
            $data = json_decode(file_get_contents($configFile), true);
            self::$allowedTables = $data ?: [];
        } else {
            self::$allowedTables = [];
        }
    }

    /**
     * Validate a SQL identifier (table or column name).
     * Only allows alphanumeric, underscores, dots (for db.table), and backtick-wrapped names.
     */
    public static function isValidIdentifier($value)
    {
        if (empty($value) || !is_string($value)) {
            return false;
        }
        $cleaned = trim(str_replace('`', '', $value));
        return (bool) preg_match('/^[a-zA-Z_][a-zA-Z0-9_.]*$/', $cleaned);
    }

    /**
     * Validate that a comma-separated list of field names contains only safe identifiers.
     */
    public static function validateFields($fields)
    {
        if (empty($fields)) {
            return false;
        }
        $parts = array_map('trim', explode(',', $fields));
        foreach ($parts as $part) {
            $field = preg_replace('/\s+(as|AS)\s+\w+$/', '', $part);
            $field = trim($field);
            if ($field === '*') {
                continue;
            }
            if (!self::isValidIdentifier($field)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Validate a table name against the whitelist (if configured) and identifier format.
     */
    public static function validateTable($table)
    {
        if (!self::isValidIdentifier($table)) {
            return false;
        }

        self::loadAllowedTables();
        if (!empty(self::$allowedTables)) {
            $cleanTable = strtolower(trim(str_replace('`', '', $table)));
            return in_array($cleanTable, self::$allowedTables, true);
        }

        return true;
    }

    /**
     * Validate sort direction.
     */
    public static function validateSortDir($dir)
    {
        return in_array($dir, self::$allowedSortDirs, true);
    }

    /**
     * Sanitize a value for use in a LIKE clause.
     */
    public static function escapeLike($value, $db)
    {
        if (is_object($db) && method_exists($db, 'real_escape_string')) {
            return $db->real_escape_string($value);
        }
        return addslashes($value);
    }

    /**
     * Validate all parameters for mkGrid and return sanitized versions or false on failure.
     */
    public static function validateGridParams($post)
    {
        $type = $post['type'] ?? '';
        $fields = $post['fields'] ?? '';
        $sort = $post['sort'] ?? '';
        $dir = $post['dir'] ?? 'ASC';

        if ($type === 'dummy') {
            return ['type' => 'dummy', 'fields' => '', 'sort' => '', 'dir' => 'ASC'];
        }

        if (!self::validateTable($type)) {
            return false;
        }
        if (!self::validateFields($fields)) {
            return false;
        }
        if (!self::isValidIdentifier($sort)) {
            return false;
        }
        if (!self::validateSortDir($dir)) {
            return false;
        }

        return [
            'type' => $type,
            'fields' => $fields,
            'sort' => $sort,
            'dir' => strtoupper($dir),
        ];
    }

    /**
     * Validate all parameters for mkCombo and return sanitized versions or false on failure.
     */
    public static function validateComboParams($post)
    {
        $type = $post['type'] ?? '';
        $value = $post['value'] ?? '';
        $display = $post['display'] ?? '';
        $extraFields = $post['extraFields'] ?? '';

        if (!self::validateTable($type)) {
            return false;
        }
        if (!self::isValidIdentifier($value)) {
            return false;
        }
        if (!self::isValidIdentifier($display)) {
            return false;
        }
        if (!empty($extraFields) && !self::validateFields($extraFields)) {
            return false;
        }

        return [
            'type' => $type,
            'value' => $value,
            'display' => $display,
            'extraFields' => $extraFields,
        ];
    }

    /**
     * Abort with a JSON error response if validation fails.
     */
    public static function abort($message = 'Invalid parameters')
    {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => $message]);
        exit;
    }
}
