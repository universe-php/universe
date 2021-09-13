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

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;

use Universe\Asteroids\Asteroids;
use Universe\Config\Config;
use Universe\Shield\Shield;

final class Jwt extends Shield{

    private $builder;
    private $payload = ['user_id','name','surname','avatar','username'];
    private $conf;

    public function __construct(){
        $this->conf = (object)Config::jwt();
        $this->builder = new Builder();
    }

    public function setIssuer($issuer){
        $this->builder->setIssuer($issuer);
        return $this;
    }

    public function setAuidence($auidence){
        $this->builder->setAudience($auidence);
        return $this;
    }

    public function setId($id){
        $this->builder->setId($this->conf->jwtId, true);
        return $this;
    }

    public function setPayload($payload){
        foreach($payload as $k=>$v){
            $this->builder->set($k, $v);
        }
        return $this;
    }
    public function generate($secretKey){
        $signer = new Sha256();
        $time = time();
        $token = $this->builder
            ->setIssuedAt($time) // Configures the time that the token was issued (iat claim)
            ->setNotBefore($time + 60) // Configures the time that the token can be used (nbf claim)
            ->setExpiration($time + 3600) // Configures the expiration time of the token (exp claim)
            ->sign($signer, $secretKey)
            ->getToken();
        return $token->__toString();
    }

    public function tokenVerify($token){

        $signer = new Sha256();
        $token = (new Parser())->parse((string) $token); // Parses from a string

        //echo $token->getHeader('jti');
        //echo $token->getClaim('iss');
        $user_id = $token->getClaim('user_id');
        $username = $token->getClaim('username');

        $verify = $token->verify($signer,$user_id);
        if ($verify){
            $payload = [];
            foreach($this->payload as $key){
                if ($token->hasClaim($key)){
                    $payload[$key] = $token->getClaim($key);
                }
            }
            //Auth::$user = (object)$payload;
            Auth::setSession((object)$payload);
        }
        return $verify;
    }

    public function getToken($payload){
        $token = $this->setIssuer($this->conf->issuer)
            ->setAuidence($this->conf->auidence)
            ->setPayload($payload)
            ->generate($payload->user_id);
        return $token;
    }

    // auth->jwt()->setIssuer()->setAudience()->

}