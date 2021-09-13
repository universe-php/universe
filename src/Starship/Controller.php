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

use Universe\Config\Config;
use Universe\Telescope\RestApi;
use Universe\Telescope\View;
use Universe\TemplateEngine\Sml;

class Controller
{

    protected $get = false;
    protected $post = false;
    protected $put = false;
    protected $delete = false;
    protected $outputMode = 'view';
    protected $methodExtend = false;
    public $requestMethod = false;

    public function __construct()
    {

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->requestMethod = 'Index';
                break;
            case 'POST':
                if (isset($_REQUEST['_method']) && $_REQUEST['_method']==='put'){
                    $this->requestMethod = 'Update';
                } else {
                    $this->requestMethod = 'Insert';
                }

                break;
            case 'PUT':
                $this->requestMethod = 'Update';
                break;
            case 'DELETE':
                $this->requestMethod = 'Delete';
                break;
            default:
                break;
        }
        /*if ($requestMethod === 'GET') {
            $this->get = true;
            $this->actionType = 'Get';
        } elseif ($requestMethod === 'POST') {
            $this->post = true;
            $this->actionType = 'Insert';
        } elseif ($requestMethod === 'PUT') {
            $this->put = true;
            $this->actionType = 'Update';
        } elseif ($requestMethod === 'DELETE') {
            $this->delete = true;
            $this->actionType = 'Delete';
        }*/
    }

    protected function outputMode($mode){
        Config::starship('outputMode',$mode);
    }

    public function methodExtend(){
        $this->methodExtend = true;
    }

    protected function render($view,$variables) : View{
        $this->outputMode('webView');
        return new View($view, $variables);
    }

    protected function restApi($data) : RestApi{
        $this->outputMode('restApi');
        return new RestApi($data);
    }

    public function notfound(){
        return new View('404', []);
    }


    /*
       if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="Benim bölgem"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Kullanıcı İptal düğmesine basınca çıkacak metin';
            exit;
        } else {
            echo "<p>Merhaba {$_SERVER['PHP_AUTH_USER']}.</p>";
            echo "<p>Parola olarak {$_SERVER['PHP_AUTH_PW']} verdiniz.</p>";
        }

     */
}