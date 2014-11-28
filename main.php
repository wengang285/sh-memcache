<?php
ini_set( 'display_errors', 'on' );
//error_reporting(E_ALL);
error_reporting(E_ERROR);

require_once 'osslib.inc.php';
require_once 'CKV.class.php';




$filePath=$_SERVER['argv'][1];



$iActivityId = $_SERVER['argv'][2];

$service = $_SERVER['argv'][3];

echo "filePath:".$filePath."\n";
echo "service:".$service."\n";
echo "iActivityId:".$iActivityId."\n";


if(!is_file($filePath)){

	echo "{$filePath} is not exist\n";
	exit();
	
}


$fp = file($filePath);

foreach($fp as &$line){

	//过滤掉换行符
	$line = str_replace("\n","",$line);
	
	//空格转成\t，方便统一处理
	$line = str_replace(" ","\t",$line);
	
	$lineArray = explode("\t",$line);
	
	
	$iUin= $lineArray[0];
	$iArea=$lineArray[1];

	$key = "{$service}_clientpopup_{$iUin}";
	
	
	$newValue = array(
        'iUin'=>$iUin,
        'iArea'=>$iArea,
        'iPopUpCount'=>0,
        'isPoped'=>0,
        'iActivityId'=>$iActivityId,
        'isPrized'=>0,
        'iUrlType'=>0,
        'iLevel'=>0,
        'dtPopDate'=>'0000-00-00 00:00:00',
		'dtValidTime'=>'0000-00-00 00:00:00'

	);

	
	$oldValue = CommonCKV::get($key);

	if($oldValue===false){
		echo "get {$key} return false\n";
	}

	//循环数组，查看是否需要更新
	$isInCache= false;
	foreach($oldValue as $value){
		if($value['iActivityId']==$iActivityId){
			$isInCache =true;
		}
	}

	//不在缓存中，则追加
	if($isInCache==false){

		$oldValue[]=$newValue;
		$iRet = CommonCKV::set($key, $oldValue, 0, 300);
		if($iRet===false){
			echo "set {$key} return false\n";
			//保存失败信息
			saveErrorLog($iUin,$iArea,$iActivityId,$service);
		}
		
	}
}


function GetCommonConfig()
{
    $g_config = parse_ini_file("/usr/local/commweb/cfg/CommConfig/commconf.cfg", true);
    return $g_config;
}

/**
**保存失败信息
**/
function saveErrorLog($iUin,$iArea,$iActivityId,$sService){
    
    $_config = GetCommonConfig();
	$db = new DBProxy(
		$_config["6125_ieod_test_db"]["proxy_ip"],
		$_config["6125_ieod_test_db"]["proxy_port"],
		'dbCKVLogGavinwen'
	);
	
	
	$sql = "insert into tbCKVErrorLog(iUin,iArea,iActivityId,sService,dtCreateTime) values({$iUin},{$iArea},{$iActivityId},'{$sService}',now());";
	
	
	try{
		$res = $db->ExecUpdate($sql);
		if($res >= 0){
			return true;
		}
		else{
			return false;
		}
	}
	catch(Exception $e){
		return -1;
	}
	
    
}


echo "set {$key} return true\n";






?>
