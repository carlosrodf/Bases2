#!/bin/bash
#Backup completo del dbms
echo "Iniciando proceso de backup..."
mysqldump --all-databases > full_backup.sql -u root -p
echo "Backup finalizado."