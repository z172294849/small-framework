<?php
/**
 * 创建目录
 * @param $file 目录名
 * @param int $mode 目录权限
 * @return bool
 */
function create_dir($file,$mode=0755)
{
    if(!is_dir($file)){
        if(mkdir($file) && chmod($file,$mode)){
            return true;
        }
        return false;
    }
    return true;
}

/**
 * 创建文件
 * @param $file 文件名
 * @param string $content 文件内容
 * @param int $mode 文件权限
 * @return bool
 */
function create_file($file,$content='',$mode=0755)
{
    if(file_exists($file)){
        if(empty($content)){
            return true;
        }
        if(file_put_contents($file,$content) !== false){
            return true;
        }
    }else{
        if(file_put_contents($file,$content) !== false && chmod($file,$mode)){
            return true;
        }
    }
    return false;
}

/**
 * 类的自动加载函数
 * @param $ClassName
 */
function autoload($ClassName){
    $ClassFile = str_replace('\\',DIRECTORY_SEPARATOR,$ClassName);
    $ClassFile = APPLICATION_NAME.DIRECTORY_SEPARATOR.$ClassFile.'.php';
    if(file_exists($ClassFile)){
        include $ClassFile;
    }else{
        //核心目录下的类的自动加载
        $ArrClassName = explode('\\',$ClassName);
        include CORE_PATH.end($ArrClassName).'.php';
    }
}

/**
 * 配置的读取、设置
 * @param null $key
 * @param null $value
 * @return array|mixed|null
 */
function config($key=null,$value=null)
{
    static $configs = array();
    if(empty($key)){
        return $configs;
    }
    //批量设置配置
    if(is_array($key)){
        $configs = array_merge($configs,array_change_key_case($key,CASE_UPPER));
        return $configs;
    }

    if(is_string($key)){
        $key = strtoupper(trim($key));
        if(empty($value)){
            //读取一项配置
            if(array_key_exists($key,$configs)){
                return $configs[$key];
            }else{
                return null;
            }
        }else{
            //设置一个配置
            $configs[$key] = $value;
        }
    }
    return null;
}

/**
 *请求数据过滤（简单过滤）
 * @param string $method 请求的方法
 * @param null $param 参数
 * @return array|null
 */
function request($method='GET',$param=null){
    $method = strtoupper($method);
    $tmp = array();
    switch($method){
        case 'GET':
            $tmp = $_GET;
            break;
        case 'POST':
            $tmp = $_POST;
            break;
    }
    //简单过滤xss攻击
    $tmp = avoid_xss($tmp,true);
    if(is_null($param)){
        //读取全部
        return $tmp;
    }else{
        //读取单项
        return isset($tmp[$param]) ? $tmp[$param] : null;
    }

}

/**
 * 过滤xss
 * @param $string
 * @param bool $low
 * @return array|mixed|string
 */
function avoid_xss($string, $low = false)
{
    if (!is_array ( $string ))
    {
        $string = trim ( $string );
        $string = htmlspecialchars ( $string );
        if ($low)
        {
            return $string;
        }
        $string = str_replace ( array ('"', "\\", "'", "/", "..", "../", "./", "//" ), '', $string );
        $no = '/%0[0-8bcef]/';
        $string = preg_replace ( $no, '', $string );
        $no = '/%1[0-9a-f]/';
        $string = preg_replace ( $no, '', $string );
        $no = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';
        $string = preg_replace ( $no, '', $string );
        return $string;
    }
    $keys = array_keys ( $string );
    $tmp = array();
    foreach ( $keys as $key )
    {
        $tmp[$key] = avoid_xss ( $string [$key] );
    }
    return $tmp;
}

