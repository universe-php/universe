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


interface ModelInterface
{
    public function find($id);
    public function get();
    public function all();
    public function save($data);
    public function delete();
}