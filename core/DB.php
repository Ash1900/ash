<?php
    class DB{
        private static $DB_Object;
        private function __construct()
        {
            exit;
        }
        
         static function GetDbObject($dbarray=array()){
            
            global $config;
            $dbOptions=array_merge($config,$dbarray);
            $dbKey=$dbOptions['DB_TYPE'].'_'.$dbOptions['DB_HOST'].'_'.$dbOptions['DB_NAME'].'_'.$dbOptions['DEBUG'];
            $dbclass=strtolower($dbOptions['DB_TYPE']);
            if(isset(self::$DB_Object[$dbKey]))
                return self::$DB_Object[$dbKey];
            if(class_exists($dbclass,false))
            {
                
            }else{
                if(file_exists(CORE_PATH.'/core/dbtype/'.$dbOptions['DB_TYPE'].'.php'))
                {
                    require_once CORE_PATH.'/core/dbtype/'.$dbOptions['DB_TYPE'].'.php';
                    
                }else 
               {
                     core::error('没有当前数据库选项');   
                }
            }
            self::$DB_Object[$dbKey]=new $dbclass($dbOptions);
            return self::$DB_Object[$dbKey];
        }
    }
?>