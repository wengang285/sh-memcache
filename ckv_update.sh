#!/bin/sh
#获取需要更新的文件路径、业务名称、任务id
#拆分文件->单个进程读取文件->更新ckv->更新任务完成标志位->通知调用端
#

#获取shell脚本当前目录

#参数小于三个，提出

export LD_LIBRARY_PATH=/usr/local/oss_dev/lib

if [ $# -lt 3 ]; then
	echo "param num is less then 3"
	exit
fi



filepath=$(cd "$(dirname "$0")"; pwd)

data_path=$1

#源文件不存在
if [ ! -f $data_path ]; then
	echo $data_path" is not exist"
	exit
fi

line_num=`wc -l $data_path|awk '{print $1}'`

per_file_num=$((line_num/100))



data_file_name=`basename $data_path`


service=$2
task_id=$3




#任务目录
task_dir_path=$filepath"/"$task_id

rm -rf $task_dir_path

if [ ! -x $task_dir_path ]; then
	mkdir $task_dir_path
fi

#清除所有文件
#rm -rf $task_dir_path"/*"

#cp $data_path $task_dir_path"/"


#new_data_file=$task_dir_path"/"$data_file_name

#拆分成100文件

split -l $per_file_num $data_path $task_dir_path"/"$service"_"$task_id"_"

#循环，多进程处理
for file in `ls $task_dir_path`
do
	tmp_file_path=$task_dir_path"/"$file
	
	
	#sh $filepath"/single_file_update.sh" $tmp_file_path $service $task_id >/dev/null &
	
	#echo "sh $filepath"/single_file_update.sh" $tmp_file_path $service $task_id >/dev/null &"
	
done





#echo $new_data_file
















