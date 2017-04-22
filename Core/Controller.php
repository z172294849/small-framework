<?php
class Controller
{
    protected $variables = array();

    /**
     * 加载模板
     * @param $file
     * @return bool
     */
    public function display($file)
    {
        if(empty($file)){
            return false;
        }
        $TemplateFile = ROOT_PATH.APPLICATION_NAME.'/View/'.config('TEMPLATE_NAME').'/'.end(explode('\\',get_class($this))).'/'.$file.config('TEMPLATE_SUFFIX');
        if(file_exists($TemplateFile)) {
            include $TemplateFile;
        }else{
            echo '<h1>页面不存在</h1> '.APPLICATION_NAME.'/View/'.config('TEMPLATE_NAME').'/'.end(explode('\\',get_class($this))).'/'.$file.config('TEMPLATE_SUFFIX');
        }
    }

    /**
     * 向模板中分配变量
     * @param $key
     * @param $value
     */
    public function assign($key,$value)
    {
        $this->variables[$key] = $value;
    }
}
