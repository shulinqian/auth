<?php
namespace thinkweb\auth;

/**
 * 简易user类
 * Class Rights
 * @package thinkweb\auth
 */
interface User {

    /**
     * 获取用户信息,返回值至少包括password,否则登录不了，需要权限验证的，需要何止role_group
     * @param $userName
     * @param array $cond
     * @return [id,password,role_group,expire,role_name]
     */
    public function getUserByKey($userName, $cond = []);

    /**
     * 获取权限，返回权限集数组即可，需要缓存的自行处理，auth包里不会处理权限缓存
     * @param $role
     * @return []
     */
    public function getRights($role);

    /**
     * 清理用户数据，在退出登录后，删除用户权限缓存等数据
     * @param $user
     * @return mixed
     */
    public function clean($user);

}