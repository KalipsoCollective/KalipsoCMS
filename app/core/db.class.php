<?php
declare(strict_types=1);

namespace app\core;

use Closure;
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
	
	function __construct()
	{
        $dsn = '';
        if (in_array(config('database.driver'), ['mysql', 'pgsql'])) {
            $dsn = config('database.driver') .
                ':host=' . str_replace(
                    ':' . config('database.port'), '', config('database.host')
                ) . ';'
                . (config('database.port') !== '' ? 'port=' . config('database.port') . ';' : '')
                . 'dbname=' . config('database.name');
        } elseif (config('database.driver') === 'sqlite') {
            $dsn = 'sqlite:' . config('database.name');
        } elseif (config('database.driver') === 'oracle') {
            $dsn = 'oci:dbname=' . config('database.host') . '/' . config('database.name');
        }

        try {
            $this->pdo = new PDO($dsn, config('database.user'), config('database.pass'));
            $this->pdo->exec(
                "SET NAMES '" . config('database.charset')
                . "' COLLATE '" . config('database.collation') . "'"
            );
            $this->pdo->exec("SET CHARACTER SET '" . config('database.charset') . "'");
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception('Cannot the connect to Database with PDO. ' . $e->getMessage());
        }

        return $this->pdo;
	}


}