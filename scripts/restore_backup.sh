#!/bin/bash
#Restauracion completa del dmbs
echo "Iniciando proceso de restauracion..."
mysql -u root -p < full_backup.sql
echo "Restauracion finalizada."