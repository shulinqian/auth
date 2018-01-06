<?php
namespace thinkweb\auth\api;
use thinkweb\auth\AuthException;
use thinkweb\auth\Base;

/**
 *
 * Class Auth
 * @package thinkweb\auth\backend
 */
class Auth extends Base{

    protected function getSessionStore($store){
        return new SessionStore($store);
    }

    public function doLogin($user) {
		if(!$user){
            throw new AuthException('login fail');
        }
        //生成token
        $serialise = serialize($user);
        $time = time();
        $token = md5(md5($serialise) . $time);
        //保存token
        $this->sessionStore->set($this->sessionStoreKey . $token, $serialise, $this->sessionStoreOption);
		if(!$rs){
            throw new AuthException('storeError');
        }
        return $token;
    }

    /**
     * @return mixed
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token) {
        $this->token = $token;
    }

}