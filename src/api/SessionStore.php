<?php
namespace thinkweb\auth\api;

class SessionStore{

    protected $store;

    public function __construct($store = null) {
        if($store){
            $this->store = $store->getStore();
        }
    }

    public function get($key, $token = null){
        return $this->store->get($key . $token);
    }

    public function set($key, $info, $option = []){
        list($expiration) = $option;
        $this->store->set($key, $info, $expiration);
    }

    public function delete($key){
        $this->store->delete($key);
    }

}