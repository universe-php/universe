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

use Universe\Starship\Model;

class Crew extends Model
{

    protected $tableName = 'tbl_user';
    protected $primaryKey = 'user_id';
    protected $fillable = ['name','surname','email','token','password','username'];
    protected $columns = '*';

    public function __construct(...$params)
    {
        parent::__construct(...$params);
    }

}