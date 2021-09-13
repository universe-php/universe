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

use Universe\Config\Config;
use Universe\TemplateEngine\TwigExtended as Twig;
use Universe\Logger\CaptainsLog as captainsLog;
use Universe\Universe;

final class View
{

    private $theme = 'default';

    private static $masterPage;

    private $output = null;

    private $template;
    private $variables;

    public function __construct($template, $variables) //$starShip
    {
        captainsLog::add('info', 'View is open and clear!');
        captainsLog::add('info', 'Journey lets begin!');

        $conf = (object)Config::starship();
        $this->theme = $conf->theme;
        self::$masterPage = 'masterpage' .DIRECTORY_SEPARATOR. 'default';


        $this->template = $template;
        $this->variables = $variables;
        $this->init();
    }

    public static function masterPage($masterPage){
        self::$masterPage = 'masterpages' .DIRECTORY_SEPARATOR. $masterPage;
    }

    private function init()
    {
        $tpl = Universe::$roots->theme . $this->theme .DIRECTORY_SEPARATOR. $this->template;
        if (file_exists($tpl)){
            $twig = new Twig();
            $twig->setTheme($this->theme);
            $twig->setTemplate($this->template);
            $twig->setVariables($this->variables);
            $this->output = $twig->render();
        } else {
            $this->output = '<center><h1>404</h1><p>Page Not Found</p></center>';
        }
    }

    public function render(){
        return $this->output;
    }

}