<?php
 return array (
  'components' => 
  array (
    'db' => 
    array (
      'class' => 'CDbConnection',
      'connectionString' => 'mysql:host=localhost;dbname=booking2;port=3306',
      'username' => 'root',
      'password' => '',
      'emulatePrepare' => true,
      'charset' => 'utf8',
      'enableParamLogging' => false,
      'enableProfiling' => false,
      'schemaCachingDuration' => 7200,
      'tablePrefix' => 'ore_ly_',
    ),
  ),
  'language' => 'ru',
) ;
?>