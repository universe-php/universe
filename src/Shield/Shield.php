<?php
/**
 * This file is part of the Universe package.
 *
 * @author Volkan Şengül <iletisim@volkansengul.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Universe\Shield;

use Universe\Asteroids\Asteroids;
use Universe\Logger\CaptainsLog;
use Universe\Universe;

class Shield{

    private $storageRoot;

    public function __construct(){
        $storageRoot = Universe::$roots->storage;
        CaptainsLog::add('info', 'Shield is active');
        $this->storageRoot = $storageRoot;
        $this->canIPass();
    }

    private function canIPass(){
        if (file_exists($this->storageRoot.'ipban'.DIRECTORY_SEPARATOR.md5($this->getIP()))){
            try {
                throw new Asteroids('You shall not pass!');
            } catch(Asteroids $e){
                $e->fail();
            }
        }
    }

    public function shallNotPass(){
        return file_put_contents(STORAGE_PATH.'ipban'.DIRECTORY_SEPARATOR.md5($this->getIP()),true);
    }


    public function getIP(){
        $ip = null;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}