<?php
/**
 * This file is part of the Universe package.
 *
 * @author Volkan Şengül <iletisim@volkansengul.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Universe\Starship;

use Universe\Db\MySQL;
use Universe\Db\DriverManager;

class Repository extends MySQL implements ModelInterface
{

    protected $entityName;
    protected $columnMap;

    protected $primaryKey;
    protected $keyType = 'int';
    protected $incrementing = true;

    private $arrayResult = [];

    public function __construct($entityClass = null, ...$params)
    {
        parent::__construct(...$params);

        if ($entityClass!==null){
            $this->entitiyReader($entityClass);
        }
    }

    private function entitiyReader($entityClass){
        $this->entityName = $entityClass;
        $_entityClass = new \ReflectionClass($entityClass);
        $_entityDoc = $_entityClass->getDocComment();

        $pattern = '/@([\\\\\w]+)\((?:|(.*?[]"}\w]))\)/';
        preg_match_all($pattern, $_entityDoc, $matches);
        if (is_array($matches) && isset($matches[1]) && is_array($matches[1])){
            $i = 0;
            foreach($matches[1] as $match){
                if ($match==='ORM\Table' && isset($matches[2])
                    && is_array($matches[2]) && isset($matches[2][$i])
                    && preg_match('/name=[\'"]([\w\._-]+)["\']/', $matches[2][$i], $ormTable)
                ){
                    $this->tableName = $ormTable[1];
                }
                $i++;
            }
        }
        $this->columnMap = [];
        $isPrimaryKey = false;
        foreach($_entityClass->getProperties() as $column){
            $colDoc = $column->getDocComment();

            preg_match_all($pattern, $colDoc, $colMatches);
            preg_match_all( '/@([\\\\\w]+)/', $colDoc, $colMatchesSingle);

            if (is_array($colMatchesSingle) && isset($colMatchesSingle[1])){
                foreach($colMatchesSingle[1] as $_cms){
                    if ($_cms === 'ORM\Id'){
                        $isPrimaryKey = true;
                    }
                }
            }

            if (is_array($colMatches) && isset($colMatches[1]) && is_array($colMatches[1])){
                $ci = 0;
                foreach($colMatches[1] as $cm){
                    if ($cm==='ORM\Column' && isset($colMatches[2])
                        && is_array($colMatches[2]) && isset($colMatches[2][$ci])
                        && preg_match('/name=[\'"]([\w\._-]+)["\']/', $colMatches[2][$ci], $_col)
                    ){
                        $this->columnMap[$column->name] = $_col[1];
                        if ($isPrimaryKey){
                            $this->primaryKey = $_col[1];
                            $isPrimaryKey = false;
                        }
                    }
                    $ci++;
                }
            }
        }
        $this->columns = implode(',',$this->columnMap);
    }

    private function columnGetterSetter($column){
        $k = explode('_',$column);
        $_k = [];
        foreach($k as $k_row){
            $_k[] = ucfirst($k_row);
        }
        $k = implode('',$_k);
        $row = [];
        $setter = 'set'.ucfirst($k);
        $getter = 'get'.ucfirst($k);
        return (object)['getter'=>$getter,'setter'=>$setter];
    }

    private function fillEntity($e){
        $pk = $this->primaryKey;
        $entity = new $this->entityName();
        $row = [];
        foreach($this->columnMap as $k=>$v){
            $_gs = $this->columnGetterSetter($k);
            $getter = $_gs->getter;
            $setter = $_gs->setter;
            if (method_exists($entity,$setter)){
                $entity->$setter($e->$v);
                $row[$k] = $entity->$getter($e->$v);
            }
        }
        return $entity;
    }

    public function getArrayResult(){
        return $this->arrayResult;
    }

    public function query($query){
        $this->sqlType = 'QUERY';
        $this->customQuery = $query;
        return $this;
    }

    public function find($id)
    {
        $this->where($this->primaryKey,$id);
        $this->collection = $this->fetch(false);
        if ($this->collection){
            return $this->fillEntity($this->collection);
        } else {
            return null;
        }

        //$this->attributes = ($row === false) ? [] : (array)$row;
        //$this->original = ($row === false) ? [] : (array)$row;
        //return $this;
    }

    public function get()
    {
        return $this->fetch();
    }

    public function all()
    {
        $this->collection = $this->fetchAll();
        $result = [];
        foreach($this->collection as $e){
            $result[] = $this->fillEntity($e);
        }
        return $result;
    }

    public function findAll(){
        return $this->all();
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null){
        $query = $this;
        foreach($criteria as $k=>$v){
            if (is_array($v)){
                $query = $query->whereIn($k,$v);
            } else {
                $query = $query->where($k,$v);
            }
        }
        $this->collection = $query->fetchAll();
        $result = [];
        foreach($this->collection as $e){
            $result[] = $this->fillEntity($e);
        }
        return $result;
    }

    public function findOneBy(array $criteria, array $orderBy = null){
        $query = $this;
        foreach($criteria as $k=>$v){
            if (is_array($v)){
                $query = $query->whereIn($k,$v);
            } else {
                $query = $query->where($k,$v);
            }
        }
        $this->collection = $query->fetch(false);
        if ($this->collection){
            return $this->fillEntity($this->collection);
        } else {
            return null;
        }
    }

    public function save($data){
        if ($this->collection){
            // update
            $_data = [];
            foreach($data as $k=>$v){
                $_data[$k] = $v;
            }
            return $this->update($_data);

        } else {
            // insert
            return $this->insert($data);
        }
    }

    public function delete(){
        $this->update(['is_deleted'=>1]);
    }

    public function clear(){
        $this->collection = null;
    }

    public function __call($name, $arguments)
    {

    }


}