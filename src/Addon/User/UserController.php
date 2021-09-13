<?php
/**
 * This file is part of the Universe package.
 *
 * @author Volkan Şengül <iletisim@volkansengul.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Universe\Addon\User;


use Universe\Shield\Jwt;
use Universe\Shield\Auth;
use Universe\Signalling\Signal;
use Universe\Asteroids\Asteroids;

use Universe\Addon\User\UserModel;
use Universe\Helper\Utils;
use Universe\Message\Email;

class UserController extends Auth {


    private $encryption = 'MD5';
    private $input;
    private $utils;

    public function __construct(){
        $this->input = new Signal();
        $this->utils = new Utils();
    }


    public function create($fields){
        $model = new UserModel();
        $input = $this->input;
        $input->validation($fields)->validate();

        $verificationCode = $this->utils->randomNumber();
        $payload = [
            'name'=>$input->name,
            'surname'=>$input->surname,
            'email'=>$input->email,
            'username'=>$input->username,
            'password'=>$input->password,
            'verification_code' => $verificationCode
        ];
        $user = $model->save($payload);
        if ($user!==false){
            $payload['user_id'] = $user;

            $this->sendEmailVerify();

            return (object)$payload;
        } else {
            return false;
        }
    }

    public function sendEmailVerify($payload){
        $mailPayload = (object)[
            'email'=>'volkan.sengul87@gmail.com',
            'subject'=> 'Test',
            'body' => 'dikkate almayın'
        ];
        $email = new Email();
        $email->send($mailPayload);
    }

    public function userVerify($userId, $verifyCode){

    }




















    public function userDelete(){
        // set passive
    }

    public function getUserBasic(){
        // extends via config
    }

    public function accountActivate($key){
        // set validate
    }

    public function passwordReset(){
        // reset token
        // generate validation code
    }

    public function passwordChange(){
        // reset token
    }

    public function emailChange(){
        // reset token
        // rest email validation
        // generate validation code
    }
    public function accountActivationResend($email){

    }
    public function captcha($email){

    }


    public function isEmailAvailable(){

    }

    // get level
    // set level

    // set photo


}