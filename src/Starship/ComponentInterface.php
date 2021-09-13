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


interface ComponentInterface
{
    public function install();
    public function uninstall();
    public function name();
    public function version();
    public function template();
    public function path();
    public function render();
}