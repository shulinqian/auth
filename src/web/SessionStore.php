<?php
namespace thinkweb\auth\web;

class SessionStore{

    public function __construct($store = null) {
        if(!session_id()){
            session_start();
        }
    }

    public function get($key, $token = null){
        if($_SESSION){
            return $_SESSION[$key] ?? null;
        }
        return null;
    }

    public function set($key, $info, $option = []){
        $_SESSION[$key] = $info;
        list($expire, $path, $domain, $secure, $httponly) = $option;
        setcookie($key, $info, $expire, $path, $domain, $secure, $httponly);
    }

    public function delete(){
        $_SESSION = [];
        if(isset($_COOKIE[session_name()])){
            setcookie(session_name(), '',time()-3600);
        }
        session_destroy();
    }

}