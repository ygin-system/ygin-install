<?php

// За основу берем проектный конфиг
$projectConfig = include dirname(__FILE__).'/project.php';

// Можно например переопределить контроллер по умолчанию, или сделать любые другие модификации в конфиг бэкэнд-приложения
// $projectConfig['defaultController'] = 'yiigin/default';

return $projectConfig;
