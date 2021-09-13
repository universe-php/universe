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

use Universe\Addon\User\UserModel as Crew;

use Universe\Shield\Jwt;
use Universe\Shield\Shield;
use Universe\Signalling\Signal;
use Universe\Asteroids\Asteroids;


class Auth extends Shield{


    private $encryption = 'MD5';

    private static $session;

    private $authMethod;
    private $jwt;
    private $crew;
    private $input;

    public function __construct(){
        $this->crew = new Crew();
        $this->input = new Signal(true);
    }

    public function encryption($encrypt){
        $this->encryption = $encrypt;
        return $this;
    }

    public function setAuthMethod($method='session'){
        $this->authMethod = $method;
    }

    public function uniqId(){
        $uid = str_replace('.','',uniqid(rand(),true));
        $uids = [];
        $uids[] = substr($uid,0,5);
        $uids[] = substr($uid,5,5);
        $uids[] = substr($uid,10,5);
        $uids[] = substr($uid,15,5);
        return implode('-',$uids);
    }

    public function jwt(){
        $this->jwt = new Jwt();
        return $this->jwt;
    }

    public function getToken(){
        return $this->input->getHeader('authorization');
    }

    public static function getSession(){
        return self::$session;
    }

    public static function setSession($data){
        self::$session = $data;
    }

    public function refreshToken(Signal $input, $user_id=null)
    {
        try {
            if ($user_id===null){
                $user_id = self::$session->user_id;
            }
            $user = $this->crew->find($user_id);

            if (!$user) {
                throw new Asteroids('Token alınamadı');
            }
            $token = $this->jwt()->getToken((object)[
                'user_id' => $user->user_id,
                'name' => $user->name,
                'surname' => $user->surname,
                'photo' => $user->photo,
                'username' => $user->username
            ]);
            if (!$token) {
                throw new Asteroids('Token alınamadı');
            }
            $user->save(['token' => $token]);
            return ['token' => $token];
        } catch (Asteroids $e) {
            $e->fail();
            return null;
        }

    }


    public function isBlocked(){

    }

    public function isLogged(){

    }

    public function login($options=[]){
        try {
            $input = $this->input;
            $input->validation(['username', 'password'])->validate();
            $this->crew->columns('user_id, name, surname, username, token');

            $encryption = $this->encryption;
            $password =  function_exists($encryption)?$encryption($input->password):$input->password;

            $user = $this->crew
                ->where('username', $input->username)
                ->where('password', $password)
                ->get();
            if (!$user) {
               throw new Asteroids('Hatalı e-posta veya şifre');
            }

            $token = null;
            if (!$token){
                $token = $this->refreshToken($input, $user->user_id);
                $user->token = $token['token'];
            }

            if ($input->remember){
                setcookie('token',$token);
            }
            //$this->logLogin($user);
            return $user;
        } catch (Asteroids $e) {
            $e->fail();
            return null;
        }
    }


    public function logout($hash){

    }

    public function forgotPassword(){

    }

    public function signup(){
        try {
            // read inputs
            $input = $this->input;
            // validation
            $input->validation(['email','username','password'])->validate();
            // md5 magic
            $encryption = $this->encryption;
            $password = function_exists($encryption)?$encryption($input->password):$input->password;


            // check user
            $userNameCheck = $this->crew
                ->where('username', $input->username)
                ->get();
            if ($userNameCheck) {
                throw new Asteroids('Bu kullanıcı adı alınmış.');
            }

            $userEmailCheck = $this->crew
                ->where('email', $input->email)
                ->get();
            if ($userEmailCheck) {
                throw new Asteroids('Bu e-posta adresi kullanımda.');
            }

            if (!$userNameCheck && !$userEmailCheck){
                $user = $this->crew;
                $data = [
                    'username'=>$input->username,
                    'email'=>$input->email,
                    'password'=>$password,
                ];
                $userId = $user->save($data);
                if ($userId){
                    return $this->login();
                } else {
                    return false;
                }
            }

        } catch (Asteroids $e){
            $e->fail();
            return null;
        };

    }





    public function getLevel($level='public'){
        switch($level){
            case 'public':
                //return true;
                break;
            case 'login':
                return $this->isLogged();
            default:
                break;
        }
        return $this;
    }


    public function check(){
        try {
            $isValid = $this->jwt()->tokenVerify();
            return $isValid;
        } catch(Asteroids $e){
            $e->fail();
            return null;
        }
    }


}