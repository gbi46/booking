<?php
/* * ********************************************************************************************
 * 								Open Real Estate
 * 								----------------
 * 	version				:	V1.23.0
 * 	copyright			:	(c) 2015 Monoray
 * 							http://monoray.net
 * 							http://monoray.ru
 *
 * 	website				:	http://open-real-estate.info/en
 *
 * 	contact us			:	http://open-real-estate.info/en/contact-us
 *
 * 	license:			:	http://open-real-estate.info/en/license
 * 							http://open-real-estate.info/ru/license
 *
 * This file is part of Open Real Estate
 *
 * ********************************************************************************************* */

class MainController extends ModuleAdminController
{

    public $modelName = 'Service';
    public $_path = null;
    public $_directoryToBackup = array();
    public $_filesToBackup = array();
    public $_allowDirectoryBackup = array();
    public $_excludeDirectoryBackup = array();
    public $_excludeFileBackup = array();
    public $_excludeFileOREBackup = array();
    public $_directoryRestoreBackup = null;
    private $tables = array();
    private $fp;
    private $fileName;
    private $fileDDName;
    private $backupTempFile = 'ore_backup_';
    private $backupDBTempFile = 'ore_backup_database.sql';

    public function init()
    {
        $backupsDirectoryName = Service::BACKUPS_DIRECTORY_NAME;
        $this->_path = Yii::getPathOfAlias("application.{$backupsDirectoryName}");
        if (!is_dir($this->_path)) {
            @mkdir($this->_path, 0777, true);
        }

        if (!is_writable($this->_path)) {
            Yii::app()->user->setFlash('error', Yii::t('common', 'Backup directory {directoryName} is not writable', array('{directoryName}' => $this->_path)));
        }

        $this->_directoryRestoreBackup = Yii::getPathOfAlias("webroot");

        $this->_directoryToBackup = array(
            'assets/' => Yii::getPathOfAlias("webroot") . DIRECTORY_SEPARATOR . 'assets/',
            'common/' => Yii::getPathOfAlias("webroot") . DIRECTORY_SEPARATOR . 'common/',
            'framework/' => Yii::getPathOfAlias("webroot") . DIRECTORY_SEPARATOR . 'framework/',
            'images/' => Yii::getPathOfAlias("webroot") . DIRECTORY_SEPARATOR . 'images/',
            'protected/' => Yii::getPathOfAlias("webroot") . DIRECTORY_SEPARATOR . 'protected/',
            're_kcfinder/' => Yii::getPathOfAlias("webroot") . DIRECTORY_SEPARATOR . 're_kcfinder/',
            'themes/' => Yii::getPathOfAlias("webroot") . DIRECTORY_SEPARATOR . 'themes/',
            'uploads/' => Yii::getPathOfAlias("webroot") . DIRECTORY_SEPARATOR . 'uploads/',
        );

        $this->_filesToBackup = array(
            'favicon.ico' => Yii::getPathOfAlias("webroot") . DIRECTORY_SEPARATOR . 'favicon.ico',
            'index.php' => Yii::getPathOfAlias("webroot") . DIRECTORY_SEPARATOR . 'index.php',
            'license.txt' => Yii::getPathOfAlias("webroot") . DIRECTORY_SEPARATOR . 'license.txt',
            'robots.txt' => Yii::getPathOfAlias("webroot") . DIRECTORY_SEPARATOR . 'robots.txt',
        );

        $dataArray = $this->getBackupFilesList($this->path . DIRECTORY_SEPARATOR);
        if ($dataArray && !empty($dataArray)) {
            $namesBackUpArray = CHtml::listData($dataArray, 'name', 'name');

            if (!empty($namesBackUpArray)) {
                foreach ($namesBackUpArray as $key => $name) {
                    unset($namesBackUpArray[$key]);

                    $this->_excludeFileOREBackup[] = $name;
                }
            }
        }

        $this->_excludeDirectoryBackup = array(
            Yii::getPathOfAlias("webroot") . '\\.hg/',
            Yii::getPathOfAlias("webroot") . '/.hg/',
            Yii::getPathOfAlias("webroot") . '\\.git/',
            Yii::getPathOfAlias("webroot") . '/.git/',
            Yii::getPathOfAlias("webroot") . '\\.well-known/',
            Yii::getPathOfAlias("webroot") . '/.well-known/',
        );

        parent::init();
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'expression' => "Yii::app()->user->checkAccess('service_site_admin')",
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionAdmin()
    {
        $model = $this->loadModel(Service::SERVICE_ID);

        $originalExecTime = ini_get("max_execution_time");
        $originalMemoryLimit = ini_get("memory_limit");

        $allowCustomMaxExecTime = false;
        $dataProviderBackups = array();

        $uploadFolderSize = folderSize(Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . Images::UPLOAD_DIR);
        if ($uploadFolderSize) {
            $uploadFolderSize = floor($uploadFolderSize / (1024 * 1024));
        }

        $hostingPartnerUrl = (Yii::app()->language == 'ru') ? '<a href="http://timeweb.com/ru/?i=31081" target="_blank" rel="nofollow">Timeweb</a>' : '<a href="https://secure1.inmotionhosting.com/cgi-bin/gby/clickthru.cgi?id=monohosting&amp;page=7" target="_blank" rel="nofollow">InMotion</a>';

        try {
            ini_set("max_execution_time", Service::CUSTOM_MAX_EXEC_TIME);
        } catch (Exception $e) {
            //echo $e->getMessage();
        }

        if (ini_get("max_execution_time") == Service::CUSTOM_MAX_EXEC_TIME) {
            $allowCustomMaxExecTime = true;

            $dataArray = $this->getBackupFilesList($this->path . DIRECTORY_SEPARATOR);

            $dataProviderBackups = new CArrayDataProvider($dataArray);
        }

        $this->performAjaxValidation($model);

        if (isset($_POST[$this->modelName])) {
            if (demo() || devVersion()) {
                throw new CException(tc('Sorry, this action is not allowed on the demo server.'));
            }

            $model->attributes = $_POST[$this->modelName];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', tc('Success'));
            } else {
                Yii::app()->user->setFlash('error', tc('Error. Repeat attempt later'));
            }
        }

        $this->render('admin', array(
            'model' => $model,
            'allowCustomMaxExecTime' => $allowCustomMaxExecTime,
            'uploadFolderSize' => $uploadFolderSize,
            'hostingPartnerUrl' => $hostingPartnerUrl,
            'dataProviderBackups' => $dataProviderBackups,
            )
        );
    }

