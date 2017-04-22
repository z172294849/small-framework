<?php
### 核心目录
define('CORE_PATH',__DIR__.DIRECTORY_SEPARATOR);
### 默认应用名称
defined('APPLICATION_NAME') || define('APPLICATION_NAME','Application');
### 根目录
define('ROOT_PATH',CORE_PATH.'../');
### 默认的Debug开启状况
defined('DEBUG') || define('DEBUG',false);
### 错误提示
DEBUG ? ini_set('display_errors','on') : ini_set('display_errors','off');
### 加载函数库
include CORE_PATH.'Function.php';
### 创建项目
include CORE_PATH.'Build.php';
### 加载默认配置
$DefaultConf = CORE_PATH.'Config.php';
if(file_exists($DefaultConf)){
    config(include $DefaultConf);
}else{
    echo '<h2>加载错误！</h2>';exit;
}
### 加载用户自定义配置
$CustomConf =  ROOT_PATH.APPLICATION_NAME.'/Conf/config.php';
if(file_exists($CustomConf)){
    config(include $CustomConf);
}
session_start();
### 注册自动加载函数
spl_autoload_register('autoload');
### 加载路由
include CORE_PATH.'Route.php';
