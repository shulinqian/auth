<?php
namespace thinkweb\auth\web;

use thinkweb\auth\Base;

class Auth extends Base {


    protected function getSessionStore($store){
        return new SessionStore($store);
    }

}