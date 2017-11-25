<?php
namespace thinkweb\auth;

/**
 * 简易权限，权限列表为数组，判断键值确定访问权限，和业务关联比较紧的系统，可以自己写right,在config里面设置 rights选项
 * Class Rights
 * @package thinkweb\auth
 */
class Rights {

    protected $rights = [];

    public function addRights($rights) {
        $this->rights = $rights;
    }

    public function check($right) {
        return in_array($right, $this->rights);
    }

}