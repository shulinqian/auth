<?php
/**
 * web方式demo页面，需要session,如果session存储到memcached等,需要在配置设置sessionStore
 *
 */

/** @var ClassLoader $loader */
use Composer\Autoload\ClassLoader;

$loader = require __DIR__ . '/vendor/autoload.php';

include __DIR__ . '/UserModel.php';

$config = [
    'useYield' => true,
    'model' => new \UserModel()
];

$auth = new \thinkweb\auth\Auth();
$userAuth = $auth->setConfig($config)->web();
$user = $userAuth->isLogin();
if(!$user){
    $rs = $userAuth->login('136' . rand(11111111, 99999999), 'admin');
}

//$userAuth->loginOut();
$user = $userAuth->isLogin();

echo '<pre>';
print_r($user);
echo '<pre>';

$action = $_REQUEST['action'] ?? 'index';
if(!$userAuth->rights()->check($action)){
    echo '<h2>权限不足</h2>';
}
