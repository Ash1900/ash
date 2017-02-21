<?php
class mysql{
    private $DbResource;
    private $DbSql='<b>QueryLog:</b>';
    function __construct($dboptions=array()){
        foreach ($dboptions as $key=>$value)
        {
            if(!stristr($key, 'DB'))    continue;
            $this->$key=$value;
        }
        $this->debug=$dboptions['DEBUG'];
    }
    
    private function connectDb(){
        if(isset($this->DbResource))
        {
            
        }else{
            //初始化数据库连接
            $this->DbResource=mysql_connect($this->DB_HOST,$this->DB_USER,$this->DB_PWD) or core::error('无法连接数据库');
            mysql_select_db($this->DB_NAME,$this->DbResource);
            mysql_query('set names '.strtolower(preg_replace('/[\W_]/', '', $this->DB_CHARSET)));
            
        }
    }
    
    function query($sql='')
    {
        if(!$sql)    return ;
        $this->connectDb();
        $this->QueryResult=mysql_query($sql,$this->DbResource);
        
        if($this->debug)    $this->DbSql.=$sql.'  '.$this->error().'<br>';
                return $this->QueryResult;
    }
    
    function fetch($result='')
    {
        $result=$result?$result:$this->QueryResult;
        if($result)
        {
            return mysql_fetch_assoc($result);
        }
    }
    
    function insertId()
    {
        if($this->QueryResult)
        return mysql_insert_id($this->DbResource);
    }
    
    function error()
    {
        return mysql_error($this->DbResource)?'<b>error:</b>'.mysql_error($this->DbResource):'';
    }
    
    function __destruct(){
        mysql_close($this->DbResource);
        if(defined('AJAX')&&AJAX==1)
            exit;
        if($this->debug)
            echo $this->DbSql;
    }
}