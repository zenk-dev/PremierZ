#! /bin/sh
# 30日以上経過したMySQLのダンプファイルを削除する

logdir="/var/www/vhosts/zenk.flxsrv.biz/httpdocs/cron/mysqlbackup/"
deletelimit=30
findcommand="eval find ${logdir} -daystart -type f -mtime +${deletelimit}"

# findcommandの実行結果を配列に格納
findresult=`eval ${findcommand} | tr "[:cntrl:]" " "`

findarray=($findresult)

if [ ${#findarray[*]} -ne 0 ]
then
	for trgtfile in ${findarray[@]}
	do
		rm $trgtfile
	done
fi
