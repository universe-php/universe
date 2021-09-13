<?php
/**
 * This file is part of the Universe package.
 *
 * @author Volkan Şengül <iletisim@volkansengul.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Universe\Db;

use Universe\Config\Config;
use Universe\Asteroids\Asteroids;

class MySQL
{

    private $pdo;
    protected $tableName;

    protected $sqlType = 'SELECT';
    protected $customQuery = null;
    protected $columns = '*';
    protected $sqlWhere = [];
    protected $sqlWhereComplex = '';
    protected $sqlData = [];
    protected $sqlOrder;
    protected $sqlGroup;
    protected $sqlLimit;

    protected $query;


    private static $isConnected = false;
    private static $connection = false;


    protected $fillable = [];
    protected $attributes = [];
    protected $original = [];

    protected $collection;

    public function __construct(...$params)
    {

        /*if (count($params) > 0) {
            $this->columns = implode(',', $params);
        }*/

        if (!self::$isConnected) {
            self::$connection = $this->connect();
            if (self::$connection !== false) {
                self::$isConnected = true;
            }
        }
        return $this;
    }

    private function connect()
    {
        try {
            $conf = (object)Config::database('master');
            $this->pdo = new \PDO('mysql:host=' . $conf->host . ';dbname=' . $conf->schema . ';charset=' . $conf->charset,
                $conf->user,
                $conf->password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (\PDOException $e) {
            //print_r($e);
            //exit();
            die('Db Connection Error');
        }
    }

    protected function reset()
    {
        $this->sqlType = 'SELECT';
        $this->columns = '*';
        $this->sqlWhere = [];
        $this->sqlData = [];
        $this->sqlOrder = null;
        $this->sqlGroup = null;
        $this->sqlLimit = null;
        $this->query = null;
        $this->attributes = [];
        $this->original = [];
    }

    public function queryBuilder()
    {
        if ($this->customQuery === null){
            $sql_query = $this->sqlType;

            switch ($this->sqlType) {
                case 'SELECT':
                    $sql_query .= ' ' . $this->columns . ' FROM ' . $this->tableName;
                    break;
                case 'INSERT':
                    $_cols = [];
                    foreach($this->sqlData as $_k=>$_v){
                        $_cols[] = ':' . $_k;
                    }
                    $sql_query .= ' INTO ' . $this->tableName . ' (' . implode(',', array_keys($this->sqlData)) . ') VALUES (' . implode(',', $_cols) . ')';
                    break;
                case 'UPDATE':
                    $_cols = [];
                    foreach($this->sqlData as $_k=>$_v){
                        $_cols[] = $_k . '=:' . $_k;
                    }
                    if (count($_cols) === 0) {
                        return $this;
                    }
                    $sql_query .= ' ' . $this->tableName . ' SET ' . implode(',', $_cols);
                    break;
                case 'DELETE':
                    $sql_query .= ' FROM ';
                    break;
                case 'SHOW TABLES':
                    break;
                case 'DESCRIBE':
                    $sql_query .= ' ' . $this->tableName;
                    break;
                case 'SP':
                    $sql_query = 'call ' . $this->tableName . '(' . implode(',', array_fill(0, count($this->sqlData), '?')) . ')';
                    break;
                default:
                    break;
            }


        } else {
            $sql_query = $this->customQuery;
        }

        $sql_query .= ' ' . ((count($this->sqlWhere) > 0) ? (' WHERE ' . implode(' ', $this->sqlWhere)) : '');
        if ($this->sqlWhereComplex!==''){
            $sql_query .= ' '.' WHERE '.$this->sqlWhereComplex . ' ';
        }

        $sql_query .= ' ' . ($this->sqlGroup ?? $this->sqlGroup);
        $sql_query .= ' ' . ($this->sqlOrder ?? $this->sqlOrder);
        $sql_query .= ' ' . ($this->sqlLimit ?? $this->sqlLimit);

        //echo $sql_query;
        try {
            $this->query = self::$connection->prepare($sql_query);
            $result = $this->query->execute($this->sqlData);
            //} catch (\PDOException $e) {
        } catch (Asteroids $e) {
            die(implode('<br>', $e->errorInfo));
        }
        return $this;
    }

    public function fetch($reset = true)
    {
        $return = $this->queryBuilder()->query->fetch(\PDO::FETCH_OBJ);
        if ($reset) {
            $this->reset();
        }
        return $return;
    }

    public function fetchAll()
    {
        $return = $this->queryBuilder()->query->fetchAll(\PDO::FETCH_OBJ);
        $this->reset();
        return $return;
    }

    public function call()
    {
        $return = $this->queryBuilder()->query->rowCount();
        $this->reset();
        return $return;
    }

    public function insert($data)
    {
        $this->sqlType = 'INSERT';
        /*foreach ($this->fillable as $field) {
            if (isset($this->attributes[$field])) {
                $this->sqlData[$field] = $this->attributes[$field];
            } else {
                $this->sqlData[$field] = null;
            }
        }*/
        $this->sqlData = $data;
        $this->sqlWhere = [];
        $this->queryBuilder();
        $this->reset();
        return self::$connection->lastInsertId();
    }

    public function update($data)
    {
        $this->sqlType = 'UPDATE';
        $data = array_merge($this->sqlData,$data);
        $this->sqlData = $data;
        $return = $this->queryBuilder()->query->rowCount();
        $this->reset();
        return $return;

        /*foreach ($this->fillable as $field) {
            if (
                $this->attributes[$field] != $this->original[$field]
            ) {
                $this->sqlData[$field] = $this->attributes[$field];
            }
        }*/

        /*$return = $this->queryBuilder()->query->rowCount();

        $this->reset();
        return $return;
        */
    }

    public function sp($sp)
    {
        $this->sqlType = 'SP';
        $this->tableName = $sp;
        return $this;
        //$return = $this->queryBuilder();
    }

    public function params($params)
    {
        $this->sqlData = $params;
        return $this;
    }


    public function tables()
    {
        $this->sqlType = 'SHOW TABLES';
        return $this->fetchAll(\PDO::FETCH_OBJ);
    }

    public function table()
    {
        $this->sqlType = 'DESCRIBE';
        return $this->fetchAll(\PDO::FETCH_OBJ);
    }

    public function where(...$params)
    {
        if (count($params) < 2) {
            return false;
        }
        $column = $params[0];
        $operator = '=';
        $value = $params[1];
        if (count($params) == 3) {
            $operator = $params[1];
            $value = $params[2];
        }
        $this->sqlWhere[] = (count($this->sqlWhere)>0?' AND ':'') . $column . $operator . ':' . $column;
        $this->sqlData[$column] = $value;
        return $this;
    }

    public function whereIn($key,$values){
        $in = [];
        $i = 0;
        foreach($values as $v){
            $this->sqlData['wherein'.$i] = $v;
            $in[] = ':wherein'.$i;
            $i++;
        }
        $in = implode(',',$in);
        $this->sqlWhere[] = (count($this->sqlWhere)>0?' AND ':'') . $key . ' IN ('.$in.')';
        return $this;
    }

    public function whereComplex($where){
        $this->sqlWhereComplex = $where;
        return $this;
    }


    public function orWhere(...$params){
        if (count($params) < 2) {
            return false;
        }
        $column = $params[0];
        $operator = '=';
        $value = $params[1];
        if (count($params) == 3) {
            $operator = $params[1];
            $value = $params[2];
        }
        $this->sqlWhere[] = ' OR ' . $column . $operator . ':' . $column;
        $this->sqlData[$column] = $value;
        return $this;
    }

    public function orderBy(...$params)
    {
        if (count($params) > 0) {
            $this->sqlOrder = 'ORDER BY ';
            $i = 0;
            foreach ($params as $param) {
                $this->sqlOrder .= $param;
                if (isset($params[$i + 1]) && ($params[$i + 1] == 'ASC' || $params[$i + 1] == 'DESC')) {
                    $this->sqlOrder .= ' ';
                } else {
                    $this->sqlOrder .= isset($params[$i + 1]) ? ',' : '';
                }
                $i++;
            }
        }
        return $this;
    }

    public function groupBy(...$params)
    {
        $this->sqlGroup = 'GROUP BY ' . implode(',', $params);
        return $this;
    }

    public function limit(...$params)
    {
        $this->sqlLimit = 'LIMIT ' . implode(',', $params);
        return $this;
    }


    /* public function sql($query,...$params){
         if (count($params)===1 && is_array($params[0])){
             $params = $params[0];
         }
         $this->query = $this->pdo->prepare($query);
         $this->query->execute($params);
         return $this;
     }*/


    public function delete()
    {


    }

    public function from()
    {

    }


    // selects & joins
    public function select()
    {
        return $this;
    }

    public function addSelect()
    {

    }

    public function join()
    {

    }

    public function leftJoin()
    {

    }

    public function rightJoin()
    {

    }

    public function innerJoin()
    {

    }

    public function sum($column){
        $this->columns = 'SUM('.$column.') as '.$column;
        return $this;
    }

    // wheres


    /*public function andWhere(){

    }

    public function orWhere(){

    }*/

}