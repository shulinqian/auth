<?php

/**
 *
 * Class UserModel
 */
class UserModel implements \thinkweb\auth\User {

    public function getUserByKey($userName, $cond = []){
        return [
            'id' => 6388,
            'nickname' => '帅哥',
            'password' => 'c3284d0f94606de1fd2af172aba15bf3',
            'avatar' => '',
            'role_name' => '超级管理员',
            'expire' => time() + 86400,
            'role_group' => 'admin,user',
        ];
    }

    public function getRights($role){
        //根据角色读取权限列表（缓存自理）
        //根据会员获取权限(缓存自理)
        return [
            'index', 'news.add'
        ];
    }

    public function clean($user){
        //清除用户缓存
    }

}

