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

    public function dbInit($schema): bool
    {

        $sql = '';
        foreach ($schema['tables'] as $table => $columns) {

            $sql .= PHP_EOL . 'DROP TABLE IF EXISTS `' . $table . '`;' . PHP_EOL;
            $sql .= 'CREATE TABLE `' . $table . '` (';

            $externalParams = [];

            foreach ($columns['cols'] as $column => $attributes) {

                $type = '';

                switch ($attributes['type']) {
                    case 'int':
                        $type = 'int(' . $attributes['type_values'] . ')';
                        break;

                    case 'varchar':
                        $type = 'varchar(' . $attributes['type_values'] . ') COLLATE ' .
                            (isset($attributes['collate']) !== false ? $attributes['collate'] :
                                $schema['table_values']['collate']);

                        break;

                    case 'text':
                        $type = 'text' . ' COLLATE ' .
                            (isset($attributes['collate']) !== false ? $attributes['collate'] :
                                $schema['table_values']['collate']);
                        break;

                    case 'longtext':
                        $type = 'longtext' . ' COLLATE ' .
                            (isset($attributes['collate']) !== false ? $attributes['collate'] :
                                $schema['table_values']['collate']);
                        break;

                    case 'enum':
                        $type = "enum('" . implode("', '", $attributes['type_values']) . "')";
                        break;

                }

                if (isset($attributes['index']) !== false) {

                    switch ($attributes['index']) {
                        case 'PRIMARY':
                            $externalParams[] = 'PRIMARY KEY (`' . $column . '`)';
                            break;

                        case 'INDEX':
                            $externalParams[] = 'INDEX `' . $column . '` (`' . $column . '`)';
                            break;

                        case 'UNIQUE':
                            $externalParams[] = 'UNIQUE(`' . $column . '`)';
                            break;

                        case 'FULLTEXT':
                            $externalParams[] = 'FULLTEXT(`' . $column . '`)';
                            break;
                    }

                }

                if (isset($attributes['attr']) !== false) {

                    $type .= ' ' . $attributes['attr'];

                }

                if (isset($attributes['nullable']) === false OR ! $attributes['nullable']) {
                    $type .= ' NOT NULL';
                }

                if (isset($attributes['default']) !== false) {
                    switch ($attributes['default']) {
                        case 'NULL':
                        case 'CURRENT_TIMESTAMP':
                            $type .= ' DEFAULT ' . $attributes['default'];
                            break;
                        default:
                            $type .= ' DEFAULT \'' . $attributes['default'] . '\'';
                            break;
                    }
                }

                if (isset($attributes['auto_inc']) !== false AND $attributes['auto_inc']) {
                    $type .= ' AUTO_INCREMENT';
                }

                $sql .= PHP_EOL . '   `' . $column . '` '. $type .',';

            }

            if (count($externalParams)) {

                foreach ($externalParams as $param) {
                    $sql .= PHP_EOL . '   ' . $param . ',';
                }

            }

            $sql = rtrim($sql, ',' . PHP_EOL);

            $sql .= PHP_EOL . ') ENGINE=' . $schema['table_values']['engine'] .
                ' DEFAULT CHARSET=' . $schema['table_values']['charset'] .
                ' COLLATE=' . $schema['table_values']['collate'] . ';' . PHP_EOL;

        }

        try {

            $this->pdo->exec($sql);
            return true;

        } catch(PDOException $e) {

            throw new Exception('DB Init action is not completed. ' . $e->getMessage());
            return false;

        }

    }

    public function dbSeed($schema) {

        $sql = '';


        foreach ($schema['data'] as $table => $data) {



            $sql .= PHP_EOL . 'INSERT INTO `' . $table . '` (';

            $i = 0;
            foreach ($data as $row) {

                $values = '(';
                $item = [];
                $i++;

                foreach ($row as $column => $value) {

                    if ($i == 1) $sql .= '`' . $column . '`, ';

                    if ($value === 'NULL') {
                        $value = 'NULL';
                    } elseif (is_numeric($value)) {
                        $value = $value;
                    } else {
                        $value = '`' . $value . '`';
                    }

                    $item[] = $value;

                    // $sql .= PHP_EOL . '   `' . $column . '` ' . $type . ',';

                }

                $values .= implode(', ', $item) . ')';

            }

            $sql = rtrim($sql, ', ' . PHP_EOL) . ') VALUES ' . PHP_EOL . $values . '; ' . PHP_EOL;



        }

        varFuck($sql);

        try {

            // $this->pdo->exec($sql);
            return true;

        } catch(PDOException $e) {

            throw new Exception('DB Init action is not completed. ' . $e->getMessage());

        }

    }

}