<?php
/**
 * This file is part of the Universe package.
 *
 * @author Volkan Şengül <iletisim@volkansengul.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Universe\Helper;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Universe\Config\Config;
use Universe\Universe;


class TwigFunctions
{

    private static $translation;
    private $conf;

    public function __construct(){
        $this->conf = (object)Config::starship();
        $path = Universe::$roots->translation . $this->conf->lang . '.json';
        self::$translation = Json_decode(file_get_contents($path),true);
    }

    public function getFunctions() {
        return array(
            new TwigFunction('asset', array($this, 'asset')),
            new TwigFunction('url', array($this, 'url')),
            new TwigFunction('langSwitch', array($this, 'langSwitch')),
            new TwigFunction('config', array($this, 'config')),
            new TwigFunction('themeCss', array($this, 'themeCss'), array('is_safe'=>array('html')))
        );
    }

    public function url($input){
        $lang = Config::starship('lang');
        $langUriPrefix = Config::starship('langUriPrefix');

        if ($input==='/'){
            $input = '';
        }
        if (substr($input,0,1)==='/'){
            $input = substr($input,0,strlen($input)-1);
        }
        if (substr($input,-1)==='/'){
            $input = substr($input,0,strlen($input)-1);
        }

        $uri = explode('/',$input);
        if ($uri[0]===$lang){
            array_shift($uri);
        }
        $uriPrefix = '';
        if ($langUriPrefix[$lang]!==''){
            $uriPrefix = $langUriPrefix[$lang]. '/';
        }
        $uri = '/'.$uriPrefix.implode('/',$uri);

        return $uri;
    }

    public function asset($input){
        return $input;
    }

    public function langSwitch($lang){
        $uri = $_REQUEST['_url'];
        $uri = explode('/',$uri);
        $langUriPrefix = Config::starship('langUriPrefix');
        $prefix = $langUriPrefix[$lang];
        if ($prefix!==''){
            $prefix = $prefix.'/';
        }
        if (in_array($uri[0],array_keys($langUriPrefix))){
            array_shift($uri);
        }
        $uri = implode('/',$uri);

        return '/'.$prefix.$uri;
    }

    public function config($key){
        return Config::starship($key);
    }

    public function themeCss($input){
        return '<link rel="stylesheet" type="text/css" href="'. DIRECTORY_SEPARATOR . $this->conf->theme . DIRECTORY_SEPARATOR . 'assets'
            .DIRECTORY_SEPARATOR. 'css' . DIRECTORY_SEPARATOR . $input . '" />';
    }

}