<?php
/**
 * This file is part of the Universe package.
 *
 * @author Volkan Şengül <iletisim@volkansengul.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Universe\Routing;

use Universe\Config\Config;
use Universe\Signalling\Signal;
use Universe\Logger\CaptainsLog;
use Universe\Universe;

final class Router
{

    /*private static $isReady = false;
    private static $routes = false;
    private static $stations = false;
    private static $permission = 'member';
    private $destination = false;
    private static $params = null;*/

    private static $routes = array();
    private static $routesWithArgument = array();
    private $controllers;

    public function __construct()
    {
        CaptainsLog::add('info', 'They want to do travelling interstellar.');
    }

    private function parser($text){
        $result = explode('@',$text);
        if (count($result)===2){
            return ['class'=>$result[0],'method'=>$result[1]];
        } else {
            return [];
        }
    }

    public static function findRoute($uri, $callback, $permission){

        self::$routes[$uri] = [
            'callback'=>$callback,
            'permission'=>$permission
        ];
        if ($callback['arguments']!==null){

            $_uri = preg_replace('/{+(.*?)}/', '([a-zA-Z0-9]+)', $uri);
            $_uri = '/'.str_replace('/','\\/',$_uri).'/';
            self::$routesWithArgument[$_uri] = $uri;
        }

        if (is_array($callback)){
            self::$routes[$uri]['callback'] = $callback['class'].'@'.$callback['method'];
            self::$routes[$uri] = array_merge(self::$routes[$uri],$callback);
        } else {
            $_callback = explode('@', self::$routes[$uri]['callback']);
            $_callback[0] .= 'Controller';
            self::$routes[$uri]['callback'] = implode('@',$_callback);
        }

/*
        if (self::$isReady!==true){
            if (is_callable($callback)) {
                $callback();
                self::$isReady = true;
            } elseif (Signal::get('_url') === $uri && strpos($callback, '@') !== false) {
                self::$stations = explode('@', $callback);
                self::$routes[$uri] = $callback;
                self::permission($permission);
                self::$isReady = true;
            } elseif (strpos($uri,'{')>-1){
                $regex = self::pattern($uri);
                $pattern = '/'.$regex->pattern.'/';
                preg_match($pattern,Signal::get('_url'),$matches);
                if (count($matches)>0 && $matches[0]===Signal::get('_url')){
                    self::$params = $matches[$regex->index];
                    self::$stations = explode('@', $callback);
                    self::$routes[$uri] = $callback;
                    self::permission($permission);
                    self::$isReady = true;
                }
            }
        }*/
    }

    public static function checkMethod($method){
        return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === $method;
    }

    public static function get($uri, $callback, $permission = ['public'])
    {
        if (self::checkMethod('GET')) {
            self::findRoute($uri,$callback,$permission);
        }
    }

    public static function post($uri, $callback, $permission = ['public'])
    {
        if (self::checkMethod('POST')) {
            self::findRoute($uri,$callback,$permission);
        }
    }
    public static function put($uri, $callback, $permission = ['public'])
    {
        if (self::checkMethod('PUT')) {
            self::findRoute($uri,$callback,$permission);
        }
    }
    public static function delete($uri, $callback, $permission = ['public'])
    {
        if (self::checkMethod('DELETE')) {
            self::findRoute($uri,$callback,$permission);
        }
    }

    private function scanControllers($dir=false){
        $files = [];
        $root = Universe::$roots->controller;
        $target = $dir?($root.$dir):$root;

        $controllers = array_diff(scandir($target),array('.', '..'));
        foreach($controllers as $controller){
            if (is_dir($root.($dir?($dir.DIRECTORY_SEPARATOR):'').$controller)){
                $scans = $this->scanControllers(($dir?($dir.DIRECTORY_SEPARATOR):'').$controller);
                $files = array_merge($files,$scans);
            } else {
                $controller = str_replace('.php','',$controller);
                $files[] = $dir
                    ?['ns'=>$dir,'class'=>$controller]
                    :['ns'=>null,'class'=>$controller];
            }
        }
        return $files;
    }

    public function annotations(){
        $controllers = $this->scanControllers();
        $routes = [];
        foreach($controllers as $controller){
            $rc = new \ReflectionClass('\\App\\Controller\\'
                .($controller['ns']!==null?($controller['ns'].'\\'):'')
                .$controller['class']);
            $methods = $rc->getMethods();
            $namespace = $rc->getNamespaceName();
            $prefix = '';

            $classDoc =  $rc->getDocComment();
            if (preg_match('/@Route\(\s*["\']([^\'"]*)["\'][^)]*\)/', $classDoc, $matches) === 1) {
                $prefix = $matches[1]??'';
            }
            foreach($methods as $m){
                if (preg_match('/@Route\(\s*["\']([^\'"]*)["\'][^)]*\)/', $m->getDocComment(), $matches) === 1) {
                    $routePath = $matches[1];
                    $route = $matches[0];

                    $method = ['GET'];
                    if (preg_match('/methods={([^}]*)}/', $route, $matches) === 1) {
                        $method = explode(',',$matches[1]);
                    }

                    $routeName = null;
                    if (preg_match('/name=[\'"]([\w\._-]+)["\']/', $route, $matches)) {
                        $routeName = $matches[1];
                    }

                    $routePath = $prefix.''.$routePath;
                    if (substr($routePath,0,1)==='/' && strlen($routePath)>1){
                        $_route = explode('/',$routePath);
                        unset($_route[0]);
                        $routePath = implode('/',$_route);
                    }

                    $arguments = null;
                    if (preg_match_all('/{+(.*?)}/', $routePath, $matches)){
                        $arguments = $matches[1];
                        /*foreach($arguments as $arg){
                            $routePath = str_replace('/{'.$arg.'}','',$routePath);
                        }*/
                    }

                    $reqs = null;
                    if (preg_match('/requirements={([^}]*)}/', $route, $matches)) {
                        $reqs = explode(',',$matches[1]);
                        foreach($reqs as $k=>$v){
                            $_v = explode('=',trim(str_replace("'","",str_replace('"','',$v))));
                            $reqs[$_v[0]] = $_v[1];
                            if ($k!==$_v[0]){
                                unset($reqs[$k]);
                            }
                        }
                    }

                    $permission = ['public'];
                    if (preg_match('/permission=\[([^}]*)\]/', $route, $matches) === 1) {
                        $permission = explode(',',str_replace(['\'','"'],'',$matches[1]));
                    }

                    $route = [
                        'name'=>$routeName,
                        'path'=>$routePath,
                        'namespace'=>$namespace,
                        'class'=>$controller['class'],
                        'method'=>$m->name,
                        'requirements'=>$reqs,
                        'arguments'=>$arguments,
                        'argumentValues'=>[],
                        'permission'=>$permission
                    ];
                    $routes[] = $route;
                    foreach($method as $_method){
                        $_method = trim(strtolower(str_replace("'","",str_replace('"', "", $_method))));
                        Router::$_method($routePath, $route, $permission);
                    }
                }

            }
        }
    }

    public function getDestination()
    {
        $destination = null;
        $url = $_REQUEST['_url']??null;

        $langUriPrefix = Config::starship('langUriPrefix');

        $_url = explode('/',$url);
        if (in_array($_url[0],array_keys($langUriPrefix))){
            Config::starship('lang',$_url[0]);
            array_shift($_url);
            $url = implode('/',$_url);
        }
        if (substr($url,0,1)==='/'){
            $url = substr($url,0,strlen($url)-1);
        }
        if (substr($url,-1)==='/'){
            $url = substr($url,0,strlen($url)-1);
        }
        if ($url===null || $url===''){
            $url = '/';
        }

        if (isset(self::$routes[$url])){
            if (!isset(self::$routes[$url]['class'])){
                $destination = array_merge(self::$routes[$url],$this->parser(self::$routes[$url]['callback']));
            } else {
                $destination = self::$routes[$url];
            }
        } else {
            $destination = ['class'=>'404'];
            $routeKeys = array_keys(self::$routesWithArgument);
            foreach($routeKeys as $key){
                if (preg_match($key, $url, $matches)){
                    array_shift($matches);

                    $_i = 0;
                    foreach(self::$routes[self::$routesWithArgument[$key]]['arguments'] as $_arg){
                        self::$routes[self::$routesWithArgument[$key]]['argumentValues'][$_arg] = $matches[$_i]??null;
                        $_i++;
                    }
                    //self::$routes[self::$routesWithArgument[$key]]['argumentValues'] = $matches;
                    $destination = self::$routes[self::$routesWithArgument[$key]];
                }
            }
        }
        return (object)$destination;

        /*
        if (self::$stations===false){
            $url = Signal::get('_url');
            self::$stations = explode('/', ($url ?? 'index'));
            CaptainsLog::add('info', 'First destinations was "' . $url . '"');
            $this->checkDestination($url);
        }*/

    }

}
