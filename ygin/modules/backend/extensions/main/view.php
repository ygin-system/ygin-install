<div class="b-welcome">
<?php
$badge = CHtml::asset(dirname(__FILE__).'/assets/backend_badge.png');
$version = '';
if (Yii::app()->user->checkAccess(DaWebUser::ROLE_DEV)) {
  $version = Yii::app()->version.' от '.Yii::app()->versionDate;
  /*$lastMigration = DynamicActiveRecord::forTable('da_migration')->find(array('order'=>'apply_time DESC'));
  if (preg_match('~^.(\d{6}_\d{6}).*~', $lastMigration->version, $matches)) {
    $version = '20'.$matches[1];
  }*/
}
$this->caption = 'Начало работы';
$showWelcome = true;
$cookie = Yii::app()->request->cookies['daMainWelcome'];
if ($cookie != null && $cookie->value == '1') $showWelcome = false;

if ($showWelcome) :
?>
  <div class="introduction">
    <div class="backend-badge" style="background:url(<?php echo $badge; ?>) no-repeat 0 0">
      <?php echo ($version != '' ? 'Версия '.$version : ''); ?>
    </div>
    <h3>Добро пожаловать в ваш сайт на ygin!</h3>
    <p class="about-description">
      Если вам потребуется помощь, посмотрите нашу документацию на странице «<a href="<?php echo Yii::app()->createUrl('instruction'); ?>">Первые шаги с ygin</a>». Если вы хотите сразу начать работать, здесь собраны действия, которые большинство пользователей выполняют ежедневно при работе с сайтом.
    </p>
    <p style="text-align:right;">Уже всё знаете? <button class="btn btn-mini" onclick="$.cookie('daMainWelcome', 1, {expires:10000, path:'/'}); $(this).parents('.introduction').slideUp()">Закройте это сообщение ×</button></p>
  </div>
<?php endif; ?>
<?php
  if (Yii::app()->user->checkAccess(DaWebUser::ROLE_DEV)) {
    $changeFile = Yii::getPathOfAlias('ygin').'/change_project.txt';
    $data = file_get_contents($changeFile);
    preg_match_all('~(\d{8})(.*?)(?=\d{8}|$)~s', $data, $matches);
    $lastDate = $matches[1][count($matches[1])-1];

    $showDevNotice = true;
    $lastDateDb = Yii::app()->params['last_change_project_date'];
    if ($lastDateDb == null) {
      $showDevNotice = false;
      $parameter = new SystemParameter();
      $parameter->id_system_parameter = 'ygin.ext.main.last_change_project_date';
      $parameter->id_group_system_parameter = SystemParameter::GROUP_SYSTEM;
      $parameter->name = 'last_change_project_date';
      $parameter->value = $lastDate;
      $parameter->note = 'Последняя дата проектных обновлений';
      $parameter->id_parameter_type = SystemParameter::TYPE_VARCHAR;
      $parameter->save();
    } else {
      if ($lastDateDb == $lastDate) {
        $showDevNotice = false;
      } else {
        $cookie = Yii::app()->request->cookies['yginDevNotice_'.$lastDate];
        if ($cookie != null && $cookie->value == '1') {
          $showDevNotice = false;
          $parameter = SystemParameter::model()->findByPk('ygin.ext.main.last_change_project_date');
          $parameter->value = $lastDate;
          $parameter->update(array('value'));
        }
      }
    }

    if ($showDevNotice) {
      $startShow = false;
?>
    <div class="alert alert-info">
      <button type="button" onclick="$.cookie('yginDevNotice_<?php echo $lastDate; ?>', 1, {expires:10000, path:'/'});" class="close" data-dismiss="alert">&times;</button>
      <p>Обратите внимание на последние изменения, которые необходимо внести в проектные файлы:</p>
      <?php foreach($matches[1] AS $i => $date) {
        if ($startShow) {
          echo CHtml::tag('p', array(), CHtml::tag('b', array(), nl2br(trim($matches[2][$i]))));
        }
        if ($date == $lastDateDb) $startShow = true;
      }?>
    </div>
<?php
    }
  }
?>


  <div class="plugin-list">
<?php
// черновая версия TODO
$array = array();

if (Yii::app()->authManager->checkObject(DaDbAuthManager::OPERATION_LIST, Yii::app()->user->id, 528)) {
  $arrayItem = array(
    'name'=>'<i class="icon-wrench"></i> Плагины',
    'desc'=>'Дополнения к системе, позволяющие значительно расширить функционал сайта',
    'link-list'=>'/admin/page/528/',
  );
  $array[] = $arrayItem;
}

