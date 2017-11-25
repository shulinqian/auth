<?php
/**
 * api认证和权限demo页面, 需要用memcache存储token，如果redis存储，需要增加driver
 */

/** @var ClassLoader $loader */
use Composer\Autoload\ClassLoader;

$loader = require __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/UserModel.php';
//memcache
$memcacheConfig = [
    ['192.168.0.179', 11211]
];
$sessionStore = new \thinkweb\auth\api\driver\MemcachedDriver($memcacheConfig);
//可以根据系统自定义store

$config = [
//    'useYield' => true,
    'sessionStore' => $sessionStore,
    'model' => new \UserModel()
];

$auth = new \thinkweb\auth\Auth();
$userAuth = $auth->setConfig($config)->api();

$token = $_REQUEST['token'] ?? null;
if(!$token){
    //生成token返回
    $token = $userAuth->login('13666666666', 'admin');
    echo <<<EOF
<script>
    window.location.href = '/api.php?token=' + '{$token}';
</script>
EOF;

} else {
//$userAuth->loginOut();
    $user = $userAuth->isLogin($token);
    echo '<pre>';
    print_r($user);
    echo '<pre>';
}

$action = $_REQUEST['action'] ?? 'index';
if(!$userAuth->rights($token)->check($action)){
    echo '<h2>权限不足</h2>';
}

