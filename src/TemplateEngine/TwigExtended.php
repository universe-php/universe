<?php
/**
 * This file is part of the Universe package.
 *
 * @author Volkan Şengül <iletisim@volkansengul.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Universe\TemplateEngine;

use Universe\Universe;
use \Twig\Environment;
use \Twig\Loader\FilesystemLoader;
use Universe\Helper\TwigFunctions;
use Universe\Helper\TwigFilters;

final class TwigExtended {

    private $theme;
    private $template;
    private $variables;

    private $twig;
    private $cache = false;
    private $twigFilters;
    private $twigFunctions;

    public function __construct(){
        $twigFilters = new TwigFilters();
        $this->twigFilters = $twigFilters->getFilters();
        $twigFunctions = new TwigFunctions();
        $this->twigFunctions = $twigFunctions->getFunctions();
    }

    public function setTheme($theme){
        $this->theme = $theme;
    }

    public function setTemplate($template){
        $this->template = $template;
    }

    public function setVariables($variables){
        $this->variables = $variables;
    }

    public function setCache($cache=false){
        $this->cache = $cache;
    }

    private function setFilters(){
        if (is_array($this->twigFilters)){
            foreach($this->twigFilters as $filter){
                $this->twig->addFilter($filter);
            }
        }
    }

    private function setFunctions(){
        if (is_array($this->twigFunctions)){
            foreach($this->twigFunctions as $function){
                $this->twig->addFunction($function);
            }
        }
    }

    public function render(){
        $path = Universe::$roots->theme . $this->theme . DIRECTORY_SEPARATOR;
        $loader = new FilesystemLoader($path);
        $this->twig = new Environment($loader, ['cache' => $this->cache]);
        $this->twig->addGlobal('session', $_SESSION);
        $this->setFilters();
        $this->setFunctions();
        echo $this->twig->render($this->template, $this->variables);
    }
}