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

//use Universe\TemplateEngine\Sml;

class Component extends Starship {

    private $sml;

    public function __construct()
    {
    }

    public function install(){
    }

    public function uninstall(){
    }

    public function name(){
    }

    public function version(){
    }

    public function template(){
    }

    public function path(){
    }

    public function importFiles(){
        $css = $this::env('componentCss');
        $import = $this->import();
        if (is_array($import)){
            foreach($import as $imp){
                $file = $this->import().$imp;
                if (file_exists($file)){
                    $css[] = $file;
                }
            }
        } else {
            $file = $this->path().$import;
            if (file_exists($file)){
                $css[] = $file;
            }
        }
        $this::setEnv('componentCss',$css);
    }


    public function willMount(){
    }

    public function didMount(){
    }

    public function render(){
        $this->importFiles();
        $this->willMount();
        /*if ($this->sml===null){
            $this->sml = new Sml($this);
        }
        $this->didMount();
        return $this->sml->render($this->path().$this->template());*/
    }
}