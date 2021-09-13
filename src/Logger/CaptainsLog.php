<?php
/**
 * This file is part of the Universe package.
 *
 * @author Volkan Şengül <iletisim@volkansengul.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Universe\Logger;

final class CaptainsLog{
    private static $logs = [
        'info' => [],
        'error' => [],
        'warning' => []
    ];

    public function __construct(){
    }

    public static function add($type,$message){
        self::$logs[$type][] = $message;
        if ($type==='info' && 1==2){
            echo '<span style="color:green;">■ {info - '.date('H:i:s').'} '.$message.'</span><hr />';
        }
    }

    public static function show(){
        return self::$logs;
    }
}