<?php
declare(strict_types=1);

namespace app\core;

// use Closure;
use PDO;
use Closure;
use PDOException;
use Exception;

/**
 * Database Class and Query Builder
 * @package  Kalipso
 * @author   koalapix <hello@koalapix.com>
 */

/**
 *
 * @property null cache
 */
class db
{
    private ?object $pdo;
    protected string $select = '*';
    protected ?string $from = null;
    protected ?string $where = null;
    protected ?int $limit = null;
    protected ?int $offset = null;
    protected ?string $join = null;
    protected ?string $orderBy = null;
    protected ?string $groupBy = null;
    protected ?string $having = null;
    protected ?bool $grouped = false;
    protected ?int $numRows = 0;
    protected ?int $insertId = null;
    protected ?string $query = null;
    protected ?string $error = null;
    protected $result = [];
    protected ?string $prefix = null;
    protected ?array $operators = ['=', '!=', '<', '>', '<=', '>=', '<>'];
    protected ?int $queryCount = 0;
    protected ?bool $debug = true;
    protected ?int $transactionCount = 0;
    protected ?string $cache = null;
	
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

    public function dbInit($schema): ?int
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
                        if (isset($attributes['type_values']) === false) $attributes['type_values'] = 11;
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

            $engine = isset($schema['table_values']['specific'][$table]['engine'])  !== false ?
                $schema['table_values']['specific'][$table]['engine']
                : $schema['table_values']['engine'];

            $charset = isset($schema['table_values']['specific'][$table]['charset'])  !== false ?
                $schema['table_values']['specific'][$table]['charset']
                : $schema['table_values']['charset'];

            $collate = isset($schema['table_values']['specific'][$table]['collate'])  !== false ?
                $schema['table_values']['specific'][$table]['collate']
                : $schema['table_values']['collate'];


