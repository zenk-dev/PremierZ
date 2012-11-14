#! /bin/sh
homedir="/var/www/vhosts/zenk.flxsrv.biz/httpdocs/"
crondir=${homedir}"cron/"
logdir=${crondir}"delaycheck/"
webhomedir=${homedir}"PremierZ/"

echo -e "\n" >> ${logdir}delaychecklog
echo "[`date +"%Y-%m-%d_%k%M"`]" >> ${logdir}delaychecklog

cd ${webhomedir}mdl
/usr/bin/php -q ${webhomedir}mdl/m_alertmail.php >> ${logdir}delaychecklog
chmod 700 ${logdir}delaychecklog
