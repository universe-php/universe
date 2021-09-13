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

final class Universe
{
    public static $roots;

    public function __construct(string $public, string $base)
    {
        $this->setPaths($public, $base);
    }

    /**
     * @param string $public
     * @param string $base
     * @return $this
     */
    private function setPaths(string $public, string $base){
        self::$roots = (object)[];
        // heart of the Universe
        self::$roots->public = $public;
        self::$roots->starship = $base;

        // Starship Specs
        self::$roots->src = self::$roots->starship . 'src' . DIRECTORY_SEPARATOR;
        self::$roots->config = self::$roots->starship . 'config' . DIRECTORY_SEPARATOR;
        self::$roots->translation = self::$roots->starship . 'translations' . DIRECTORY_SEPARATOR;
        self::$roots->routes =  self::$roots->starship. 'routes'. DIRECTORY_SEPARATOR;
        self::$roots->model = self::$roots->src . 'Model' . DIRECTORY_SEPARATOR;
        self::$roots->version =  self::$roots->src. 'versions' . DIRECTORY_SEPARATOR;

        // mvcs
        self::$roots->controller = self::$roots->src . 'Controller' . DIRECTORY_SEPARATOR;
        self::$roots->view = self::$roots->src . 'View' . DIRECTORY_SEPARATOR;
        self::$roots->middleware = self::$roots->src . 'Middleware' . DIRECTORY_SEPARATOR;
        self::$roots->service = self::$roots->src . 'Service' . DIRECTORY_SEPARATOR;
        self::$roots->entity = self::$roots->src . 'Entitiy' . DIRECTORY_SEPARATOR;
        self::$roots->repository = self::$roots->src . 'Repository' . DIRECTORY_SEPARATOR;
        self::$roots->migration = self::$roots->src . 'Migration' . DIRECTORY_SEPARATOR;
        self::$roots->helper = self::$roots->src . 'Helper' . DIRECTORY_SEPARATOR;
        self::$roots->theme = self::$roots->src . 'Theme' . DIRECTORY_SEPARATOR;

        // helpers
        self::$roots->storage =  self::$roots->starship. 'storage' . DIRECTORY_SEPARATOR;
        self::$roots->cache =  self::$roots->storage. 'cache' . DIRECTORY_SEPARATOR;

        return $this;
    }

}

?>