#! /bin/sh
homedir="/home/zenk1/"
crondir=${homedir}"cron/"
logdir=${crondir}"logincheck/"
webhomedir="/var/www/html/"
phpdir="/usr/local/bin/"

echo -e "\n" >> ${logdir}loginchecklog
echo "[`date +"%Y-%m-%d_%k%M"`]" >> ${logdir}loginchecklog

cd ${webhomedir}mdl
${phpdir}php -q ${webhomedir}mdl/m_logincheck.php >> ${logdir}loginchecklog
chmod 755 ${logdir}loginchecklog

