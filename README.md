# 通用权限
设计初衷：当不用现成框架写一个系统的时候，发现每次都要去写auth模块，其他框架的auth和框架耦合度太高，独立不出来，因此开发了此模块

QQ群：647344518   [立即加群](http://shang.qq.com/wpa/qunwpa?idkey=83a58116f995c9f83af6dc2b4ea372e38397349c8f1973d8c9827e4ae4d9f50e)      
出品： [成都新数科技有限公司 http;//www.xinshukeji.com](http;//www.xinshukeji.com)    
体验地址： [http://www.must.pw/tests/](http://www.must.pw/tests/) 

### 1. 简介   
auth提供2中认证模式，基于$_SESSION的认证，和基于token的api认证

### 2. 使用
详细使用见src/demo目录，web是session auth模式，api是token auth
```
composer require shulinqian/auth
```

#### 配置:
```
$sessionStore = new MySessionStore();
$rightsManger = new MyRightsManger();
$config = [
    /* 有默认值 */
    'userNameKey' => 'phone', //登录字段
    'passwordKey' => 'password', //密码字段
    'sessionStoreKey' => 'login_user', //身份前缀
    'sessionStoreOption' => [], //$_SESSION或者memcached等的参数
    'sessionStore' => $sessionStore, //身份保持存储对象
    'useYield' => false, //异步协程模式，swoole等异步框架下可用
    'rightsManger' => $rightsManger, //权限管理对象
    
    /* 无默认值，需要设置 */
    'model' => new \UserModel(),  //user对象，返回查询数据和权限数据
];
```

### 3. 快速测试
找个空目录(前提安装好composer),初始化项目，一直回车就好
```
composer init
composer install
composer require shulinqian/auth
```

安装完成后，运行服务器
```
php7 -S 0.0.0.0:9008
```
然后浏览器访问你的ip  
http://127.0.0.1:9008/web.php

http://127.0.0.1:9008/api.php

至此，大功告成，可根据自己的系统快速集成一个认证和简易权限验证的功能，

# 立即开始愉快的旅程吧