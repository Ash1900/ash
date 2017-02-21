<?php
function formatGet($data=array()){
    $data=$data?$data:$_GET;

    $str='';
    $str.=(isset($data['c'])?$data['c']:'index').(isset($data['a'])?'_'.$data['a']:'_index');
    foreach ($data as $key=>$value)
    {
        if($key=='c'||$key=='a')    continue;
        $str.='_'.$value;
    }
    return $str;
}

function drawPage($dataCount,$limit,$currentPage=1,$functionName=''){
    $pageCount=ceil($dataCount/$limit);
    $currentPage=($currentPage<1)?1:($currentPage>$pageCount?$pageCount:$currentPage);
    if($pageCount<=1)    return '';
    
    $html='<div class="page">';

    if($pageCount>10)
    {
        
        if($currentPage-5>=1)
            $html.='<a href="'.pageUrl(1,$functionName).'" >'.'1</a> ...';
        $startPage=min($pageCount-7,$currentPage-5>=1?$currentPage-2:1);
        $endPage=max(8,$currentPage+5<$pageCount?$currentPage+3:$pageCount);
        for($i=$startPage;$i<=$endPage;$i++)
        {
             $html.=($currentPage==$i)?' <b><a href="'.pageUrl($i,$functionName).'" >'.$i.'</a></b> ':' <a href="'.pageUrl($i,$functionName).'" >'.$i.'</a> ';
        }
        if($currentPage+5<$pageCount)
        {
            $html.='... <a href="'.pageUrl($pageCount,$functionName).'" >'.$pageCount.'</a>';
        }
    }else{
        for($i=1;$i<=$pageCount;$i++)
        {
            $html.=($currentPage==$i)?'<b><a href="'.pageUrl($i,$functionName).'" >'.$i.'</a></b>':'<a href="'.pageUrl($i,$functionName).'" >'.$i.'</a>';
        }
    }
    $html.="</div><div class='clear'></div> ";
    return $html;
}

function pageUrl($pageNum,$functionName='')
{
    if($functionName)
    {
        return 'javascript:'.str_ireplace('#PAGE#', $pageNum, $functionName);
    }else
    return $_SERVER['PHP_SELF'].'?'.preg_replace('/[&]?page=(\w+)/', '', $_SERVER['QUERY_STRING']).'&page='.$pageNum;
}

function set_Cookie($name,$value,$expire=0)
{

        
    if(setcookie($name,$value,$expire,'/'))
        return true;
    else 
    {
        core::error('cookie设置失败！');
        return false;
    }        
}

function iflogined(){
    $userid=isset($_COOKIE['uid'])?$_COOKIE['uid']:0;
    $key=isset($_COOKIE['ukey'])?base64_decode($_COOKIE['ukey']):0;
    if(!$userid||!$key)
    {
        define('UID', 0);
        return false;
    }

    $db=DB::GetDbObject();
    $db->query('select * from user where id='.(int)$userid.' ');
    $result=$db->fetch();
    if(substr($result['password'], 0,3)==$key)
    {
        define('UID', $result['id']);
        define('UNAME',$result['username']);
        $act=new ActModel();
        $useract=$act->getUserAct();
        $GLOBALS['userAct']=$useract;
    }else {
        define('UID',0);
        return false;
    }
    
}

function loadView($path='')
{
    global $config;
    if(!file_exists($config['view_path'].$path))
    {
        core::error('模板不存在！');
        return false;
    }else 
    {
        
        return $config['view_path'].$path;
    }
}

function encodedata(array $data)
{
    echo json_encode(array(
        'result'=>true,
        key($data)=>$data[key($data)]
    ));
    exit;
}

function add_slashes(array $data)
{
    if(get_magic_quotes_gpc()) return $data;
    
    foreach ($data as $key=>$val)
    {
        if(is_array($val))
        {
            $data[$key]=add_slashes($val);
        }
        if(is_string($val))
            $data[$key]=addslashes($val);
    }
    return $data;
}
