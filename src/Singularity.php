<?php
/**
 * This file is part of the Universe package.
 *
 * @author Volkan Şengül <iletisim@volkansengul.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Universe;

use Universe\Config\Config;
use Universe\Asteroids\Asteroids;
use Universe\Starship\Starship;
use Universe\Shield\Shield;
use Universe\Logger\CaptainsLog as captainsLog;

/**
 * Class Singularity
 * @package Universe
 */
final class Singularity
{
    private $starShip;

    /**
     * Begining of the Time
     * @param string $public
     * @param string $base
     */
    public function __construct(string $public, string $base)
    {
        session_start();
        captainsLog::add('info', 'Captain\'s log. Star date ' . time());
        captainsLog::add('info', 'There is singularity before begining at the time.');

        //require_once 'vendor/autoload.php';
        new Universe($public, $base);
        return $this->configure()
            ->shield()
            ->bigBang();
    }

    /**
     * @return Singularity
     */
    private function configure() : Singularity {
        new Config();
        return $this;
    }

    /**
     * @return Singularity
     */
    private function shield() : Singularity{
        new Shield();
        return $this;
    }

    /**
     * @return Starship
     */
    private function bigBang() : Starship
    {
        captainsLog::add('info', 'And then something happend. We called it "big bang"');
        captainsLog::add('info','Firstly stars borned and they created planets, asteroids, galaxies and 
        second generation stars, themselves from core');
        $this->starShip = new Starship();
        $this->starShip->loadRoutes()->fire();
        return $this->starShip;
    }

}

?>