<?php
/**
 * This file is part of the Universe package.
 *
 * @author Volkan Şengül <iletisim@volkansengul.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Universe\Db;

use Universe\Db\MySQL;

final class DriverManager {

    private static $isConnected = false;
    private static $connection = false;

    public static function init(){
        return false;
    }

    public static function connect($db,...$params){
        if (self::$isConnected){
            return self::$connection;
        } else {
            switch ($db){
                case 'MySQL':
                    self::$connection = new MySQL(...$params);
                    if (self::$connection!==false){ self::$isConnected = true; }
                    return self::$connection;
                    break;
                default:
                    return false;
                    break;
            }
        }
    }

}