<?php
class sqlsrv{
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
            $this->DbResource=sqlsrv_connect($this->DB_HOST,array(
                'UID'=>$this->DB_USER,
                'PWD'=>$this->DB_PWD,
//                'Database'=>$this->DB_NAME,
                'CharacterSet'=>$this->DB_CHARSET
            )) or die("无法连接数据库");
            
        }
    }
    
    function query($sql='')
    {
        if(!$sql)    return ;
        $this->connectDb();
        $this->QueryResult=sqlsrv_query($this->DbResource,$sql);
        if($this->debug)    $this->DbSql.=$sql.'  '.$this->error().'<br>';
        return $this->QueryResult;
    }
    
    function fetch($result='')
    {
        $result=$result?$result:$this->QueryResult;
        if($result)
        {
            return sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC);
        }
    }
    
    function insertId($tableName,$key)
    {
     //   if($this->QueryResult)
     //   return mysql_insert_id($this->DbResource);
         
        $maxIdResult=$this->query('select max('.$key.') as insertId from '.$tableName.'  ');
        $result=$this->fetch($maxIdResult);
        return $result['insertId'];
    }
    
    function error()
    {
       $errors=sqlsrv_errors();
        return $errors?'<b>error:</b>'.$errors[0]['message']:'';
    }
    
    function __destruct(){
        sqlsrv_close($this->DbResource);
        if(defined('AJAX')&&AJAX==1)
            exit;
        if($this->debug)
            echo $this->DbSql;
    }
}