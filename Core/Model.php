<?php
class Model
{
    //数据库的配置信息
    protected $host;
    protected $port;
    protected $user;
    protected $passwd;
    protected $dbname = null;

    //数据库连接
    protected $conn = null;
    //执行的sql语句
    public $last_sql = null;
    //要操作的表
    protected $table = null;


    /**
     * 构造方法
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->table = end(explode('\\',get_class($this)));
        //数据库的配置信息
        $this->host = isset($config['host']) ? $config['host'] : config('MYSQL_DB_HOST');
        $this->port = isset($config['port']) ? $config['port'] : config('MYSQL_DB_PORT');
        $this->user = isset($config['user']) ? $config['user'] : config('MYSQL_DB_USER');
        $this->passwd = isset($config['passwd']) ? $config['passwd'] : config('MYSQL_DB_PSWD');
        $this->dbname = isset($config['dbname']) ? $config['dbname'] : config('MYSQL_DB_DBNM');

        $this->conn_db();
        $this->select_db($this->dbname);
    }

    /**
     * 连接数据库操作
     */
    protected function conn_db()
    {
        $this->conn = mysql_connect($this->host.':'.$this->port,$this->user,$this->passwd);
    }

    /**
     * 执行选择数据库
     * @param $dbname
     */
    protected function select_db($dbname)
    {
        mysql_select_db($dbname,$this->conn);
    }

    /**
     * 读取一条数据
     * @param string $where
     * @param string $order
     * @return array|bool
     */
    public function get_one($where='',$order='')
    {
        if(!empty($where)){
            $where = ' WHERE '.$where;
        }
        if(!empty($order)){
            $order = ' ORDER BY '.$order;
        }
        $limit = ' LIMIT 0,1';
        return $this->query('SELECT * FROM '.$this->table.$where.$order.$limit,0);
    }

    /**
     * 查询多条（暂不支持分组查询）
     * @param string $where 查询条件
     * @param string $order 排序规则
     * @param string $limit 数量限制
     * @return array|bool
     */
    public function select($where='',$order='',$limit='')
    {
        if(!empty($where)){
            $where = ' WHERE '.$where;
        }
        if(!empty($order)){
            $order = ' ORDER BY '.$order;
        }
        if(!empty($limit)){
            $limit = ' LIMIT '.$limit;
        }
        return $this->query('SELECT * FROM '.$this->table.$where.$order.$limit,1);
    }

    /**
     * 执行一条SQL语句
     * @param $sql
     * @return array|bool
     */
    public function query($sql,$dimension=0)
    {
        $this->last_sql = $sql;
        return $this->format(mysql_query($sql,$this->conn),$dimension);

    }

    /**
     * 执行非select语句
     * @param $sql
     * @return bool
     */
    public function exec($sql)
    {
        $this->last_sql = $sql;
        return mysql_query($sql,$this->conn);
    }

    /**
     * 删除记录
     * @param $where
     * @return bool|resource
     */
    public function delete($where)
    {
        if(!is_string($where) || empty($where)){
            return false;
        }

        $sql = 'DELETE FROM '.$this->table.' WHERE '.$where;
        return $this->exec($sql);
    }

    /**
     * 添加一条数据
     * @param $table
     * @param array $data
     * @return bool
     */
    public function insert($data=array())
    {
        if(!is_array($data) || empty($data)){
            return false;
        }
        $field = '';
        $value = '';
        foreach($data as $k=>$v){
            $field .= '`'.$k.'`,';
            $value .= '"'.$v.'",';
        }
        $sql = 'INSERT INTO '.$this->table.' ('.trim($field,',').') VALUES ('.trim($value,',').')';
        return $this->exec($sql);
    }

    /**
     * 一次插入多行数据
     * @param $data
     * @return bool
     */
    public function insert_all($data)
    {
        if(!is_array($data) || empty($data) || !is_array($data[0])){
            return false;
        }
        $fields = implode(',',array_keys($data[0]));
        $values = '';
        foreach($data as $v){
            if(!is_array($v)){
                return false;
            }
            $values .= '(';
            foreach($v as $vv){
                $values .= '"'.$vv.'",';
            }
            $values = trim($values,',');
            $values .= '),';
        }
        $values = trim($values,',');
        $sql = 'INSERT INTO '.$this->table.' ('.$fields.') VALUES '.$values;
        return $this->exec($sql);
    }

    /**
     * 更新数据
     * @param array $data
     * @param string $where
     * @return bool
     */
    public function update($data=array(),$where='')
    {
        array('username'=>'hello');
        if(!is_array($data) || empty($data)){
            return false;
        }
        $tmp = '';
        foreach($data as $k=>$v){
            $tmp .= '`'.$k.'`="'.$v.'",';
        }
        $tmp = rtrim($tmp,',');
        if(empty($where)){
            return false;
        }
        $where = ' WHERE '.$where;
        $sql = 'UPDATE '.$this->table.' SET '.$tmp.$where;
        return $this->exec($sql);
    }


    /**
     * 格式化数据集
     * @param $data_sets 数据集
     * @return array|bool
     */
    protected function format($data_sets,$dimension=0)
    {
        if(!is_resource($data_sets)) return false;
        $data = array();
        while($row = mysql_fetch_assoc($data_sets)){
            $data[] = $row;
        }
        //如果只有一条，返回一个一维数组
        if(empty($dimension)){
            return $data[0];
        }
        //多条的情况下，返回多维数组
        return $data;
    }

    /**
     * 析构方法
     */
    public function __destruct()
    {
        unset($this->conn);
    }


}
