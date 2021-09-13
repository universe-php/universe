<?php
/**
 * This file is part of the Universe package.
 *
 * @author Volkan Şengül <iletisim@volkansengul.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Universe\Asteroids;

use Universe\Config\Config;
use Universe\Telescope\RestApi;
use Universe\Starship\Starship;
use Universe\Logger\CaptainsLog as captainsLog;


final class Asteroids extends \Exception implements \Throwable {

    private $debug = false;
    private $mute = false;
    private $conf;
    private static $errors = [];

    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        /*ini_set('display_errors',1);
        ini_set('display_startup_errors',1);
        error_reporting( E_ALL );*/

        set_error_handler(array($this,'errorHandler'));
        set_exception_handler(array($this, 'exceptionHandler'));
        parent::__construct($message, $code, $previous);
        $this->conf = (object)Config::starship();
    }

    public function exceptionHandler($err){
        var_dump($err->getMessage());
        if ($this->conf->outputMode==='restApi'){
            RestApi::$errors[] = [
                'code'=>$err->getCode(),
                'message' => $err->getMessage()
            ];
        } else {
            echo '<div style="margin:30px auto; background:#181818; padding:20px; color:#fff;">Exception : <span style="color:#cccc00;">'.$err->getMessage().'</span></div>';
            //echo $err->getLine().'--'.$err->getFile();
        }
    }

    public function errorHandler($errNo = false, $errStr = false, $errFile = false, $errLine = false, $errContext = false){
       echo 'ERR';
       var_dump($errStr);
        if ($this->conf->outputMode==='restApi'){
            RestApi::$errors[] = ['code'=>$errNo, 'message'=>$errStr];
        } else {
            echo 'Err Handler :'.$errStr.'-'.$errNo.'- Line:'.$errLine.'--'.$errFile;
            echo '<hr>';
        }
    }




    /*public static function impact($err,$code){
        Starship::$errors[] = ['code'=>$code, 'message'=>$err];
    }*/

    //public static function impact($err){
        //echo '<div style="margin:30px auto; background:#181818; padding:20px; color:#fff;">Exception : <span style="color:#cccc00;">'.$err->getMessage().'</span></div>';
    //}



    public function info(){
        $this->showError();
    }

    public function warning(){
        $this->showError();
    }

    public function critical(){
        $this->showError();
    }

    public function fail(){
        $this->showError();
        //$this->eject();
    }

    /*
    public function impact(){
        $this->showError();
    }

    public function collision(){
        $this->showError();
        $this->eject();
    }*/

    private function eject(){
        /*if ($this->conf->outputMode==='restApi') {
            $restApi = new RestApi();
            $restApi->response(null, 200, false);
        }*/
        //exit();
    }

    private function showError(){
        if ($this->conf->outputMode==='restApi'){
            RestApi::$errors[] = ['code'=>$this->getCode(), 'message'=>$this->getMessage()];
//            self::$errors[] = $this->getMessage(); //['code'=>$code, 'message'=>$err];
            //Starship::$outputMessage = $this->getMessage();
        } else {
            echo '<div style="margin:30px auto; background:#181818; padding:20px; color:#fff;">Exception : <span style="color:#cccc00;">'.$this->getMessage().'</span></div>';
        }

    }



    // user error, continue
    //trigger_error('deneme');
    // stop
    //throw new Impact('--- hata abc',123);

}