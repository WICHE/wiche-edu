#!/bin/bash
AMAZEEIO_TMP_PATH=/tmp

echo "Dumping Database locally"
drush sql-dump --result-file=$AMAZEEIO_TMP_PATH/dump_sqlpush.sql

echo "Cleaning up Trigger Definer Queries"
sed -i 's/DEFINER=[^*]*\*/\*/g' $AMAZEEIO_TMP_PATH/dump_sqlpush.sql

echo "Copy Database to remote $1"
drush rsync -y $AMAZEEIO_TMP_PATH/dump_sqlpush.sql $1:/tmp/dump_sqlpush.sql

echo "Import Database on remote $1"
drush $1 ssh drush sql-cli < /tmp/dump_sqlpush.sql

echo "Removing remote dump file on $1"
drush $1 ssh rm /tmp/dump_sqlpush.sql