    public function actionDoClear()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $target = Yii::app()->request->getParam('target');

            $text = '';
            //$text = '<div class="flash-error">'.tc('Error. Repeat attempt later').'</div>';

            if (in_array($target, array('assets', 'runtime'))) {
                $cacheDir = '';
                switch ($target) {
                    case 'assets':
                        $cacheDir = Yii::app()->assetManager->basePath;
                        break;
                    case 'runtime':
                        $cacheDir = Yii::app()->runtimePath;

                        Yii::app()->cache->flush();
                        break;
                }

                if ($cacheDir && is_dir($cacheDir)) {
                    $excludeFiles = array('.empty', /* 'state.bin', */ 'already_install');
                    $excludeDirs = array('cache', 'HTML', 'minScript', 'URI', 'assets');

                    $this->rrmdir($cacheDir, $excludeFiles, $excludeDirs);
                    $text = '<div class="flash-success">' . Yii::t('module_service', 'Cache files in the folder {folder} have been successfully removed', array('{folder}' => $cacheDir)) . '</div>';
                }
            }

            echo $text;
            Yii::app()->end();
        }
    }

    function rrmdir($dir, $excludeFiles = array(), $excludeDirs = array(), $depth = 0)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            if ($objects) {
                foreach ($objects as $object) {
                    if ($object != "." && $object != ".." && !in_array($object, $excludeFiles)) {
                        if (filetype($dir . DIRECTORY_SEPARATOR . $object) == "dir") {
                            $depth = $depth + 1;
                            $this->rrmdir($dir . DIRECTORY_SEPARATOR . $object, $excludeFiles, $excludeDirs, $depth);
                        } else {
                            @unlink($dir . DIRECTORY_SEPARATOR . $object);
                        }
                    }
                }
            }

            reset($objects);
            if (!in_array(substr($dir, strrpos($dir, '/') + 1), $excludeDirs) && $depth)
                @rmdir($dir);
        }
    }

    public function actionCreateBackup()
    {
        if (demo() || devVersion()) {
            Yii::app()->user->setFlash('error', tc('Sorry, this action is not allowed on the demo server.'));
            $this->redirect(array('admin'));
        }

        $this->createBackup();

        $this->clearOldDBFiles();

        $this->redirect(array('admin'));
    }

    public function actionDownloadBackup($file = null)
    {
        if (demo() || devVersion()) {
            Yii::app()->user->setFlash('error', tc('Sorry, this action is not allowed on the demo server.'));
            $this->redirect(array('admin'));
        }

        //@ini_set("memory_limit","256M");		

        if (isset($file)) {
            $ziplFile = $this->getPath() . DIRECTORY_SEPARATOR . basename($file);
            if (file_exists($ziplFile)) {
                Controller::disableProfiler();

                # del old downloads
                $maskToDelete = Yii::getPathOfAlias('webroot.assets') . DIRECTORY_SEPARATOR . '_tmp_bk_dl_*.zip';
                $dirs = glob($maskToDelete);
                if ($dirs && is_array($dirs)) {
                    @array_map("unlink", $dirs);

                    /* foreach (glob($maskToDelete) as $dir) {
                      $timeUpdated = filemtime($dir);

                      if ($timeUpdated < time() + 1440) { # 1 час
                      @rrmdir($dir);
                      }
                      } */
                }

                /* $fileContent = file_get_contents($ziplFile);

                  if (!empty($fileContent)) {
                  Yii::app()->request->sendFile(basename($file), $fileContent);
                  }
                  else {
                  throw new CHttpException(500, tc('Error. Repeat attempt later'));
                  } */

                /* Yii::app()->request->xSendFile($ziplFile,array(
                  'saveName'=>basename($file),
                  'mimeType'=>'application/zip',
                  'terminate'=>true,
                  )); */


                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $fileExt = pathinfo($file, PATHINFO_EXTENSION);

                $newFileName = '_tmp_bk_dl_' . md5(uniqid() . time()) . '.' . $fileExt;
                $newFileFullPath = Yii::getPathOfAlias('webroot.assets') . DIRECTORY_SEPARATOR . $newFileName;

                if (copy($ziplFile, $newFileFullPath)) {
                    $fInfo = finfo_open(FILEINFO_MIME_TYPE);
                    header('Content-Type: ' . finfo_file($fInfo, $newFileFullPath));
                    finfo_close($fInfo);

                    header('Content-Disposition: attachment; filename=' . basename($file));
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($newFileFullPath));

                    ob_clean();
                    flush();
                    readfile($newFileFullPath);

                    Yii::app()->end();
                } else {
                    throw new CHttpException(404, tt('File not found'));
                }
            }
        }
        throw new CHttpException(404, tt('File not found'));
    }

    public function actionDeleteBackup($file = null, $action = true)
    {
        if (isset($file)) {
            $zipFile = $this->getPath() . DIRECTORY_SEPARATOR . basename($file);
            if (file_exists($zipFile)) {
                @unlink($zipFile);
            }
        } else
            throw new CHttpException(404, tt('File not found'));

        if ($action) {
            $this->actionAdmin();
        }
    }

    public function actionRestoreBackup($file = null)
    {
        if (demo() || devVersion()) {
            Yii::app()->user->setFlash('error', tc('Sorry, this action is not allowed on the demo server.'));
            $this->redirect(array('admin'));
        }

        set_time_limit(7200);

        if (isset($file)) {
            try {
                $this->execZipFile($this->getPath() . DIRECTORY_SEPARATOR . basename($file));
                Yii::app()->user->setFlash('success', Yii::t('module_service', 'Successfully restored the backup file: {backup}', array('{backup}' => basename($file))));
                $this->clearOldDBFiles();
            } catch (Exception $e) {
                $this->clearOldDBFiles();

                //throw new CHttpException(500, $e->getMessage());
                /* Yii::app()->user->setFlash('error',$e->getMessage()); */
                Yii::app()->user->setFlash('error', Yii::t('module_service', 'Unable to restore backup correctly: {backup}', array('{backup}' => basename($file))));
            }
        }

        $this->clearOldDBFiles();

        $this->redirect(array('admin'));
    }
    ##################################################################################

    public function getBackupFilesList($path)
    {
        $dataArray = array();

        $listFiles = glob($path . '*.zip');

        if ($listFiles) {
            $list = array_map('basename', $listFiles);
            sort($list);

            foreach ($list as $id => $filename) {
                $columns = array();
                $columns['id'] = $id;
                $columns['name'] = basename($filename);
                $columns['size'] = floor(filesize($path . $filename) / 1024) . ' KB';
                $columns['create_time'] = Yii::app()->locale->dateFormatter->formatDateTime(filectime($path . $filename), "full", null);
                $dataArray[] = $columns;
            }
        }

        return $dataArray;
    }

    protected function getPath()
    {
        if (!is_dir($this->_path)) {
            @mkdir($this->_path, 0777, true);
        }
        return $this->_path;
    }

    private function getColumns($tableName)
    {
        $sql = 'SHOW CREATE TABLE ' . $tableName;
        $cmd = Yii::app()->db->createCommand($sql);
        $table = $cmd->queryRow();

        $create_query = $table['Create Table'] . '~';

        $create_query = preg_replace('/^CREATE TABLE/', 'CREATE TABLE IF NOT EXISTS', $create_query);
        //$create_query = preg_replace('/AUTO_INCREMENT\s*=\s*([0-9])+/', '', $create_query);
        if ($this->fp) {
            $this->writeComment('TABLE `' . addslashes($tableName) . '`');
            $final = 'DROP TABLE IF EXISTS `' . addslashes($tableName) . '`~' . PHP_EOL . $create_query . PHP_EOL . PHP_EOL;
            fwrite($this->fp, $final);
        } else {
            $this->tables[$tableName]['create'] = $create_query;
            return $create_query;
        }
    }

    private function getData($tableName)
    {
        $sql = 'SELECT * FROM ' . $tableName;
        $cmd = Yii::app()->db->createCommand($sql);
        $dataReader = $cmd->query();

        $data_string = '';

        foreach ($dataReader as $data) {
            $itemNames = array_keys($data);
            $itemNames = array_map("addslashes", $itemNames);
            $items = join('`,`', $itemNames);
            $itemValues = array_values($data);
            $itemValues = array_map("addslashes", $itemValues);
            $valueString = join("','", $itemValues);
            $valueString = "('" . $valueString . "'),";
            $values = "\n" . $valueString;
            if ($values != "") {
                $data_string .= "INSERT INTO `$tableName` (`$items`) VALUES" . rtrim($values, ",") . "~" . PHP_EOL;
            }
        }

        if (empty($data_string)) {
            return null;
        }

        if ($this->fp) {
            $this->writeComment('TABLE DATA ' . $tableName);
            $final = $data_string . PHP_EOL . PHP_EOL . PHP_EOL;
            fwrite($this->fp, $final);
        } else {
            $this->tables[$tableName]['data'] = $data_string;
            return $data_string;
        }
    }

    private function getTables($dbName = null)
    {
        $sql = 'SHOW TABLES';
        $cmd = Yii::app()->db->createCommand($sql);
        $tables = $cmd->queryColumn();
        return $tables;
    }

    private function StartBackup($addcheck = true)
    {
        $this->fileDDName = $this->path . DIRECTORY_SEPARATOR . $this->backupDBTempFile;

        $this->fp = fopen($this->fileDDName, 'w+');

        if ($this->fp == null) {
            return false;
        }

        fwrite($this->fp, '-- -------------------------------------------~' . PHP_EOL);

        if ($addcheck) {
            /* fwrite($this->fp,  'SET AUTOCOMMIT=0~' .PHP_EOL );
              fwrite($this->fp,  'START TRANSACTION~' .PHP_EOL ); */
            fwrite($this->fp, 'SET SQL_QUOTE_SHOW_CREATE = 1~' . PHP_EOL);
        }

        fwrite($this->fp, 'SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0~' . PHP_EOL);
        fwrite($this->fp, 'SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0~' . PHP_EOL);
        fwrite($this->fp, '-- -------------------------------------------~' . PHP_EOL);
        $this->writeComment('START BACKUP');

        return true;
    }

    private function EndBackup($addcheck = true)
    {
        fwrite($this->fp, '-- -------------------------------------------~' . PHP_EOL);
        fwrite($this->fp, 'SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS~' . PHP_EOL);
        fwrite($this->fp, 'SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS~' . PHP_EOL);

        if ($addcheck) {
            fwrite($this->fp, 'COMMIT~' . PHP_EOL);
        }

        fwrite($this->fp, '-- -------------------------------------------~' . PHP_EOL);
        fwrite($this->fp, '-- -------------------------------------------~' . PHP_EOL);
        fwrite($this->fp, '-- END BACKUP~' . PHP_EOL);
        fwrite($this->fp, '-- -------------------------------------------');

        fclose($this->fp);
        $this->fp = null;
    }

    private function writeComment($string)
    {
        fwrite($this->fp, '-- -------------------------------------------~' . PHP_EOL);
        fwrite($this->fp, '-- ' . $string . '~' . PHP_EOL);
        fwrite($this->fp, '-- -------------------------------------------~' . PHP_EOL);
    }

    private function createBackupDb($tables)
    {
        //@ini_set("memory_limit","256M");
        if (!$this->StartBackup()) {
            //render error
            throw new Exception("Error", 500);
            return false;
        }

        foreach ($tables as $tableName) {
            $this->getColumns($tableName);
        }

        foreach ($tables as $tableName) {
            $this->getData($tableName);
        }

        $this->EndBackup(false);
    }

    private function zipDirectory($zip, $alias, $directory)
    {
        if ($handle = opendir($directory)) {
            while (($file = readdir($handle)) !== false) {
                #logs('$directory.$file=');
                #logs($directory.$file.'/');
                if (is_dir($directory . $file) && $file != "." && $file != ".." && !in_array($directory . $file . '/', $this->_excludeDirectoryBackup)) {
                    #logs('$directory.$file=');
                    #logs($directory.$file.'/');

                    $this->zipDirectory($zip, $alias . $file . '/', $directory . $file . '/');
                }

                if (is_file($directory . $file) && (!in_array($directory . $file, $this->_excludeFileBackup)) && !in_array($file, $this->_excludeFileOREBackup)) {
                    $fileToAdd = $alias . $file;
                    if (version_compare(PHP_VERSION, "7.0.0", "<=")) {
                        $fileToAdd = iconv(mb_detect_encoding($fileToAdd, mb_detect_order(), true), "CP866//IGNORE", $fileToAdd);
                    }
                    $zip->addFile($directory . $file, $fileToAdd);
                }
            }
            closedir($handle);
        }
    }

    private function createZipBackup()
    {
        set_time_limit(7200);
        $zip = new ZipArchive;

        $opened = $zip->open($this->fileName, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
        if ($opened === true) {
            /* try { */
            $fileToAdd = $this->backupDBTempFile;
            if (version_compare(PHP_VERSION, "7.0.0", "<=")) {
                $fileToAdd = iconv(mb_detect_encoding($fileToAdd, mb_detect_order(), true), "CP866//IGNORE", $fileToAdd);
            }
            $zip->addFile($this->fileDDName, $fileToAdd);

            // clear cache
            $excludeFiles = array('.empty', /* 'state.bin', */ 'already_install');
            $excludeDirs = array('cache', 'HTML', 'minScript', 'URI', 'assets');

            Yii::app()->cache->flush();
            $assetsDir = Yii::app()->assetManager->basePath;

            if ($assetsDir && is_dir($assetsDir)) {
                $this->rrmdir($assetsDir, $excludeFiles, $excludeDirs);
            }

            $cacheDir = Yii::app()->runtimePath;
            if ($cacheDir && is_dir($cacheDir)) {
                $this->rrmdir($cacheDir, $excludeFiles, $excludeDirs);
            }

            // zip folders
            foreach ($this->_directoryToBackup as $alias => $directory) {
                #logs('directory=');
                #logs($directory);

                if (is_dir($directory) && !in_array($directory, $this->_excludeDirectoryBackup)) {
                    $this->zipDirectory($zip, $alias, $directory);
                }

                if (is_file($directory) && !in_array($directory, $this->_excludeFileBackup)) {
                    $fileToAdd = $alias;
                    if (version_compare(PHP_VERSION, "7.0.0", "<=")) {
                        $fileToAdd = iconv(mb_detect_encoding($fileToAdd, mb_detect_order(), true), "CP866//IGNORE", $fileToAdd);
                    }
                    $zip->addFile($directory, $fileToAdd);
                }
            }

            // zip files
            foreach ($this->_filesToBackup as $alias => $directory) {
                if (is_file($directory) && !in_array($directory, $this->_excludeFileBackup)) {
                    $fileToAdd = $alias;
                    if (version_compare(PHP_VERSION, "7.0.0", "<=")) {
                        $fileToAdd = iconv(mb_detect_encoding($fileToAdd, mb_detect_order(), true), "CP866//IGNORE", $fileToAdd);
                    }
                    $zip->addFile($directory, $fileToAdd);
                }
            }

            if ($zip) {
                $zip->close();
            }
            $this->actionDeleteBackup($this->backupDBTempFile, false);
            /* } catch(Exception $e) {
              Yii::app()->user->setFlash('error', $e->getMessage());
              } */
        } else {
            Yii::app()->user->setFlash('error', Yii::t('module_service', 'Failed to create the backup: {backup}', array('{backup}' => $this->fileName)));
        }
    }

    public function createBackup()
    {
        $tables = $this->getTables();
        $this->createBackupDb($tables);

        $this->backupTempFile = $this->backupTempFile . date('Y_m_d__His') . '.zip';
        $this->fileName = $this->path . DIRECTORY_SEPARATOR . $this->backupTempFile;

        try {
            $this->createZipBackup();
            Yii::app()->user->setFlash('success', Yii::t('module_service', 'Successfully Create the backup file: {backup}', array('{backup}' => $this->backupTempFile)));
        } catch (Exception $e) {
            $this->actionDeleteBackup($this->backupTempFile, false);
            $this->actionDeleteBackup($this->backupDBTempFile, false);

            Yii::app()->user->setFlash('error', Yii::t('module_service', 'Failed to create the backup: {backup}', array('{backup}' => $this->backupTempFile)));
        }
    }

    private function execSqlFile($sqlFile)
    {
        if (file_exists($sqlFile)) {
            $queries = explode('~', file_get_contents($sqlFile));
            $connection = Yii::app()->db;

            foreach ($queries as $query) {
                if (!empty($query)) {
                    $connection->createCommand($query)->execute();
                }
            }
        } else
            throw new CHttpException(404, tt('File not found'));
    }

    private function execZipFile($zipFile)
    {
        $zip = new ZipArchive;
        $res = $zip->open($zipFile);
        if ($res === true) {
            if ($zip) {
                $zip->extractTo($this->_directoryRestoreBackup);
                $zip->close();
            }

            $this->execSqlFile($this->_directoryRestoreBackup . DIRECTORY_SEPARATOR . $this->backupDBTempFile);

            $this->actionDeleteBackup($this->backupDBTempFile, false);

            if (file_exists($this->backupDBTempFile)) {
                @unlink($this->backupDBTempFile);
            }

            if (file_exists($this->_directoryRestoreBackup . DIRECTORY_SEPARATOR . $this->backupDBTempFile)) {
                @unlink($this->_directoryRestoreBackup . DIRECTORY_SEPARATOR . $this->backupDBTempFile);
            }
        }
    }

    private function clearOldDBFiles()
    {
        $this->actionDeleteBackup($this->backupDBTempFile, false);

        if (file_exists($this->backupDBTempFile)) {
            @unlink($this->backupDBTempFile);
        }

        if (file_exists($this->_directoryRestoreBackup . DIRECTORY_SEPARATOR . $this->backupDBTempFile)) {
            @unlink($this->_directoryRestoreBackup . DIRECTORY_SEPARATOR . $this->backupDBTempFile);
        }
    }
}
