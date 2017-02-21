<?php
    define('ROOT_PATH', getcwd());
    define('CORE_PATH',dirname(__FILE__));
    define('U_CONF_PATH', ROOT_PATH.'/conf/');
    if(!defined('APP_NAME'))
        define('APP_NAME','default');
    $config=array();
    require 'core/common.php';
    require 'core/core.php';
    core::init($config);  
?>