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

use Universe\Asteroids\Asteroids;
use Universe\Config\Config;
use Universe\Routing\Router;
use Universe\Telescope\View;
use Universe\Telescope\RestApi;
use Universe\Logger\CaptainsLog as captainsLog;
use Universe\Signalling\Signal;
use Universe\Universe;

/**
 * Class Starship
 * @package Universe\Starship
 */
class Starship
{
    private $router;

    public function __construct()
    {
        captainsLog::add('info', 'Then they builded a starships for discovery the universe.');
        //spl_autoload_register(array($this, 'autoLoader'));
    }


    /**
     * @return $this
     */
    public function loadRoutes()
    {
        $this->router = new Router();
        $this->router->annotations();

        if (file_exists(Universe::$roots->routes)){
            $routes = array_diff(scandir(Universe::$roots->routes), array('.', '..'));
            foreach($routes as $r){
                require Universe::$roots->routes .DIRECTORY_SEPARATOR. $r;
            }
        }
        return $this;
    }

    /**
     * @param \ReflectionMethod $reflection
     * @return array
     */
    private function prepareArguments(\ReflectionMethod $reflection, $_arguments) : array {
        $args = $reflection->getParameters();
        $myArgs = [];
        foreach($args as $arg){
            $typeHint = $arg->getType();
            assert($typeHint instanceof \ReflectionNamedType);
            $typeHint = $typeHint->getName();
            if ($typeHint==='Universe\Signalling\Signal'){
                $myArgs[] = new $typeHint(true);
            } elseif(in_array($typeHint, ['int','string','boolean','double','string','array','object','resource',NULL,'unknown type'])){
                $myArgs[] = $_arguments[$arg->name];
            } else {
                $myArgs[] = new $typeHint();
            }
        }
        return $myArgs;
    }

    /**
     * @param string $_class
     * @param string $_method
     * @return mixed
     * @throws \ReflectionException
     */
    private function callClassMethod(string $_class, string $_method, array $_arguments = []){
        try {
            if (class_exists($_class)){
                if (method_exists($_class,$_method)){

                    $reflection = new \ReflectionMethod($_class, $_method);
                    $class = new $_class();
                    return $class->$_method(...$this->prepareArguments($reflection, $_arguments));
                } else {
                    Throw new Asteroids('method not found : '.$_method);
                }
            } else {
                Throw new Asteroids('class not found : '.$_class);
            }
        } catch(Asteroids $e){
            $e->fail();
        }
    }

    /**
     * @throws \ReflectionException
     */
    public function fire(){
        captainsLog::add('info', 'Engine is ready !');
        captainsLog::add('info', 'Navigation system is ready !');
        $autorun = '\\App\\Middleware\\Autorun';
        $destination = $this->router->getDestination();

        if (class_exists($autorun)){
            new $autorun($destination);
        }
        if ($destination->class==='404'){
            $result = $this->callClassMethod('\\Universe\\Starship\\Controller', 'notfound');
        } else {
            $result = $this->callClassMethod('\\'.$destination->namespace.'\\'.$destination->class, $destination->method, $destination->argumentValues);

            if (is_object($result) && get_class($result)==='Universe\Telescope\RestApi'){
                $output = $result->response();
                try {
                    $jsonOutput = json_encode($output);
                    if ( $output->result===null || $output->result===false || !$output->success){
                        Throw new Asteroids('Internal API Error');
                    } else {
                        echo $jsonOutput;
                    }
                } catch (Asteroids $e){
                    echo json_encode([
                        'result'=>null,
                        'success'=>false,
                        'message'=>null,
                        'errors'=>[
                            ['code'=>500, 'message'=>$e->getMessage()]
                        ]
                    ]);
                }
            } elseif (is_object($result) && get_class($result)==='Universe\Telescope\View'){
                $output = $result->render();
                echo $output;
            } else {

            }
        }
    }














    public static function env($key)
    {
        $keys = explode('.', $key);
        if (count($keys) === 1) {
            $env = Config::$starship;
        }
        foreach ($keys as $k) {
            if (isset($env->{$k})) {
                $env = $env->{$k};
            } elseif (is_array($env) && isset($env[$k])) {
                $env = $env[$k];
            } else {
                return false;
            }
        }
        return $env;
    }

