<?php

// Переопределяющий конфиг файл на локальном хосте
$localConfig = dirname(__FILE__).'/protected/config/local.php';

//Включает режим установки Ygin.
define('YGIN_NEED_INSTALL', !file_exists($localConfig));
if (YGIN_NEED_INSTALL) { 
  defined('YII_DEBUG') or define('YII_DEBUG', true);
  defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
}

$local = null;
if (!(defined('YGIN_NEED_INSTALL') && YGIN_NEED_INSTALL)) {
  if (file_exists($localConfig)) {
    $local = require($localConfig);
  }
}

require_once(dirname(__FILE__).'/ygin/yii/YiiBase.php');

$config = array();
$applicationClass = null;

$request = new CHttpRequest();
if (substr($request->getRequestUri(), 0, 6) == "/admin") {  // система управлением
  $config = require(dirname(__FILE__).'/ygin/modules/backend/config/mainConfig.php');
  require_once dirname(__FILE__).'/ygin/modules/backend/components/BackendApplication.php';
  $applicationClass = 'BackendApplication';
} else if (substr($request->getRequestUri(), 0, 5) == "/yiic") {
  if (!defined("YII_DEBUG") || YII_DEBUG == false) {
    echo "yiic - bad request. Enable YII_DEBUG.";
  } else {
    require(dirname(__FILE__).'/ygin/yiic.php');
  }
  return;
} else {  // приложение по умолчанию
  if (defined('YGIN_NEED_INSTALL') && YGIN_NEED_INSTALL) {
    $config = require(dirname(__FILE__).'/ygin/modules/install/config/install.php');
  } else {
    $config = require(dirname(__FILE__).'/ygin/config/mainConfig.php');
  }
  require_once dirname(__FILE__).'/ygin/components/DaWebApplication.php';
  $applicationClass = 'DaWebApplication';
}

if ($local != null) $config = CMap::mergeArray($config, $local);
//print_r($config);exit;
// Для того чтобы лучше был автокомплит
class Yii extends YiiBase {
    /**
     * @static
     * @return BaseApplication
     */
    public static function app()
    {
        return parent::app();
    }
}
Yii::createApplication($applicationClass, $config)->run();
