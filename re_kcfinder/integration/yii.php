<?php
$yiiApp = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..');

$yii = $yiiApp.'/framework/yii.php';

if (file_exists($yiiApp.'/protected/config/main.php')) {
	$config = $yiiApp.'/protected/config/main.php';
}
else {
	$config = $yiiApp.'/protected/config/main-free.php';
}

// remove the following lines when in production mode
//defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

define('ROOT_PATH', $yiiApp);
define('ALREADY_INSTALL_FILE', ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR
                                . 'runtime' . DIRECTORY_SEPARATOR . 'already_install');

require_once($yii);

Yii::createWebApplication($config);

# проверка на доступ
$disabled = true;

if (Yii::app()->user->getState("isAdmin") || Yii::app()->user->getState("isModerator")) {
	$disabled = false;
}

/*
# проверка на доступ
$disabled = true;

$dbFilePath = $yiiApp."/protected/config/db.php";
$dbConfigFileContent = require($dbFilePath);

if (!class_exists('HDatabase')) {
	$dbClassPath = $yiiApp."/protected/helpers/HDatabase.php";
	require($dbClassPath);
}

$dbConfig['tablePrefix'] = $dbConfigFileContent['components']['db']['tablePrefix'];
$dbConfig['connectionString'] = $dbConfigFileContent['components']['db']['connectionString'];
$dbConfig['charset'] = $dbConfigFileContent['components']['db']['charset'];
$dbConfig['username'] = $dbConfigFileContent['components']['db']['username'];
$dbConfig['password'] = $dbConfigFileContent['components']['db']['password'];

$db = new HDatabase($dbConfig);
$res = $db->getUserInfoBySessionId(Yii::app()->session->sessionId);
if ($res && ($res['role'] == 'admin' || $res['role'] == 'moderator')) {
	$disabled = false;
}
*/

# директории
$uploadURL = Yii::app()->getBaseUrl(true).'/uploads/editor';
$uploadURL = str_replace('/re_kcfinder', '', $uploadURL);

$uploadDir = $yiiApp.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'editor';

$session = new CHttpSession;
//$session->setSavePath(Yii::app()->session->savePath);
$session->open();

$session['KCFINDER'] = array(
    'disabled' => $disabled,
    'uploadURL' => $uploadURL,
    'uploadDir' => $uploadDir,
);

//chdir($currentCwd);

Yii::$enableIncludePath = false;
spl_autoload_unregister(array('YiiBase','autoload'));