    public static function setEnv($key, $val)
    {
        if (isset(Config::$starship->{$key})) {
            Config::$starship->{$key} = $val;
        }
    }

    public function version()
    {
        return Config::starship('version');
    }

    public function theme()
    {
        return Config::$starship->theme;
    }

    public function routes()
    {
        //return self::$config->routes;
        //return Config::$routes;
        return Config::$routes;
    }

    public function destination()
    {
        return $this->router->destination();
    }

    public function stations()
    {
        return $this->router->stations();
    }

    public static function masterPage($masterpage){
        View::masterPage($masterpage);
    }

    public static function view($file){
        self::$viewFile = $file;
    }

    public function viewFile()
    {
        /*$dest = $this->destination();
        if (is_array($dest)){
            $dest = $dest['view'];
        } elseif(self::$viewFile!=false){
            return self::$viewFile;
        } else {
            if ($dest === '_autoloader_') {
                $dest = implode('/',$this->stations());
            }
            if ($dest==''){
                $dest='home';
            } elseif (is_dir(STARSHIP_THEME_PATH.$this->theme().'/templates/'.$dest)){
                $dest = $dest.'/index';
            }
        }
        return $dest;*/

        if (self::$viewFile!==null){
            return self::$viewFile;
        } else {
            $dest = $this->destination();
            if ($dest === '_autoloader_') {
                $dest = implode('/', $this->stations());
            }
            return $dest;
        }
    }

    public function versionedPath($path)
    {
        return Universe::$roots->version . $this->version() . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR;
    }

    public function unVersionedPath($path)
    {
        switch ($path) {
            case 'controller':
                $path = Universe::$roots->controller;
                break;
            case 'model':
                $path = Universe::$roots->model;
                break;
            case 'middleware':
                $path = Universe::$roots->middleware;
                break;
            case 'components':
                $path = Universe::$roots->component;
                break;
            default:
                $path = '';
        }
        return $path;
    }

    private function classFileImport($class, $classType)
    {
        $import = false;
        if ($classType==='Entity'){
            if (class_exists('\\App\\Entity\\'.$class)===false ) {
               // require STARSHIP_ENTITY_PATH . $class .'.php';
            }
        } else {

            $versioned = $this->versionedPath(strtolower($classType)) . $class;
            $unVersioned = $this->unVersionedPath(strtolower($classType)) . $class;

            if (is_dir($versioned)) {
                if (file_exists($versioned . DIRECTORY_SEPARATOR . 'index.php')) {
                    $import = $versioned . DIRECTORY_SEPARATOR . 'index.php';
                } elseif (file_exists($versioned . DIRECTORY_SEPARATOR . $class . '.php')) {
                    $import = $versioned . DIRECTORY_SEPARATOR . $class . '.php';
                }
            } elseif (file_exists($versioned . '.php')) {
                $import = $versioned . '.php';
            }

            if (is_dir($unVersioned)) {
                if (file_exists($unVersioned . DIRECTORY_SEPARATOR . 'index.php')) {
                    $import = $unVersioned . DIRECTORY_SEPARATOR . 'index.php';
                } elseif (file_exists($unVersioned . DIRECTORY_SEPARATOR . $class . '.php')) {
                    $import = $unVersioned . DIRECTORY_SEPARATOR . $class . '.php';
                }
            } elseif (file_exists($unVersioned . '.php')) {
                $import = $unVersioned . '.php';
            }
            if ($import !== false && ( $classType==='Controller' && class_exists('\\App\\Controller\\'.$class)===false )) {
                //require $import;
            }
        }
        return $import;
    }

    private function cleanUpClassName($className, $needle)
    {
        if (strpos($className, $needle) !== false) {
            $className = explode($needle, $className);
            $_class = [];
            foreach ($className as $_c) {
                $_class[] = ucfirst($_c);
            }
            $className = implode('', $_class);
        }
        return $className;
    }

    private function componentClassFixer($className)
    {
        $classNames = explode('\\', $className);
        if (count($classNames) === 1) {
            $className = array_pop($classNames);
            $nameSpace = $className;
        } else {
            $className = array_pop($classNames);
            $nameSpace = implode($classNames);
        }
        $className = ucfirst($nameSpace) . '\\' . $className;
        return $className;
    }


