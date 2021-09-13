<?php
/**
 * This file is part of the Universe package.
 *
 * @author Volkan Şengül <iletisim@volkansengul.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Universe\Telescope;

use Universe\Asteroids\Asteroids;
use Universe\Logger\CaptainsLog as captainsLog;
use Universe\Signalling\Signal as Signal;
use Universe\Starship\Starship;

final class RestApi
{

    private $statusCode = 200;
    private $contentType = "application/json";
    private $output;
    private $body;

    public static $errors;


    public function __construct($body=null)
    {
        captainsLog::add('info', 'Rest Api ready!');
        captainsLog::add('info', 'Journey lets begin!');
        $this->init($body);
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

    private function entityResponse($body){
        if (is_object($body) && get_class($body)!=='stdClass'){
            $response = [];
            $className = get_class($body);
            if ( substr($className,0, 10) === 'App\Entity') {
                foreach((array)$body as $k=>$v){
                    $column = trim(str_replace($className,'',$k));

                    $_gs = $this->columnGetterSetter($column);
                    $getter = $_gs->getter;
                    $setter = $_gs->setter;

                    if (method_exists($className,$getter)){
                        $response[$column] = $v;
                    }
                }
            }
        } else {
            $response = $body;
        }
        return $response;
    }

    private function init($body)
    {
        $this->output = (object)[
            'result'=>null,
            'message'=>null,
            'success'=>false,
            'errors'=>self::$errors
        ];
        if (is_array($body)){
            $response = [];
            foreach($body as $_k=>$_b){
                $response[$_k] = $this->entityResponse($_b);
            }
            $body = $response;
        } else {
            $body = $this->entityResponse($body);
        }
        $this->body = $body;
    }

    private function setHeaders(){
        header("HTTP/1.1 ".$this->statusCode." ".$this->getStatusMessage());
        header("Content-Type:".$this->contentType);
    }

    private function getStatusMessage(){
        $status = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported');
        return ($status[$this->statusCode])?$status[$this->statusCode]:$status[500];
    }

    public function response($status=200, $success=true){
        if (self::$errors!==null && is_array(self::$errors) && count(self::$errors)>0){
            $this->statusCode = 400;
            $this->output->success = false;
        } else {
            $this->statusCode = ($status)??200;
            $this->output->success = $success;
            $this->output->result = $this->body;
        }
        $this->output->errors = self::$errors;
        $this->output->message = null;
        $this->setHeaders();
        return $this->output;
    }

}