<?php
namespace thinkweb\auth\web;

use thinkweb\auth\AuthException;

class AuthSync extends Auth
{

    public function login($userName, $password, $cond = []) {
        if (!$userName || !$password) {
            throw new AuthException('arg miss');
        }

        $info = $this->getUserByKey($userName, $cond);
        $info = $info->current();
        if (!$info) {
            throw new AuthException('user not exist', 6001);
        }

        $getHashPassword = $this->bulidHash($password);
        $isLogin = $info[$this->passwordKey] === $getHashPassword;

        if ($isLogin) {
            unset($info[$this->passwordKey]);
            $this->doLogin($info);
            return true;
        }

        return false;
    }

    public function getUserByKey($userName, $cond = []){
        $model = $this->model;
        if (!$model) {
            throw new AuthException('model not exist');
        }
        return yield $model->getUserByKey($userName, $cond);
    }
}