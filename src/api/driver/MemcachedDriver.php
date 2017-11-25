<?php
namespace thinkweb\auth\api\driver;

use Memcached;

class MemcachedDriver{

    protected $store;
    public function __construct($config = []) {
        if($config){
            $count = count($config);
            if($count == 1){
                $config = array_pop($config);
            } else {
                $server = rand(0, $count - 1);
                $config = $config[$server];
            }
            $this->store = new Memcached();
            list($host, $port) = $config;
            $this->store->addServer($host, $port);
        }
    }

    public function setStore($store){
        $this->store = $store;
    }

    public function getStore(){
        return $this->store;
    }
}