if (Yii::app()->authManager->checkObject(DaDbAuthManager::OPERATION_LIST, Yii::app()->user->id, Menu::ID_OBJECT)) {
  $arrayItem = array(
    'name'=>'<i class="icon-list-alt"></i> Меню',
    'desc'=>'Пункты меню сайта являются основными страницами с постоянным содержимым.',
    'link-list'=>'/admin/page/'.Menu::ID_OBJECT.'/',
  );
  if (Yii::app()->authManager->checkObject(DaDbAuthManager::OPERATION_CREATE, Yii::app()->user->id, Menu::ID_OBJECT)) {
    $arrayItem['link-add'] = '/admin/page/'.Menu::ID_OBJECT.'/-1/';
  }
  $array[] = $arrayItem;
}

if (Yii::app()->hasModule('news') && Yii::app()->authManager->checkObject(DaDbAuthManager::OPERATION_LIST, Yii::app()->user->id, News::ID_OBJECT)) {
  $arrayItem = array(
        'name'=>'<i class="icon-bullhorn"></i> Новости',
        'desc'=>'Модуль для написания периодической информации. Позволяет вести новостную ленту, размещая различные медиа-данные.',
        'link-list'=>'/admin/page/'.News::ID_OBJECT.'/',
  );
  if (Yii::app()->authManager->checkObject(DaDbAuthManager::OPERATION_CREATE, Yii::app()->user->id, News::ID_OBJECT)) {
    $arrayItem['link-add'] = '/admin/page/'.News::ID_OBJECT.'/-1/';
  }
  $array[] = $arrayItem;
}
if (Yii::app()->hasModule('photogallery') && Yii::app()->authManager->checkObject(DaDbAuthManager::OPERATION_LIST, Yii::app()->user->id, Photogallery::ID_OBJECT)) {
  $arrayItem = array(
        'name'=>'<i class="icon-picture"></i> Фотогалереи',
        'desc'=>'Инструмент для массовой загрузки и удобного просмотра фотографий на сайте.',
        'link-list'=>'/admin/page/'.Photogallery::ID_OBJECT.'/',
  );
  if (Yii::app()->authManager->checkObject(DaDbAuthManager::OPERATION_CREATE, Yii::app()->user->id, Photogallery::ID_OBJECT)) {
    $arrayItem['link-add'] = '/admin/page/'.Photogallery::ID_OBJECT.'/-1/';
  }
  $array[] = $arrayItem;
}
if (Yii::app()->hasModule('faq') && Yii::app()->authManager->checkObject(DaDbAuthManager::OPERATION_LIST, Yii::app()->user->id, Question::ID_OBJECT)) {
  $array[] = array(
        'name'=>'<i class="icon-retweet"></i> Вопрос-ответ',
        'desc'=>'Раздел, создержащий форму для приёма вопросов от посетителей сайта с возможностью написания ответов.',
        'link-list'=>'/admin/page/'.Question::ID_OBJECT.'/',
  );
}
if (Yii::app()->hasModule('feedback') && Yii::app()->authManager->checkObject(DaDbAuthManager::OPERATION_LIST, Yii::app()->user->id, Feedback::ID_OBJECT)) {
  $array[] = array(
        'name'=>'<i class="icon-share-alt"></i> Обратная связь',
        'desc'=>'Механизм получения сообщений или заказов от посетителей сайта.',
        'link-list'=>'/admin/page/'.Feedback::ID_OBJECT.'/',
  );
}

$c = count($array);
$html = '';
$block = '';
for ($i = 0; $i < $c; $i++) {
  $element = $array[$i];
  $addButton = (isset($element['link-add']) ? '<a class="btn btn-success" href="'.$element['link-add'].'"><i class="icon-plus icon-white"></i> Добавить</a>' : '');
  $block .= '<div class="span4">
        <div class="caption">
          <h4>'.$element['name'].'</h4>
          <p>'.$element['desc'].'</p>
          <p>'.$addButton.'
              <a class="btn" href="'.$element['link-list'].'"><i class="icon-list"></i> Просмотр</a>
            </p>
        </div>
      </div>';
  if (($i+1) % 3 == 0) {
    $html .= CHtml::tag('div', array('class'=>'row-fluid'), $block);
    $block = '';
  }
}
if ($block != '') $html .= CHtml::tag('div', array('class'=>'row-fluid'), $block);

echo $html.'
  </div><!-- .plugin-list -->
</div>';
