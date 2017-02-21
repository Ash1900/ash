<?php
class cache{
     private function __construct(){
         
     }  
     
     static function setCache($name='',$data=array()){
         global $config;
         $cachename=$name?$name:formatGet($_GET);
         
         file_put_contents($config['cache_path'].$cachename.'.cache.php', '<?php return '.var_export($data,true).';?>');
         return $data;
     }
     
     static function getCache($name='',$time=0){
         global $config;
         $cachename=$name?$name:formatGet($_GET);
         $cachefile=$config['cache_path'].$cachename.'.cache.php';
         if(!$time||!file_exists($cachefile))
             return false;
         if(time()-filemtime($cachefile)>=$time)
             return false;
         return require $cachefile;         
     }
}
?>