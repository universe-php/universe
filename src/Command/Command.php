<?php
/**
 * This file is part of the Universe package.
 *
 * @author Volkan Şengül <iletisim@volkansengul.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Universe\Command;

use Universe\Asteroids\Asteroids;
use Universe\Universe;
// TODO : refactor
class Command{

    private $basePath;

    public function __construct($basePath){
        $this->basePath = $basePath;
    }

    /* console outputs */
    private function green($str){
        return "\033[32m$str\033[0m";
    }
    private function red($str){
        return "\033[31m$str\033[0m";
    }
    private function yellow($str){
        return "\033[33m$str\033[0m";
    }
    private function blue($str){
        return "\033[36m$str\033[0m";
    }
    private function newline(){
        echo "\n\r";
    }
    private function terminate(){
        exit();
    }

    /* console inputs */
    private function input($msg){
        return (string)readline($msg);
    }

    /**
     * @param string $templateName
     * @return string
     */
    private function getTemplate(string $templateName) : string {
        try {
            $template = dirname(__DIR__)
                .DIRECTORY_SEPARATOR. 'Command'
                .DIRECTORY_SEPARATOR. 'Template'
                .DIRECTORY_SEPARATOR. $templateName;
            if (!file_exists($template)){
                throw new \Exception('Command template {'.$templateName.'} file not exists');
            }
            return file_get_contents($template);
        } catch (\Exception $e){
            echo $this->red($e->getCode().' : '.$e->getMessage());
            $this->terminate();
        }
    }

    /**
     * @return string
     */
    private function getControllerPath() : string {
        try {
            $controllerPath =  $this->basePath.'src'
                .DIRECTORY_SEPARATOR. 'Controller'
                .DIRECTORY_SEPARATOR;
            if (!file_exists($controllerPath)){
                throw new \Exception('Controller path not exists. Run this command on base dir.');
            }
            return $controllerPath;
        } catch(\Exception $e){
            echo $this->red($e->getCode().' : '.$e->getMessage());
            $this->terminate();
        }
    }

    /**
     * @return string
     */
    private function getViewPath(): string {
        try {
            $viewPath = $this->basePath.'src'
                .DIRECTORY_SEPARATOR. 'Theme'
                .DIRECTORY_SEPARATOR. 'default'
                .DIRECTORY_SEPARATOR. 'pages'
                .DIRECTORY_SEPARATOR;
            if (!file_exists($viewPath)){
                throw new \Exception('View path not exists. Run this command on base dir.');
            }
            return $viewPath;
        } catch(\Exception $e){
            echo $this->red($e->getCode().' : '.$e->getMessage());
            $this->terminate();
        }
    }

    /**
     * @return string
     */
    private function getServicePath() : string {
        try {
            $controllerPath =  $this->basePath.'src'
                .DIRECTORY_SEPARATOR. 'Service'
                .DIRECTORY_SEPARATOR;
            if (!file_exists($controllerPath)){
                throw new \Exception('Service path not exists. Run this command on base dir.');
            }
            return $controllerPath;
        } catch(\Exception $e){
            echo $this->red($e->getCode().' : '.$e->getMessage());
            $this->terminate();
        }
    }

    /**
     * @return string
     */
    private function getEntityPath() : string {
        try {
            $controllerPath =  $this->basePath.'src'
                .DIRECTORY_SEPARATOR. 'Entity'
                .DIRECTORY_SEPARATOR;
            if (!file_exists($controllerPath)){
                throw new \Exception('Entity path not exists. Run this command on base dir.');
            }
            return $controllerPath;
        } catch(\Exception $e){
            echo $this->red($e->getCode().' : '.$e->getMessage());
            $this->terminate();
        }
    }

    /**
     * @return string
     */
    private function getRepositoryPath() : string {
        try {
            $controllerPath =  $this->basePath.'src'
                .DIRECTORY_SEPARATOR. 'Repository'
                .DIRECTORY_SEPARATOR;
            if (!file_exists($controllerPath)){
                throw new \Exception('Controller path not exists. Run this command on base dir.');
            }
            return $controllerPath;
        } catch(\Exception $e){
            echo $this->red($e->getCode().' : '.$e->getMessage());
            $this->terminate();
        }
    }

    /**
     * @param array $argv
     * @return string
     */
    private function putControllerName($argv) : string {
        try {
            if($argv && isset($argv[2]) && strlen(trim($argv[2]))>0) {
                $input = ucfirst($argv[2]);
            } else {
                echo $this->green('Choose a name for your controller class (e.g. ');
                echo $this->yellow('HappyElephantController');
                echo $this->green(')');
                $this->newline();
                $input = trim($this->input('> '));
            }
            if (!strlen($input)>0){
                throw new \Exception('Controller name cannot be empty');
            }
            return ucfirst($input);
        } catch (\Exception $e){
            echo $this->red($e->getCode() .' : '.$e->getMessage());
            $this->newline();
            return $this->putControllerName($argv);
        }
    }

    /**
     * @param array $argv
     * @return string
     */
    private function putEntityName($argv) : string {
        try {
            if($argv && isset($argv[2]) && strlen(trim($argv[2]))>0) {
                $input = ucfirst($argv[2]);
            } else {
                echo $this->green('Choose a name for your entity class (e.g. ');
                echo $this->yellow('CrazyCat');
                echo $this->green(')');
                $this->newline();
                $input = trim($this->input('> '));
            }
            if (!strlen($input)>0){
                throw new \Exception('Entity class name cannot be empty');
            }
            return ucfirst($input);
        } catch (\Exception $e){
            echo $this->red($e->getCode() .' : '.$e->getMessage());
            $this->newline();
            return $this->putControllerName($argv);
        }
    }

    /**
     * @param string $controllerName
     * @param string $controllerTemplate
     * @return bool
     */
    private function createController(string $controllerName, string $controllerTemplate) : bool {
        try {
            $content = $controllerTemplate;
            $content = str_replace('%ROUTE_PREFIX%',strtolower($controllerName),$content);
            $content = str_replace('%CONTROLLER_NAME%',$controllerName,$content);
            $content = str_replace('%VIEW_NAME%',strtolower($controllerName),$content);
            $controllerPath =  $this->getControllerPath();
            $fileName = $controllerName.'Controller.php';
            file_put_contents($controllerPath . $fileName,$content);
            if (file_exists($controllerPath . $fileName)){
                echo $this->green('created: ');
                echo $fileName;
                $this->newline();
                return true;
            } else {
                throw new \Exception('Controller not created !');
                return false;
            }
        } catch(\Exception $e){
            echo $this->red($e->getCode().' : '.$e->getMessage());
            $this->terminate();
        }
    }

    /**
     * @param string $viewName
     * @return bool
     */
    private function createView(string $viewName) : bool {
        try {
            $viewPath =  $this->getViewPath();
            $fileName =  $viewName . '.html.twig';
            $content = 'this is view for {{ controller_name }}, edit this!';
            file_put_contents($viewPath . strtolower($fileName), $content);
            if (file_exists($viewPath . $fileName )){
                echo $this->green('created: ');
                echo $fileName;
                $this->newline();
                return true;
            } else {
                throw new \Exception('View not created !');
                return false;
            }
        } catch (\Exception $e){
            echo $this->red($e->getCode().' : '.$e->getMessage());
            $this->terminate();
        }
    }

    /**
     * @param string $entityName
     * @param string $entityTemplate
     * @param string $props
     * @return bool
     */
    private function createEntity(string $entityName, string $entityTemplate, string $props) : bool {
        try {
            $repositoryName = $entityName.'Repository';
            $content = $entityTemplate;
            $content = str_replace('%ENTITY_NAME%', $entityName, $content);
            $tableName = preg_replace("([A-Z])"," $0", $entityName);
            $tableName = explode(' ',trim($tableName));
            $tableName = strtolower(implode('_',$tableName));
            $content = str_replace('%TABLE_NAME%','tbl_'.$tableName, $content);
            $content = str_replace('%REPOSITORY_NAME%',$repositoryName, $content);
            $content = str_replace('%PROPS%',$props, $content);
            $entityPath =  $this->getEntityPath();
            $fileName = $entityName.'.php';
            file_put_contents($entityPath . $fileName, $content);
            if (file_exists($entityPath . $fileName)){
                echo $this->green('created: ');
                echo $fileName;
                $this->newline();
                return true;
            } else {
                throw new \Exception('Entity not created !');
                return false;
            }
        } catch(\Exception $e){
            echo $this->red($e->getCode().' : '.$e->getMessage());
            $this->terminate();
        }
    }

    /**
     * @param string $repositoryName
     * @param string $repositoryTemplate
     * @return bool
     */
    private function createRepository(string $repositoryName, string $repositoryTemplate) : bool {
        try {
            $entityName = $repositoryName;
            $repositoryName = $repositoryName.'Repository';
            $content = $repositoryTemplate;
            $content = str_replace('%ENTITY_NAME%', $entityName, $content);
            $repositoryPath =  $this->getRepositoryPath();
            $fileName = $repositoryName.'.php';
            file_put_contents($repositoryPath . $fileName, $content);
            if (file_exists($repositoryPath . $fileName)){
                echo $this->green('created: ');
                echo $fileName;
                $this->newline();
                return true;
            } else {
                throw new \Exception('Repository not created !');
                return false;
            }
        } catch(\Exception $e){
            echo $this->red($e->getCode().' : '.$e->getMessage());
            $this->terminate();
        }
    }

    /**
     * @param $entityName
     * @return string
     */
    private function inputEntityProp($entityName) : string{
        $props = [];
        $continue = true;

        $template = dirname(__DIR__)
            .DIRECTORY_SEPARATOR. 'Command'
            .DIRECTORY_SEPARATOR.'Template'
            .DIRECTORY_SEPARATOR;

        $entityPropTemplate = file_get_contents($template.'EntityProp');
        $entityGetterSetterTemplate = file_get_contents($template.'EntityGetterSetter');

        while($continue){
            // prop name
            echo $this->green(
                (count($props)>0?'Add another property? Enter the ': 'New ') .
                'property name (press <return> to stop adding fields):');
            $this->newline();
            $propName = $this->input('> ');
            if (trim($propName)===''){
                $continue = false;
                break;
            }
            $prop = (object)[];
            $prop->name = $propName;

            // prop type
            echo $this->green('Field type (enter');
            echo $this->yellow(' ? ');
            echo $this->green('to see all types)');
            echo $this->yellow('[string]');
            $this->newline();
            $propType = $this->input('> ');
            $prop->type = $propType===''?'string':$propType;

            // length
            if ($prop->type==='string' || $prop->type==='int'){
                echo $this->green('Field length');
                echo $this->yellow('['.($prop->type==='string'?255:45).']');
                $this->newline();
                $propLength = $this->input('> ');
                $prop->length = ($propLength==='')?($prop->type==='string'?255:45):$propLength;
            }

            echo $this->green('Can this field be null in the database (nullable) (yes/no)');
            echo $this->yellow('[no]');
            $this->newline();
            $propNull = $this->input('> ');
            $prop->isNullable = ($propNull!=='yes'?false:true);
            $props[] = $prop;
        }

        $tpl = '';
        foreach($props as $p){
            $tpl .= $entityPropTemplate;
            $tpl = str_replace('%COLUMN_NAME%',$p->name,$tpl);
            $tpl = str_replace('%COLUMN_NAME_VAR%','$'.$p->name,$tpl);
            $tpl = str_replace('%COLUMN_LENGTH%',$p->length,$tpl);
            $tpl = str_replace('%COLUMN_TYPE%',$p->type,$tpl);
            $tpl .= "\n\r\n\r";
        }

        foreach($props as $p){
            $tpl .= $entityGetterSetterTemplate;
            $tpl = str_replace('%GETTER%',ucfirst($p->name),$tpl);
            $tpl = str_replace('%SETTER%',ucfirst($p->name),$tpl);
            $tpl = str_replace('%VAR_NAME%',$p->name,$tpl);
            $tpl = str_replace('%COLUMN_TYPE%',$p->type,$tpl);
            $tpl .= "\n\r\n\r";
        }
        /*echo $this->yellow('updated : ');
        echo 'src/Entity/'.$entityName.'Entity.php';
        $this->newline();*/
        return $tpl;
    }

    /**
     * @param $cmd
     * @param $argv
     */
    public function create($cmd,$argv){
        $cmd = ucfirst(strtolower($cmd));
        switch($cmd){
            case 'Controller':
                $controllerTemplate = $this->getTemplate('Controller');
                $controllerName = $this->putControllerName($argv);
                $this->createController($controllerName, $controllerTemplate);
                $this->createView($controllerName);
                break;
            case 'Service':
                echo 'servis yarat';
                break;
            case 'Entity':
                $entityTemplate = $this->getTemplate('Entity');
                $repositoryTemplate = $this->getTemplate('Repository');
                $entityName = $this->putEntityName($argv);
                $props = $this->inputEntityProp($entityName);
                $this->createEntity($entityName, $entityTemplate, $props);
                $this->createRepository($entityName, $repositoryTemplate);
                break;
            default:
                break;
        }
    }
    
    public function list(){
        // usage
        echo $this->yellow('Usage:');
        $this->newline();
        echo "  command [options] [arguments]";
        $this->newline();

        // options
        echo $this->yellow('Options:');
        $this->newline();
        echo $this->green('  -h, --help');
        echo "\t\t Display help for the given command. When no command is given display help for the list command";
        $this->newline();
        echo $this->green('  -v, --version');
        echo "\t\t Display this application version.";
        $this->newline();

        // commands
        echo $this->yellow('Available commands:');
        $this->newline();
        echo $this->green('  list');
        echo "\t\t\t List commands.";
        $this->newline();

        echo $this->yellow('create:');
        $this->newline();
        echo $this->green('  create:controller');
        echo "\t Creates new controller class.";
        $this->newline();
        echo $this->green('  create:entity');
        echo "\t\t Creates or updates a entitiy class.";
        $this->newline();
        echo $this->green('  create:crud');
        echo "\t\t Creates CRUD for entity class.";
        $this->newline();
    }
}