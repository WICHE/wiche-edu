<?php

/**
 * PHP settings:
 *
 * To see what PHP settings are possible, including whether they can
 * be set at runtime (ie., when ini_set() occurs), read the PHP
 * documentation at http://www.php.net/manual/en/ini.php#ini.list
 * and take a look at the .htaccess file to see which non-runtime
 * settings are used there. Settings defined here should not be
 * duplicated there so as to avoid conflict issues.
 */
ini_set('arg_separator.output',     '&amp;');
ini_set('magic_quotes_runtime',     0);
ini_set('magic_quotes_sybase',      0);
ini_set('session.cache_expire',     200000);
ini_set('session.cache_limiter',    'none');
ini_set('session.cookie_lifetime',  2000000);
ini_set('session.gc_maxlifetime',   200000);
ini_set('session.save_handler',     'user');
ini_set('session.use_only_cookies', 1);
ini_set('session.use_trans_sid',    0);
ini_set('url_rewriter.tags',        '');

### Temp directory
if (getenv('AMAZEEIO_TMP_PATH')) {
  $conf['file_temporary_path'] = getenv('AMAZEEIO_TMP_PATH');
}

if (getenv('AMAZEEIO_HASH_SALT')) {
  $drupal_hash_salt = getenv('AMAZEEIO_HASH_SALT');
}

if(getenv('AMAZEEIO_SITENAME')) {
  $databases['default']['default'] = array(
    'driver' => 'mysql',
    'database' => getenv('AMAZEEIO_SITENAME'),
    'username' => getenv('AMAZEEIO_DB_USERNAME'),
    'password' => getenv('AMAZEEIO_DB_PASSWORD'),
    'host' => getenv('AMAZEEIO_DB_HOST'),
    'port' => getenv('AMAZEEIO_DB_PORT'),
    'prefix' => 'wiche_',
  );
}

### amazee.io Base URL
if (getenv('AMAZEEIO_BASE_URL')) {
  $base_url = getenv('AMAZEEIO_BASE_URL');
  $cookie_domain = getenv('AMAZEEIO_BASE_URL');
}