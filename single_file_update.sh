#!/bin/sh
###处理切分之后的单个文件
export LD_LIBRARY_PATH=/usr/local/ieod-web/lib:/usr/local/lib:/usr/local/oss_dev/lib:/usr/local/ice/lib:/usr/local/mysql/lib/mysql/:$LD_LIBRARY_PATH

file_path=$1

service=$2

activity_id=$3

filepath=$(cd "$(dirname "$0")"; pwd)




	
/usr/local/ieod-web/php/bin/php $filepath"/"main.php $file_path $activity_id $service >/dev/null &







