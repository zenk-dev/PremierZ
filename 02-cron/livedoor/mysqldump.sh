#! /bin/sh
# MySQLの日時バックアップ
# ZENKZPデータベースについて、TABLEの再作成とデータの挿入を行う
# ダンプファイルを取得する
logdir="/var/www/vhosts/zenk.flxsrv.biz/httpdocs/cron/mysqlbackup/"
filename="mysqldump`date +"%Y-%m-%d"`"
databasename="logindzobk_23003_ZENKZP"
resultflag=0

# すでに同じ名前のファイルがある場合は削除する
if test -e ${logdir}${filename};then
    rm -f ${logdir}${filename}
fi

# ダンプ取得
mysqldump -u zenkzp -pzproject --opt ${databasename} > ${logdir}${filename}

# tar.gz化
if test -e ${logdir}${filename};then
    cd ${logdir}
    tar czf ${filename}.tar.gz ${filename}

    if test -e ${filename}.tar.gz;then
        rm -f ${filename}
    else
        resutlflag=2
    fi
else
    resutlflag=2
fi

# 例外処理
if [ ${resultflag} -eq 2 ]
then
    echo "Backup failed." > ${logdir}${filename}
fi
