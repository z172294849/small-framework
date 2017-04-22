<?php
$controller = isset($_GET['controller']) && !empty($_GET['controller']) ? $_GET['controller'] : 'index';
$method = isset($_GET['method']) && !empty($_GET['method'])  ? $_GET['method'] : 'index';
$ClassName = 'Controller\\'.$controller;
call_user_func(array(new $ClassName,$method));