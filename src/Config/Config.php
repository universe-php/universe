<?php
/**
 * This file is part of the Universe package.
 *
 * @author Volkan Şengül <iletisim@volkansengul.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Universe\Config;

use Universe\Asteroids\Asteroids;
use Universe\Logger\CaptainsLog as captainsLog;
use Universe\Universe;

/**
 * Class Config
 * @package Universe\Config
 */
final class Config
{

    private $configRoot;
    private static $configs = array();


    /**
     * Config constructor.
     */
    public function __construct()
    {
        captainsLog::add('info', 'And something happend somewhere on the time. And life begun.');
        captainsLog::add('info', '... then shows up Humankind. They was corious.');
        $this->configRoot = Universe::$roots->config;
        $this->load();
    }

    /**
     * Configuration loader
     */
    private function load()
    {
        try {
            if (file_exists($this->configRoot) && is_dir($this->configRoot)) {
                $configs = array_diff(scandir($this->configRoot), array('.', '..'));
                foreach ($configs as $conf) {
                    $confName = str_replace('.php', '', $conf);
                    self::$configs[$confName] = require $this->configRoot . $conf;
                }
            } else {
                throw new Asteroids('Config root not found');
            }
        } catch (Asteroids $e) {
            $e->fail();
        }
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        try {
            $keyArgument = count($arguments)>0?$arguments[0]:null;
            $valueArgument = count($arguments)>1?$arguments[1]:null;
            if (count($arguments) === 0) {
                return self::$configs[$name];

            } elseif (count($arguments) === 1
                && isset(self::$configs[$name])
                && isset(self::$configs[$name]->$keyArgument)) {
                return self::$configs[$name]->$keyArgument;

            } elseif (count($arguments) === 2
                && isset(self::$configs[$name])
                && isset(self::$configs[$name]->$keyArgument)) {
                self::$configs[$name]->$keyArgument = $valueArgument;
            } else {
                throw new Asteroids('missing configuration ' . $name . ' - ' . implode($arguments));
            }
        } catch (Asteroids $e) {
            $e->fail();
        }
    }

}