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
use Twig\TwigFilter;
use Universe\Config\Config;
use Universe\Universe;


class TwigFilters
{

    private static $translation;

    public function __construct(){

        $conf = (object)Config::starship();
        $path = Universe::$roots->translation . $conf->lang . '.json';
        self::$translation = Json_decode(file_get_contents($path),true);
        //var_dump ( yaml_parse_file($path) );
        //exit();
    }

    public function getFilters() {
        return array(
            new TwigFilter('base64_encode', array($this, 'base64_en')),
            new TwigFilter('base64_decode', array($this, 'base64_dec')),
            new TwigFilter('trans', array($this, 'translate'))
        );
    }

    public function translate($input){
        $inputs = explode('.',$input);
        $tempVar = null;
        for($i=0; $i<count($inputs); $i++){
            $tempKey = $inputs[$i];
            if ($i===0 && isset(self::$translation[$tempKey])){
                $tempVar = self::$translation[$tempKey];
            } elseif ($i!==0 && $tempVar!==null && isset($tempVar[$tempKey])){
                $tempVar = $tempVar[$tempKey];
            } else {
                $tempVar = $input;
            }
        }
        return $tempVar;
    }

    public function base64_en($input)
    {
        return base64_encode($input);
    }

    public function base64_dec($input)
    {
        return base64_decode($input);
    }

}