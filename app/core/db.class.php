<?php
declare(strict_types=1);

namespace app\core;

// use Closure;
use PDO;
use PDOException;
use Exception;

/**
 * Database Class and Query Builder
 * @package  Kalipso
 * @author   koalapix <hello@koalapix.com>
 */

/**
 * 
 */
class db
{
    private object $pdo;
	
	public function __construct()
	{
        $dsn = config('database.driver') .
            ':host=' . str_replace(
                ':' . config('database.port'), '', config('database.host')
            ) . ';'
            . (config('database.port') !== '' ? 'port=' . config('database.port') . ';' : '')
            . 'dbname=' . config('database.name');

        try {

            $this->pdo = new PDO($dsn, config('database.user'), config('database.pass'));
            $this->pdo->exec(
                "SET NAMES '" . config('database.charset')
                . "' COLLATE '" . config('database.collation') . "'"
            );
            $this->pdo->exec("SET CHARACTER SET '" . config('database.charset') . "'");
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        } catch (PDOException $e) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new Exception('Cannot the connect to Database with PDO. ' . $e->getMessage());
        }

        return $this->pdo;
	}

    public function dbInit($schema) {

	    foreach ($schema['tables'] as $table => $columns) {

            $sql = 'DROP TABLE IF EXISTS `' . $table . '`;' . PHP_EOL;
            $sql .= 'CREATE TABLE `' . $table . '` (' . PHP_EOL;

            foreach ($columns['cols'] as $column => $attributes) {

                switch ($attributes) {

                }

                $sql .= '`' . $column . '` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,' . PHP_EOL;

            }

            $sql .= ') ENGINE=' . $schema['table_values']['engine'] .
                ' DEFAULT CHARSET=' . $schema['table_values']['charset'] .
                ' COLLATE=' . $schema['table_values']['collate'];

        }

	        /*
	         *
	         CREATE TABLE `bids` (
              `id` int(11) UNSIGNED NOT NULL,
              `title` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
              `type` varchar(80) COLLATE utf8mb4_unicode_520_ci NOT NULL,
              `revised_for` int(11) DEFAULT '0',
              `message` text COLLATE utf8mb4_unicode_520_ci,
              `file_id` int(10) UNSIGNED NOT NULL,
              `customer_id` int(10) UNSIGNED DEFAULT '0',
              `process_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
              `last_validity_date` varchar(80) COLLATE utf8mb4_unicode_520_ci NOT NULL,
              `created_at` varchar(80) COLLATE utf8mb4_unicode_520_ci NOT NULL,
              `created_by` int(11) NOT NULL,
              `status` enum('sent','viewed','deleted') COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'sent'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
	         */

        }

    }

}