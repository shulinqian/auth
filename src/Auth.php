<?php
namespace thinkweb\auth;

class Auth{

    protected $config = [];

    /**
     * @return array
     */
    public function getConfig($key = null, $def = null) {
        if ($key === null) {
            return $this->config;
        }
        return $this->config[$key] ?? $def;
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }

    public function api(){
        return new \thinkweb\auth\api\Auth($this->config);
    }

    public function web(){
        if($this->getConfig('useYield')){
            return new \thinkweb\auth\web\AuthSync($this->config);
        }
        return new \thinkweb\auth\web\Auth($this->config);
    }

}