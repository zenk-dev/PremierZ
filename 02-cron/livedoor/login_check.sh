#! /bin/sh
homedir="/var/www/vhosts/zenk.flxsrv.biz/httpdocs/"
crondir=${homedir}"cron/"
logdir=${crondir}"logincheck/"
webhomedir=${homedir}"PremierZ/"

echo -e "\n" >> ${logdir}loginchecklog
echo "[`date +"%Y-%m-%d_%k%M"`]" >> ${logdir}loginchecklog

cd ${webhomedir}mdl
/usr/bin/php -q ${webhomedir}mdl/m_logincheck.php >> ${logdir}loginchecklog
chmod 700 ${logdir}loginchecklog
