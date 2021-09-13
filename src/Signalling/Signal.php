<?php
/**
 * This file is part of the Universe package.
 *
 * @author Volkan Şengül <iletisim@volkansengul.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Universe\Signalling;

use Universe\Starship\Starship;
use Universe\Asteroids\Asteroids;
use Universe\Telescope\RestApi;

final class Signal
{

    private static $headers;
    private static $requestParams;
    private static $requestRawParams;
    private static $status;

    private $validation;

    public function __construct($read=false)
    {
        $this->init('*');
        self::$headers = apache_request_headers();
    }

    public function __get($name)
    {
        if (isset(self::$requestParams->$name)){
            return self::$requestParams->$name;
        } else {
            return null;
        }
    }

    public function raw($key){
        return self::$requestRawParams->$key;
    }

    public function checkContentType(){
        return $_SERVER["CONTENT_TYPE"]??false;
    }

    public function validation($rules = false){
        $this->validation = new Validation();
        if ($rules!==false && is_array($rules)){
            foreach($rules as $rule){
                $this->validation->rule($rule,'required');
            }
        }
        return $this;
    }

    public function validate(){
        try {
            if (!$this->validation->validate()){
                RestApi::$errors['formValidation'] = $this->validation->errors();
                throw new Asteroids('Invalid request');
            } else {
                return true;
            }
        } catch (Asteroids $e){
            $e->fail();
            return false;
        }
    }

    public function getFormData(){
        foreach ($_REQUEST as $k=>$v){
            if ($k!=='_url'){
                $this->bind($k, $v);
            }
        }
    }

    public function getFileData(){

    }

    public function getJsonData(){
        if ($this->checkContentType() === 'application/json') {
            $data = file_get_contents('php://input');
            $data = json_decode($data,true);
            if (is_array($data)){
                foreach($data as $k=>$v){
                    $this->bind($k, $v);
                }
            }
        }
    }

    public function getXmlData(){

    }

    public static function dataSafe($data, $noHtml = true)
    {
        if ($noHtml) {
            if (is_array($data)){
                $_data = [];
                foreach($data as $key=>$row){
                    $row = htmlspecialchars($row, ENT_NOQUOTES, 'UTF-8'); //ENT_NOQUOTES ENT_QUOTES
                    $row = strip_tags($row);
                    $_data[$key] = $row;
                }
                $data = $_data;
            } else {

                $data = htmlspecialchars($data, ENT_NOQUOTES, 'UTF-8'); //ENT_NOQUOTES ENT_QUOTES
                $data = strip_tags($data);
            }
        } else {
            //$data = htmlspecialchars_decode($data,ENT_QUOTES);
        }
        return $data;
    }

    private function bind($key, $value, $safeMode=true)
    {
        if (is_array($value)){
            //self::$requestParams->$key = $this->dataSafe($value, $safeMode);
            self::$requestParams->$key = $value;
            self::$requestRawParams->$key = $value;
        } else {
            self::$requestParams->$key = trim($this->dataSafe($value, $safeMode));
            self::$requestRawParams->$key = $value;
        }
    }

    public function init($key = '*'){
        if (self::$status!=='ready'){
            self::$requestParams = (object)[];
            self::$requestRawParams = (object)[];
            $this->getJsonData();
            $this->getFormData();
            self::$status = 'ready';
        }
        // get Xml Data
        // get json Data
        // get form Data
    }























    public function getHeader($header=false)
    {
        if ($header!==false){
            switch($header){
                case 'authorization':
                    return isset(self::$headers[ucfirst($header)])?(str_replace('Bearer ','',self::$headers[ucfirst($header)])):false;
                default:
                    return isset(self::$headers[$header])?self::$headers[$header]:false;
            }
        } else {
            return false;
        }
    }












    // flash
    // redirect with input
    // except
    // old
    // cookie
    // files
    // hasfile
    // file is valid?
    // file move
    // path - req path
    // ajax - req ajax
    // is method ( post )
    // is ? (/admin/*) path check
    // request::url


}

?>