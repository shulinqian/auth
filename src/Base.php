<?php
namespace thinkweb\auth;

abstract class Base{
    protected $config = [];
    protected $token;

    public function __construct($config = []) {
        $this->setConfig($config);
    }

    public function __get($key) {
        switch ($key) {
            case 'userNameKey':
                return $this->userNameKey = $this->getConfig($key, 'phone');
            case 'passwordKey':
                return $this->passwordKey = $this->getConfig($key, 'password');
            case 'sessionStoreKey':
                return $this->$key = $this->getConfig($key, 'login_user');
            case 'sessionStore':
                $store = $this->getConfig($key);
                $sessionStore = $this->getSessionStore($store);
                return $this->$key = $sessionStore;
            case 'rightsManger':
                $rightsClass = $this->getConfig($key);
                if(!$rightsClass){
                    $rightsClass = $this->getRightsManager();
                }
                return $this->$key = $rightsClass;

            case 'model':
            case 'sessionStoreOption':
                return $this->$key = $this->getConfig($key);
        }
    }

    /**
     * 保存session
     * @param $store
     * @return mixed
     */
    abstract protected function getSessionStore($store);

    /**
     * 权限验证，可根据自己需求重新
     * @return Rights
     */
    protected function getRightsManager(){
        return new Rights();
    }

    /**
     * 用户登录
     * @param $userName
     * @param $password
     * @param array $cond
     * @return bool
     * @throws AuthException
     */
    public function login($userName, $password, $cond = []) {
        if (!$userName || !$password) {
            throw new AuthException('arg miss');
        }

        $info = $this->getUserByKey($userName, $cond);

        if (!$info) {
            throw new AuthException('user not exist', 6001);
        }

        $getHashPassword = $this->bulidHash($password);
        $isLogin = $info[$this->passwordKey] === $getHashPassword;

        if ($isLogin) {
            unset($info[$this->passwordKey]);
            return $this->doLogin($info);
        }

        return false;
    }

    protected $loginUser = [];

    /**
     * 登录状态检测，根据模型返回值返回用户数组（过滤了password）
     * @param null $token
     * @return array|mixed|null
     */
    public function isLogin($token = null){
        if($this->loginUser){
            return $this->loginUser;
        }
        $sessionStore = $this->sessionStore;
        $loginUser = $sessionStore->get($this->sessionStoreKey, $token);
        if($loginUser){
            $this->loginUser = unserialize($loginUser);
        }
        if($this->loginUser && isset($this->loginUser['expire'])){
            if($this->loginUser['expire'] < time()){
                $this->loginOut();
                return null;
            }
        }
        return $this->loginUser;
    }

    /**
     * 根据登录的key，获取用户数据
     * @param $userName
     * @param array $cond
     * @return mixed
     * @throws AuthException
     */
    public function getUserByKey($userName, $cond = []){
        $model = $this->model;
        if (!$model) {
            throw new AuthException('model not exist');
        }
        return $model->getUserByKey($userName, $cond);
    }

    /**
     * 登录身份保持
     * @param $user
     * @return bool
     */
    public function doLogin($user) {
        $sessionStore = $this->sessionStore;
        $this->loginUser = $user;
        $serialise = serialize($user);
        $sessionStore->set($this->sessionStoreKey, $serialise, $this->sessionStoreOption);
        return true;
    }

    /**
     * 退出登录
     * @return bool
     */
    public function loginOut($token = null) {
        $user = $this->isLogin($token);
        if(!$user){
            return false;
        }
        $sessionStore = $this->sessionStore;
        $sessionStore->delete($token);
        $this->model->clean($user);
        return true;
    }

    /**
     * 密码加密
     * @param $password
     * @return string
     */
    public function bulidHash($password) {
        //预留加密接口
        $hash = $this->getConfig('hash');
        $fix = $this->getConfig('hash_fix');
        switch ($hash) {
            default:
                return md5(md5($password) . $fix);
        }
    }

    /**
     * 获取配置
     * @return array
     */
    public function getConfig($key = null, $def = null) {
        if ($key === null) {
            return $this->config;
        }
        return $this->config[$key] ?? $def;
    }

    /**
     * 设置配置
     * @param array $config
     */
    public function setConfig($config) {
        $this->config = $config;
    }

    protected $rights;

    /**
     * 获取权限对象
     * @param null $token
     * @return Rights|bool
     */
    public function rights($token = null){
        if($this->rights){
            return $this->rights;
        }
        $user = $this->isLogin($token);
        $model = $this->model;
        if(!$user || !$model){
            return false;
        }

        //一下代码确认right必须有值，否则数据库或者缓存过期后，权限列表为空，就成了所有权限了
        $rights = $model->getRights($user);
        if(!$rights){
            return false;
        }
        //end
        $this->rightsManger->addRights($rights);
        return $this->rights = $this->rightsManger;
    }
}