            $sql .= PHP_EOL . ') ENGINE=' . $engine .
                ' DEFAULT CHARSET=' . $charset .
                ' COLLATE=' . $collate . ';' . PHP_EOL;

        }

        try {

            return $this->pdo->exec($sql);

        } catch(PDOException $e) {

            throw new Exception('DB Init action is not completed. ' . $e->getMessage());

        }

    }

    public function dbSeed($schema): ?int
    {

        $sql = '';


        foreach ($schema['data'] as $table => $data) {


            $values = '';
            $sql .= PHP_EOL . 'TRUNCATE `' . $table . '`;' . PHP_EOL . 'INSERT INTO `' . $table . '` (';

            $i = 0;
            foreach ($data as $row) {

                $values .= '(';
                $item = [];
                $i++;

                foreach ($row as $column => $value) {

                    if ($i == 1) $sql .= '`' . $column . '`, ';

                    if ($value === 'NULL') {
                        $value = 'NULL';
                    } elseif (is_numeric($value)) {
                        $value = '' . $value . '';
                    } else {
                        $value = '"' . $value . '"';
                    }

                    $item[] = $value;

                }

                $values .= implode(', ', $item) . '),' . PHP_EOL;

            }

            $sql = rtrim($sql, ', ' . PHP_EOL) . ') VALUES ' . PHP_EOL .
                rtrim($values, ',' . PHP_EOL) . '; ' . PHP_EOL;



        }

        try {

            return $this->pdo->exec($sql);

        } catch(PDOException $e) {

            throw new Exception('DB Seed action is not completed. ' . $e->getMessage());

        }

    }

    /**
     * @param array|string $fields
     *
     * @return $this
     */
    public function select($fields)
    {
        $select = is_array($fields) ? implode(', ', $fields) : $fields;
        $this->optimizeSelect($select);

        return $this;
    }

    /**
     * @param string $field
     * @param string|null $name
     *
     * @return $this
     */
    public function min(string $field, $name = null)
    {
        $column = 'MIN(' . $field . ')' . (!is_null($name) ? ' AS ' . $name : '');
        $this->optimizeSelect($column);

        return $this;
    }

    /**
     * @param string $field
     * @param string|null $name
     *
     * @return $this
     */
    public function max(string $field, $name = null)
    {
        $column = 'MAX(' . $field . ')' . (!is_null($name) ? ' AS ' . $name : '');
        $this->optimizeSelect($column);

        return $this;
    }

    /**
     * @param string $field
     * @param string|null $name
     *
     * @return $this
     */
    public function sum(string $field, $name = null)
    {
        $column = 'SUM(' . $field . ')' . (!is_null($name) ? ' AS ' . $name : '');
        $this->optimizeSelect($column);

        return $this;
    }

    /**
     * @param string $field
     * @param string|null $name
     *
     * @return $this
     */
    public function avg(string $field, $name = null)
    {
        $column = 'AVG(' . $field . ')' . (!is_null($name) ? ' AS ' . $name : '');
        $this->optimizeSelect($column);

        return $this;
    }

    /**
     * @param string $field
     * @param string|null $name
     *
     * @return $this
     */
    public function count(string $field, $name = null)
    {
        $column = 'COUNT(' . $field . ')' . (!is_null($name) ? ' AS ' . $name : '');
        $this->optimizeSelect($column);

        return $this;
    }


    public function join($table, $field1 = null, $operator = null, $field2 = null, $type = '')
    {
        $on = $field1;
        $table = $this->prefix . $table;

        if (!is_null($operator)) {
            $on = !in_array($operator, $this->operators)
                ? $field1 . ' = ' . $operator
                : $field1 . ' ' . $operator . ' ' . $field2;
        }

        $this->join = (is_null($this->join))
            ? ' ' . $type . 'JOIN' . ' ' . $table . ' ON ' . $on
            : $this->join . ' ' . $type . 'JOIN' . ' ' . $table . ' ON ' . $on;

        return $this;
    }

    /**
     * @param $table
     *
     * @return $this
     */
    public function table($table)
    {
        if (is_array($table)) {
            $from = '';
            foreach ($table as $key) {
                $from .= $this->prefix . $key . ', ';
            }
            $this->from = rtrim($from, ', ');
        } else {
            if (strpos($table, ',') > 0) {
                $tables = explode(',', $table);
                foreach ($tables as $key => &$value) {
                    $value = $this->prefix . ltrim($value);
                }
                $this->from = implode(', ', $tables);
            } else {
                $this->from = $this->prefix . $table;
            }
        }

        return $this;
    }

    /**
     * @param string $table
     * @param string $field1
     * @param string $operator
     * @param string $field2
     *
     * @return $this
     */
    public function innerJoin(string $table, string $field1, $operator = '', $field2 = '')
    {
        return $this->join($table, $field1, $operator, $field2, 'INNER ');
    }

    /**
     * @param string $table
     * @param string $field1
     * @param string $operator
     * @param string $field2
     *
     * @return $this
     */
    public function leftJoin(string $table, string $field1, $operator = '', $field2 = '')
    {
        return $this->join($table, $field1, $operator, $field2, 'LEFT ');
    }

    /**
     * @param string $table
     * @param string $field1
     * @param string $operator
     * @param string $field2
     *
     * @return $this
     */
    public function rightJoin(string $table, string $field1, $operator = '', $field2 = '')
    {
        return $this->join($table, $field1, $operator, $field2, 'RIGHT ');
    }

    /**
     * @param string $table
     * @param string $field1
     * @param string $operator
     * @param string $field2
     *
     * @return $this
     */
    public function fullOuterJoin(string $table, string $field1, $operator = '', $field2 = '')
    {
        return $this->join($table, $field1, $operator, $field2, 'FULL OUTER ');
    }

    /**
     * @param string $table
     * @param string $field1
     * @param string $operator
     * @param string $field2
     *
     * @return $this
     */
    public function leftOuterJoin(string $table, string $field1, $operator = '', $field2 = '')
    {
        return $this->join($table, $field1, $operator, $field2, 'LEFT OUTER ');
    }

    /**
     * @param string $table
     * @param string $field1
     * @param string $operator
     * @param string $field2
     *
     * @return $this
     */
    public function rightOuterJoin(string $table, string $field1, $operator = '', $field2 = '')
    {
        return $this->join($table, $field1, $operator, $field2, 'RIGHT OUTER ');
    }

    /**
     * @param array|string $where
     * @param string       $operator
     * @param string       $val
     * @param string       $type
     * @param string       $andOr
     *
     * @return $this
     */
    public function where($where, $operator = null, $val = null, $type = '', $andOr = 'AND')
    {
        if (is_array($where) && !empty($where)) {
            $_where = [];
            foreach ($where as $column => $data) {
                $_where[] = $type . $column . '=' . $this->escape($data);
            }
            $where = implode(' ' . $andOr . ' ', $_where);
        } else {
            if (is_null($where) || empty($where)) {
                return $this;
            }

            if (is_array($operator)) {
                $params = explode('?', $where);
                $_where = '';
                foreach ($params as $key => $value) {
                    if (!empty($value)) {
                        $_where .= $type . $value . (isset($operator[$key]) ? $this->escape($operator[$key]) : '');
                    }
                }
                $where = $_where;
            } elseif (!in_array($operator, $this->operators) || $operator == false) {
                $where = $type . $where . ' = ' . $this->escape($operator);
            } else {
                $where = $type . $where . ' ' . $operator . ' ' . $this->escape($val);
            }
        }

        if ($this->grouped) {
            $where = '(' . $where;
            $this->grouped = false;
        }

        $this->where = is_null($this->where)
            ? $where
            : $this->where . ' ' . $andOr . ' ' . $where;

        return $this;
    }

    /**
     * @param array|string $where
     * @param string|null  $operator
     * @param string|null  $val
     *
     * @return $this
     */
    public function orWhere($where, $operator = null, $val = null)
    {
        return $this->where($where, $operator, $val, '', 'OR');
    }

    /**
     * @param array|string $where
     * @param string|null  $operator
     * @param string|null  $val
     *
     * @return $this
     */
    public function notWhere($where, $operator = null, $val = null)
    {
        return $this->where($where, $operator, $val, 'NOT ');
    }

    /**
     * @param array|string $where
     * @param string|null  $operator
     * @param string|null  $val
     *
     * @return $this
     */
    public function orNotWhere($where, $operator = null, $val = null)
    {
        return $this->where($where, $operator, $val, 'NOT ', 'OR');
    }

    /**
     * @param string $where
     * @param bool $not
     *
     * @return $this
     */
    public function whereNull(string $where, $not = false)
    {
        $where = $where . ' IS ' . ($not ? 'NOT' : '') . ' NULL';
        $this->where = is_null($this->where) ? $where : $this->where . ' ' . 'AND ' . $where;

        return $this;
    }

    /**
     * @param string $where
     *
     * @return $this
     */
    public function whereNotNull(string $where)
    {
        return $this->whereNull($where, true);
    }

    /**
     * @param Closure $obj
     *
     * @return $this
     */
    public function grouped(Closure $obj)
    {
        $this->grouped = true;
        call_user_func_array($obj, [$this]);
        $this->where .= ')';

        return $this;
    }

    /**
     * @param string $field
     * @param array $keys
     * @param string $type
     * @param string $andOr
     *
     * @return $this
     */
    public function in(string $field, array $keys, $type = '', $andOr = 'AND')
    {
        if (is_array($keys)) {
            $_keys = [];
            foreach ($keys as $k => $v) {
                $_keys[] = is_numeric($v) ? $v : $this->escape($v);
            }
            $where = $field . ' ' . $type . 'IN (' . implode(', ', $_keys) . ')';

            if ($this->grouped) {
                $where = '(' . $where;
                $this->grouped = false;
            }

            $this->where = is_null($this->where)
                ? $where
                : $this->where . ' ' . $andOr . ' ' . $where;
        }

        return $this;
    }

    /**
     * @param string $field
     * @param array $keys
     *
     * @return $this
     */
    public function notIn(string $field, array $keys)
    {
        return $this->in($field, $keys, 'NOT ');
    }

    /**
     * @param string $field
     * @param array $keys
     *
     * @return $this
     */
    public function orIn(string $field, array $keys)
    {
        return $this->in($field, $keys, '', 'OR');
    }

    /**
     * @param string $field
     * @param array $keys
     *
     * @return $this
     */
    public function orNotIn(string $field, array $keys)
    {
        return $this->in($field, $keys, 'NOT ', 'OR');
    }

    /**
     * @param string $field
     * @param string|int $value1
     * @param string|int $value2
     * @param string $type
     * @param string $andOr
     *
     * @return $this
     */
    public function between(string $field, $value1, $value2, $type = '', $andOr = 'AND')
    {
        $where = '(' . $field . ' ' . $type . 'BETWEEN ' . ($this->escape($value1) . ' AND ' . $this->escape($value2)) . ')';
        if ($this->grouped) {
            $where = '(' . $where;
            $this->grouped = false;
        }

        $this->where = is_null($this->where)
            ? $where
            : $this->where . ' ' . $andOr . ' ' . $where;

        return $this;
    }

    /**
     * @param string $field
     * @param string|int $value1
     * @param string|int $value2
     *
     * @return $this
     */
    public function notBetween(string $field, $value1, $value2)
    {
        return $this->between($field, $value1, $value2, 'NOT ');
    }

    /**
     * @param string $field
     * @param string|int $value1
     * @param string|int $value2
     *
     * @return $this
     */
    public function orBetween(string $field, $value1, $value2)
    {
        return $this->between($field, $value1, $value2, '', 'OR');
    }

    /**
     * @param string $field
     * @param string|int $value1
     * @param string|int $value2
     *
     * @return $this
     */
    public function orNotBetween(string $field, $value1, $value2)
    {
        return $this->between($field, $value1, $value2, 'NOT ', 'OR');
    }

    /**
     * @param string $field
     * @param string $data
     * @param string $type
     * @param string $andOr
     *
     * @return $this
     */
    public function like(string $field, string $data, $type = '', $andOr = 'AND')
    {
        $like = $this->escape($data);
        $where = $field . ' ' . $type . 'LIKE ' . $like;

        if ($this->grouped) {
            $where = '(' . $where;
            $this->grouped = false;
        }

        $this->where = is_null($this->where)
            ? $where
            : $this->where . ' ' . $andOr . ' ' . $where;

        return $this;
    }

    /**
     * @param string $field
     * @param string $data
     *
     * @return $this
     */
    public function orLike(string $field, string $data)
    {
        return $this->like($field, $data, '', 'OR');
    }

    /**
     * @param string $field
     * @param string $data
     *
     * @return $this
     */
    public function notLike(string $field, string $data)
    {
        return $this->like($field, $data, 'NOT ');
    }

    /**
     * @param string $field
     * @param string $data
     *
     * @return $this
     */
    public function orNotLike(string $field, string $data)
    {
        return $this->like($field, $data, 'NOT ', 'OR');
    }

    /**
     * @param int $limit
     * @param int|null $limitEnd
     *
     * @return $this
     */
    public function limit(int $limit, $limitEnd = null)
    {
        $this->limit = !is_null($limitEnd)
            ? $limit . ', ' . $limitEnd
            : $limit;

        return $this;
    }

    /**
     * @param int $offset
     *
     * @return $this
     */
    public function offset(int $offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @param int $perPage
     * @param int $page
     *
     * @return $this
     */
    public function pagination(int $perPage, int $page)
    {
        $this->limit = $perPage;
        $this->offset = (($page > 0 ? $page : 1) - 1) * $perPage;

        return $this;
    }

    /**
     * @param string $orderBy
     * @param string|null $orderDir
     *
     * @return $this
     */
    public function orderBy(string $orderBy, $orderDir = null)
    {
        if (!is_null($orderDir)) {
            $this->orderBy = $orderBy . ' ' . strtoupper($orderDir);
        } else {
            $this->orderBy = stristr($orderBy, ' ') || strtolower($orderBy) === 'rand()'
                ? $orderBy
                : $orderBy . ' ASC';
        }

        return $this;
    }

    /**
     * @param string|array $groupBy
     *
     * @return $this
     */
    public function groupBy($groupBy)
    {
        $this->groupBy = is_array($groupBy) ? implode(', ', $groupBy) : $groupBy;

        return $this;
    }

    /**
     * @param string $field
     * @param string|array|null $operator
     * @param string|null $val
     *
     * @return $this
     */
    public function having(string $field, $operator = null, $val = null)
    {
        if (is_array($operator)) {
            $fields = explode('?', $field);
            $where = '';
            foreach ($fields as $key => $value) {
                if (!empty($value)) {
                    $where .= $value . (isset($operator[$key]) ? $this->escape($operator[$key]) : '');
                }
            }
            $this->having = $where;
        } elseif (!in_array($operator, $this->operators)) {
            $this->having = $field . ' > ' . $this->escape($operator);
        } else {
            $this->having = $field . ' ' . $operator . ' ' . $this->escape($val);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function numRows()
    {
        return $this->numRows;
    }

    /**
     * @return int|null
     */
    public function insertId()
    {
        return $this->insertId;
    }

    /**
     * @throw PDOException
     */
    public function error()
    {
        $msg = '<h1>Database Error</h1>';
        $msg .= '<h4>Query: <em style="font-weight:normal;">"' . $this->query . '"</em></h4>';
        $msg .= '<h4>Error: <em style="font-weight:normal;">' . $this->error . '</em></h4>';

        if ($this->debug === true) {
            if (php_sapi_name() === 'cli') {
                die("Query: " . $this->query . PHP_EOL . "Error: " . $this->error . PHP_EOL);
            }
            die($msg);
        }

        throw new PDOException($this->error . '. (' . $this->query . ')');
    }

    /**
     * @param string|bool $type
     * @param string|null $argument
     *
     * @return mixed
     */
    public function get($type = null, $argument = null)
    {
        $this->limit = 1;
        $query = $this->getAll(true);
        return $type === true ? $query : $this->query($query, false, $type, $argument);
    }

    /**
     * @param bool|string $type
     * @param string|null $argument
     *
     * @return mixed
     */
    public function getAll($type = null, $argument = null)
    {
        $query = 'SELECT ' . $this->select . ' FROM ' . $this->from;

        if (!is_null($this->join)) {
            $query .= $this->join;
        }

        if (!is_null($this->where)) {
            $query .= ' WHERE ' . $this->where;
        }

        if (!is_null($this->groupBy)) {
            $query .= ' GROUP BY ' . $this->groupBy;
        }

        if (!is_null($this->having)) {
            $query .= ' HAVING ' . $this->having;
        }

        if (!is_null($this->orderBy)) {
            $query .= ' ORDER BY ' . $this->orderBy;
        }

        if (!is_null($this->limit)) {
            $query .= ' LIMIT ' . $this->limit;
        }

        if (!is_null($this->offset)) {
            $query .= ' OFFSET ' . $this->offset;
        }

        return $type === true ? $query : $this->query($query, true, $type, $argument);
    }

    /**
     * @param array $data
     * @param bool  $type
     *
     * @return bool|string|int|null
     */
    public function insert(array $data, $type = false)
    {
        $query = 'INSERT INTO ' . $this->from;

        $values = array_values($data);
        if (isset($values[0]) && is_array($values[0])) {
            $column = implode(', ', array_keys($values[0]));
            $query .= ' (' . $column . ') VALUES ';
            foreach ($values as $value) {
                $val = implode(', ', array_map([$this, 'escape'], $value));
                $query .= '(' . $val . '), ';
            }
            $query = trim($query, ', ');
        } else {
            $column = implode(', ', array_keys($data));
            $val = implode(', ', array_map([$this, 'escape'], $data));
            $query .= ' (' . $column . ') VALUES (' . $val . ')';
        }

        if ($type === true) {
            return $query;
        }

        if ($this->query($query, false)) {
            $this->insertId = (integer) $this->pdo->lastInsertId();
            return $this->insertId();
        }

        return false;
    }

    /**
     * @param array $data
     * @param bool  $type
     *
     * @return mixed|string
     */
    public function update(array $data, $type = false)
    {
        $query = 'UPDATE ' . $this->from . ' SET ';
        $values = [];

        foreach ($data as $column => $val) {
            $values[] = $column . '=' . $this->escape($val);
        }
        $query .= implode(',', $values);

        if (!is_null($this->where)) {
            $query .= ' WHERE ' . $this->where;
        }

        if (!is_null($this->orderBy)) {
            $query .= ' ORDER BY ' . $this->orderBy;
        }

        if (!is_null($this->limit)) {
            $query .= ' LIMIT ' . $this->limit;
        }

        return $type === true ? $query : $this->query($query, false);
    }

    /**
     * @param bool $type
     *
     * @return mixed|string
     */
    public function delete($type = false)
    {
        $query = 'DELETE FROM ' . $this->from;

        if (!is_null($this->where)) {
            $query .= ' WHERE ' . $this->where;
        }

        if (!is_null($this->orderBy)) {
            $query .= ' ORDER BY ' . $this->orderBy;
        }

        if (!is_null($this->limit)) {
            $query .= ' LIMIT ' . $this->limit;
        }

        if ($query === 'DELETE FROM ' . $this->from) {
            $query = 'TRUNCATE TABLE ' . $this->from;
        }

        return $type === true ? $query : $this->query($query, false);
    }

    /**
     * @return mixed
     */
    public function analyze()
    {
        return $this->query('ANALYZE TABLE ' . $this->from, false);
    }

    /**
     * @return mixed
     */
    public function check()
    {
        return $this->query('CHECK TABLE ' . $this->from, false);
    }

    /**
     * @return mixed
     */
    public function checksum()
    {
        return $this->query('CHECKSUM TABLE ' . $this->from, false);
    }

    /**
     * @return mixed
     */
    public function optimize()
    {
        return $this->query('OPTIMIZE TABLE ' . $this->from, false);
    }

    /**
     * @return mixed
     */
    public function repair()
    {
        return $this->query('REPAIR TABLE ' . $this->from, false);
    }

    /**
     * @return bool
     */
    public function transaction()
    {
        if (!$this->transactionCount++) {
            return $this->pdo->beginTransaction();
        }

        $this->pdo->exec('SAVEPOINT trans' . $this->transactionCount);
        return $this->transactionCount >= 0;
    }

    /**
     * @return bool
     */
    public function commit()
    {
        if (!--$this->transactionCount) {
            return $this->pdo->commit();
        }

        return $this->transactionCount >= 0;
    }

    /**
     * @return bool
     */
    public function rollBack()
    {
        if (--$this->transactionCount) {
            $this->pdo->exec('ROLLBACK TO trans' . ($this->transactionCount + 1));
            return true;
        }

        return $this->pdo->rollBack();
    }

    /**
     * @return mixed
     */
    public function exec()
    {
        if (is_null($this->query)) {
            return null;
        }

        $query = $this->pdo->exec($this->query);
        if ($query === false) {
            $this->error = $this->pdo->errorInfo()[2];
            $this->error();
        }

        return $query;
    }

    /**
     * @param string $type
     * @param string $argument
     * @param bool   $all
     *
     * @return mixed
     */
    public function fetch($type = null, $argument = null, $all = false)
    {
        if (is_null($this->query)) {
            return null;
        }

        $query = $this->pdo->query($this->query);
        if (!$query) {
            $this->error = $this->pdo->errorInfo()[2];
            $this->error();
        }

        $type = $this->getFetchType($type);
        if ($type === PDO::FETCH_CLASS) {
            $query->setFetchMode($type, $argument);
        } else {
            $query->setFetchMode($type);
        }

        $result = $all ? $query->fetchAll() : $query->fetch();
        $this->numRows = is_array($result) ? count($result) : 1;
        return $result;
    }

    /**
     * @param string $type
     * @param string $argument
     *
     * @return mixed
     */
    public function fetchAll($type = null, $argument = null)
    {
        return $this->fetch($type, $argument, true);
    }

    /**
     * @param string $query
     * @param array|bool $all
     * @param null $type
     * @param null $argument
     *
     * @return $this|mixed
     */
    public function query(string $query, $all = true, $type = null, $argument = null)
    {
        $this->reset();

        if (is_array($all) || func_num_args() === 1) {
            $params = explode('?', $query);
            $newQuery = '';
            foreach ($params as $key => $value) {
                if (!empty($value)) {
                    $newQuery .= $value . (isset($all[$key]) ? $this->escape($all[$key]) : '');
                }
            }
            $this->query = $newQuery;
            return $this;
        }

        $this->query = preg_replace('/\s\s+|\t\t+/', ' ', trim($query));
        $str = false;
        foreach (['select', 'optimize', 'check', 'repair', 'checksum', 'analyze'] as $value) {
            if (stripos($this->query, $value) === 0) {
                $str = true;
                break;
            }
        }

        $type = $this->getFetchType($type);
        $cache = false;
        if (!is_null($this->cache) && $type !== PDO::FETCH_CLASS) {
            $cache = $this->cache->getCache($this->query, $type === PDO::FETCH_ASSOC);
        }

        if (!$cache && $str) {
            $sql = $this->pdo->query($this->query);
            if ($sql) {
                $this->numRows = $sql->rowCount();
                if ($this->numRows > 0) {
                    if ($type === PDO::FETCH_CLASS) {
                        $sql->setFetchMode($type, $argument);
                    } else {
                        $sql->setFetchMode($type);
                    }
                    $this->result = $all ? $sql->fetchAll() : $sql->fetch();
                }

                if (!is_null($this->cache) && $type !== PDO::FETCH_CLASS) {
                    $this->cache->setCache($this->query, $this->result);
                }
                $this->cache = null;
            } else {
                $this->cache = null;
                $this->error = $this->pdo->errorInfo()[2];
                $this->error();
            }
        } elseif ((!$cache && !$str) || ($cache && !$str)) {
            $this->cache = null;
            $this->result = $this->pdo->exec($this->query);

            if ($this->result === false) {
                $this->error = $this->pdo->errorInfo()[2];
                $this->error();
            }
        } else {
            $this->cache = null;
            $this->result = $cache;
            $this->numRows = is_array($this->result) ? count($this->result) : ($this->result === '' ? 0 : 1);
        }

        $this->queryCount++;
        return $this->result;
    }

    /**
     * @param $data
     *
     * @return string
     */
    public function escape($data)
    {
        return $data === null ? 'NULL' : (
        is_int($data) || is_float($data) ? $data : $this->pdo->quote($data)
        );
    }

    /**
     * @return int
     */
    public function queryCount()
    {
        return $this->queryCount;
    }

    /**
     * @return string|null
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return void
     */
    protected function reset()
    {
        $this->select = '*';
        $this->from = null;
        $this->where = null;
        $this->limit = null;
        $this->offset = null;
        $this->orderBy = null;
        $this->groupBy = null;
        $this->having = null;
        $this->join = null;
        $this->grouped = false;
        $this->numRows = 0;
        $this->insertId = null;
        $this->query = null;
        $this->error = null;
        $this->result = [];
        $this->transactionCount = 0;
    }

    /**
     * @param  $type
     *
     * @return int
     */
    protected function getFetchType($type)
    {
        return $type === 'class'
            ? PDO::FETCH_CLASS
            : ($type === 'array'
                ? PDO::FETCH_ASSOC
                : PDO::FETCH_OBJ);
    }

    /**
     * Optimize Selected fields for the query
     *
     * @param string $fields
     *
     * @return void
     */
    private function optimizeSelect(string $fields)
    {
        $this->select = $this->select === '*'
            ? $fields
            : $this->select . ', ' . $fields;
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        $this->pdo = null;
    }

}