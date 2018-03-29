#!/bin/bash
cd $AMAZEEIO_WEBROOT

echo "Dumping database from $1"
drush $1 ssh drush sql-dump > $AMAZEEIO_TMP_PATH/dump_$1.sql

echo "Removing DEFINER queries from SQL Dump"
sed -i 's/DEFINER=[^*]*\*/\*/g' $AMAZEEIO_TMP_PATH/dump_$1.sql

echo "Importing database..."
drush sql-cli < $AMAZEEIO_TMP_PATH/dump_$1.sql

echo "Removing intermediate dump file"
rm $AMAZEEIO_TMP_PATH/dump_$1.sql
