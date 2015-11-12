#!/bin/bash
#Truncar la bitacora de transacciones del dbms
#Se deben agregar las siguientes lineas al /etc/mysql/my.cnf
#[mysqld]
#log-bin=mysql-bin
#
echo "Truncando la bitacora de transacciones del dbms..."
mysql -u root -p -Bse "PURGE BINARY LOGS BEFORE NOW();"
echo "Proceso finalizado."