    private function classLoader($classType, $className, $fire = false, $required = false, $method = null, $params = null)
    {
        $className = $this->cleanUpClassName($className, '-');
        $className = $this->cleanUpClassName($className, '_');

        $className = ucfirst($className);

        $import = $this->classFileImport($className, $classType);

        if ($classType === 'Components') {
            $className = $this->componentClassFixer($className);
        }

        $class = '\\App\\' . $classType . '\\' . $className;
        $_return = false;

        if ($import !== false && class_exists($class) && $fire === true) {
            $_class = new $class();
            $method = is_array($method) ? (implode('_', $method)) : $method;
            $myParams = [];
            if ($method){

                $reflection = new \ReflectionMethod($class, $method);

                $args = $reflection->getParameters();
                $type1 = $args[0]->getName();
                $type1 = $args[0]->getType();

                assert($type1 instanceof \ReflectionNamedType);
                //print_r($args[0]->getType());
                //print_r($args[0]->hasType());

                $typeHint = $type1->getName();
                $myParams[] = new $typeHint();
            }

            if ($method!==null && $classType === 'Controller'){

                $param = new Signal(true);
                $_method = ($_class->requestMethod!==false)?(strtolower($_class->requestMethod)):false;

                if ($method==='index' && $_method!==false && method_exists($class,$_method)){
                    $method = $_method;
                } elseif ($method!=='index' && $_method!==false && method_exists($classType,$method.ucfirst($_method)) ) {
                    $method .= ucfirst($_method);
                } elseif (method_exists($class,$method)) {

                } elseif ( !method_exists($class,$method) && method_exists($class,'notFound')){
                    $param = $method;
                    $method = 'notFound';
                } else {
                    throw new Asteroids('Method not found! : ' . $className . '\\' . $method);
                }
                $param = (in_array($_class->requestMethod, ['Insert', 'Update'])) ? (new Signal(true)) : $param;

                $_return = $_class->$method(...$myParams);

            }
            return $_return;

        } elseif ($import !== false && class_exists($class) && $fire === false) {
            return $import;
        } else {
            if ($required) {
                throw new Asteroids($classType . ' class not found! : ' . $className);
            }
        }
    }


    private function model($model, $fire = true, $required = true, $method = null, $params = null)
    {
        return $this->classLoader('Model', $model, $fire, $required, $method, $params);
    }


    private function entity($entitiy, $fire = true, $required = true, $method = null, $params = null)
    {
        return $this->classLoader('Entity', $entitiy, $fire, $required, $method, $params);
    }


    private function controller($controller, $fire = true, $required = true, $method = null, $params = null)
    {
        // volkan
        if (class_exists($controller)){
            $method = is_array($method) ? (implode('_', $method)) : $method;
            if (method_exists($controller,$method)){
                $class = new $controller();
                return $class->$method($params);
            }
            return false;
        } else {
            return $this->classLoader('Controller', $controller . 'Controller', $fire, $required, $method, $params);
        }
    }

    public function component($component, $fire = true, $required = true, $method = 'render', $params = null)
    {
        return $this->classLoader('Components', $component, $fire, $required, $method, $params);
    }


    public function routeDestination()
    {
        return $this->router->destination();
    }

    public function setViewData($key, $val)
    {
        $this->viewData[$key] = $val;
    }

    public function getViewData($key)
    {
        if (isset($this->viewData[$key])) {
            return $this->viewData[$key];
        } else {
            return null;
        }
    }



    public function path()
    {
        return STARSHIP_ENGINE_PATH . $this->config->version . DIRECTORY_SEPARATOR;
    }

    public function views()
    {
        return $this->path() . 'views' . DIRECTORY_SEPARATOR;
    }


    public function themePath()
    {
        return $this->views() . 'themes' . DIRECTORY_SEPARATOR . self::theme() . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
    }

    /*private function flightMode(string $contentType = 'text/html')
    {
        header("Content-type: " . $this->contentType . "; charset=utf-8;");
        $this->contentType = $contentType;
    }*/

    private function runModule()
    {

    }

    private function renderModule()
    {

    }

}

?>