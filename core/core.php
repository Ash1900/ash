<?php 
    class core{
        
        function  __construct(){

        }
        
        static function init(&$config=null)
        {
            if(!file_exists(ROOT_PATH.'/conf/'.APP_NAME.'_config.php'))
            {
                //初始化
                self::initApp();
            }else {
                $config=require CORE_PATH.'/conf/config.php';
                $userdefinedconfig=require ROOT_PATH.'/conf/'.APP_NAME.'_config.php';
                $config=array_merge($config,$userdefinedconfig);
                $GLOBALS['config']=$config;
                header('Content-Type: text/html; charset=' . $config['charset']);

                $_POST=isset($_POST)?add_slashes($_POST):'';
                $_GET=isset($_GET)?add_slashes($_GET):'';
                $controlName=(isset($_GET['c'])?$_GET['c']:'index').'Control';
                $actionName=(isset($_GET['a'])?$_GET['a']:'index').'Action';
                $control=new $controlName();
                if(method_exists($control, $actionName))
                {
                    $control->$actionName();
                }else 
               {
                    self::error('未找到指定动作');
                }
            }
        }

        static function initApp()
        {
            $config=require CORE_PATH.'/conf/config.php';
            if(mkdir(ROOT_PATH.'/model/'))
            $config['model_path']=ROOT_PATH.'/model/';
            else self::error("无法创建目录");
            mkdir(ROOT_PATH.'/control/');
            $config['control_path']=ROOT_PATH.'/control/';
            mkdir(ROOT_PATH.'/view/');
            $config['view_path']=ROOT_PATH.'/view/';
            mkdir(ROOT_PATH.'/cache/');
            $config['cache_path']=ROOT_PATH.'/cache/';
            mkdir(ROOT_PATH.'/conf/');
            $configstr='<?php return '.var_export($config,true).';?>';
            file_put_contents(CORE_PATH.'/conf/config.php',$configstr);
            file_put_contents(ROOT_PATH.'/conf/'.APP_NAME.'_config.php', '<?php return array();?>');
            
            file_put_contents($config['control_path'].'indexControl.class.php',"<?php class indexControl{ \n\t function indexAction(){\n\n\t}\n} ?>");
            self::init();
        }
        
        static function autoload($className)
        {
            if(class_exists($className,false))
                return ;
            global $config;

            if(stristr($className, 'Control')&&file_exists($config['control_path'].$className.'.class.php'))
            {
                require_once $config['control_path'].$className.'.class.php';
                return;
            }
            if(stristr($className, 'Model')&&file_exists($config['model_path'].$className.'.class.php'))
            {
                require_once $config['model_path'].$className.'.class.php';
                return;
            }         
            if(file_exists(CORE_PATH.'/core/'.$className.'.php'))
            {

                require_once CORE_PATH.'/core/'.$className.'.php';
                return;
            }
            
            
            self::error('项目不存在！');
            
        }
        
        static function error($msg)
        {
            die($msg);
        }
        
        static function error_AJAX($msg)
        {
              echo json_encode(array('result'=>false,'msg'=>$msg));
              exit;
        }
        
        static function jumpUrl($msg,$url,$time='')
        {
            header( "refresh:".(int)$time.";url=".$url."" ); 
            echo $msg.'<br>页面将在'.$time.'秒后跳转 <a href="'.$url.'">点击这里</a>';
            exit;
        }
    }
    
    spl_autoload_register(array('core','autoload'));
?>