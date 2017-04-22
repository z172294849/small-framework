<?php
### 创建项目的MVC结构
create_dir(ROOT_PATH.APPLICATION_NAME.DIRECTORY_SEPARATOR,0777);
$necessary_dir = array('Controller','Model','View','Conf','Library');
foreach($necessary_dir as $v){
    if(!create_dir(ROOT_PATH.APPLICATION_NAME.DIRECTORY_SEPARATOR.$v,0777)){
        echo '<h2>项目创建失败！</h2>';exit;
    }
}
### 创建默认文件
$DefaultFile = ROOT_PATH.APPLICATION_NAME.DIRECTORY_SEPARATOR.'Controller'.DIRECTORY_SEPARATOR.'index.php';
if(!file_exists($DefaultFile)){
    $DefaultContent = "<?php\r\nnamespace Controller;\r\nclass index extends \\Controller\r\n"."{\r\n"."\tpublic function index()\r\n\t"."{\r\n\t\t"."echo '<h2>Small Framework</h2>';\r\n\t";
    $DefaultContent .= "}\r\n"."}";
    if(!create_file($DefaultFile,$DefaultContent,0777)){
        echo '<h2>默认控制器创建失败！</h2>';exit;
    }
}
### 创建项目默认配置文件
$DefaultConf = ROOT_PATH.APPLICATION_NAME.DIRECTORY_SEPARATOR.'Conf'.DIRECTORY_SEPARATOR.'config.php';
if(!file_exists($DefaultConf)){
    $DefaultContent = "<?php\r\nreturn array(\r\n);";
    if(!create_file($DefaultConf,$DefaultContent,0777)){
        echo '<h2>配置文件创建失败！</h2>';exit;
    }
}
