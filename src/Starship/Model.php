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

class Model extends MySQL implements ModelInterface
{

    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $incrementing = true;

    /*protected $with = [];
    protected $withCount = [];

    protected $perPage = 10;

    protected $exists = false;
    protected $wasRecentlyCreated = false;
    protected $resolver;
    protected $dispatcher;

    protected $booted = [];
    protected $globalScopes = [];*/


    public $timestamp = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public function __construct(...$params)
    {
        parent::__construct(...$params);
        // $this->db = Drivermanager::connect('MySQL');
    }

    public function __set($key, $value)
    {
        if (in_array($key, $this->fillable)) {
            $this->attributes[$key] = $value;
        }
    }

    public function __get($key)
    {
        if (isset($this->attributes[$key])){
            return $this->attributes[$key];
        } else {
            return false;
        }

    }

    /**
     * @param $id
     * @return $this
     */
    public function find($id)
    {
        $this->where($this->primaryKey,$id);
        $row = $this->fetch(false);
        $this->attributes = ($row === false) ? [] : (array)$row;
        $this->original = ($row === false) ? [] : (array)$row;
        return $this;
    }

    public function get()
    {
        return $this->fetch();
    }

    public function all()
    {
        return $this->fetchAll();
    }

    /**
     *
     * @ORM\Entity(repositoryClass="MyProject\UserRepository")
     * @param bool $data
     * @return string
     */
    public function save($data=false)
    {
        $data = is_object($data) ? (array)$data : $data;
        if ($data != false && is_array($data)) {
            foreach ($data as $k => $v) {
                if (in_array($k, $this->fillable)) {
                    $this->attributes[$k] = $v;
                }
            }
        }
        if (isset($this->original[$this->primaryKey])){
            return $this->update($data);
        } else {
            return $this->insert($data);
        }


        //echo $this->primaryKey.':'.$this->original[$this->primaryKey];
        /*foreach ($this->attributes as $k => $v) {
            if (!isset($this->original[$k]) || (isset($this->original[$k]) && $this->original[$k] != $v)) {
                echo '--'.$k.':'.$v.'--';
            }
        }*/
    }


    /*
        public function get($id=false){
            return $this->sql('SELECT * FROM '.$this->table.' WHERE status=1 ORDER BY '.$this->primaryKey.' DESC ', [])
                ->fetch();
        }

        public function all(){
            return $this->db
                ->sql('SELECT * FROM '.$this->table.' WHERE status=1 ORDER BY '.$this->primaryKey.' DESC ', [])
                ->fetchAll();
        }


        public function insert($data){
            $columns = [];
            $values = [];
            if (is_object($data)){
                $data = (array)$data;
            }
            if (!is_array($data)){

            }

            foreach($data as $k=>$v){
                $columns[] = $k;
                $values[] = ':'.$k;
            }
            $columns = implode(',',$columns);
            $values = implode(',',$values);

            return $this->db
                ->sql('INSERT INTO '.$this->table.' ('.$columns.') VALUES ('.$values.')',
                    $data)
                ->insert();
        }

        public function update(){

        }

        public function delete(){

        }

        public function first(){

        }

        public function last(){

        }*/


    public function setTable($table){
        $this->table = $table;
    }

    public function columns($cols){
        $this->columns = $cols;
    }
}