<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
  <meta name="viewport" content="width=device-width">
  <meta http-equiv="content-language" content="ru" > <?php // TODO - в будущем генетить автоматом ?>
<?php
  //Регистрируем файлы скриптов в <head>
  if (YII_DEBUG) {
    Yii::app()->assetManager->publish(YII_PATH.'/web/js/source', false, -1, true);
  }
  Yii::app()->clientScript->registerCoreScript('jquery');
  Yii::app()->clientScript->registerCoreScript('bootstrap'); 
  $this->registerJsFile('modernizr-2.6.1-respond-1.1.0.min.js', 'ygin.assets.js');

  Yii::app()->clientScript->registerScriptFile('/themes/business/js/js.js', CClientScript::POS_HEAD);
  
  Yii::app()->clientScript->registerScript('setScroll', "setAnchor();", CClientScript::POS_READY);
  Yii::app()->clientScript->registerScript('menu.init', "$('.dropdown-toggle').dropdown();", CClientScript::POS_READY);

  $ass = Yii::getPathOfAlias('application.assets.bootstrap.img').DIRECTORY_SEPARATOR;
  Yii::app()->clientScript->addDependResource('bootstrap.min.css', array(
    $ass.'glyphicons-halflings.png' => '../img/',
    $ass.'glyphicons-halflings-white.png' => '../img/',
    $ass.'glyphicons-halflings-red.png' => '../img/',
    $ass.'glyphicons-halflings-green.png' => '../img/',
  ));
  
  Yii::app()->clientScript->registerCssFile('/themes/business/css/content.css');
  Yii::app()->clientScript->registerCssFile('/themes/business/css/page.css');
  $nAss = Yii::getPathOfAlias('ygin.assets.gfx').DIRECTORY_SEPARATOR;
  Yii::app()->clientScript->addDependResource('page.css', array(
    $nAss.'loading_s.gif' => '../../../ygin/assets/gfx/',
  ));
?>
  <title><?php echo CHtml::encode($this->getPageTitle()); ?></title>
</head>
<body>
  <div id="wrap" class="container">
    <div id="head" class="row">
<?php if (Yii::app()->request->url == "/"){ ?>
      <div class="logo span2"><img border="0" alt="Название компании - На главную" src="/themes/business/gfx/l0000000.gif"></div>
<?php } else { ?>
      <a href="/" title="Главная страница" class="logo span2"><img src="/themes/business/gfx/l0000000.gif" alt="Логотип компании"></a>
<?php }?>
      <div class="cname span7">Ваша компания
        <p>«Слоган или вид деятельности»</p>
      </div>
      <div class="tright span3">
        <div class="numbers">
          <p>+7 (495) <strong>123-45-67</strong></p>
          <p><strong>123-45-68</strong></p>
        </div>
      </div>
    </div>
    <div class="b-menu-top navbar">
      <div class="nav-collapse">
<?php

if (Yii::app()->hasModule('search')) {
  $this->widget('SearchWidget');
}
$this->widget('MenuWidget', array(
  'rootItem' => Yii::app()->menu->all,
  'htmlOptions' => array('class' => 'nav nav-pills'), // корневой ul
  'submenuHtmlOptions' => array('class' => 'dropdown-menu'), // все ul кроме корневого
  'activeCssClass' => 'active', // активный li
  'activateParents' => 'true', // добавлять активность не только для конечного раздела, но и для всех родителей
  //'labelTemplate' => '{label}', // шаблон для подписи
  'labelDropDownTemplate' => '{label} <b class="caret"></b>', // шаблон для подписи разделов, которых есть потомки
  //'linkOptions' => array(), // атрибуты для ссылок
  'linkDropDownOptions' => array('data-target' => '#', 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'), // атрибуты для ссылок для разделов, у которых есть потомки
  'linkDropDownOptionsSecondLevel' => array('data-target' => '#', 'data-toggle' => 'dropdown'), // атрибуты для ссылок для разделов, у которых есть потомки
  //'itemOptions' => array(), // атрибуты для li
  'itemDropDownOptions' => array('class' => 'dropdown'),  // атрибуты для li разделов, у которых есть потомки
  'itemDropDownOptionsSecondLevel' => array('class' => 'dropdown-submenu'),
//  'itemDropDownOptionsThirdLevel' => array('class' => ''),
  'maxChildLevel' => 2,
  'encodeLabel' => false,
));

?>
      </div>
    </div>

<?php $this->widget('BlockWidget', array("place" => SiteModule::PLACE_TOP)); ?>

<?php // + Главный блок ?>
    <div id="main">

      <div id="container" class="row">
<?php

$column1 = 0;
$column2 = 12;
$column3 = 0;

if (Yii::app()->menu->current != null) {
  $column1 = 3;
  $column2 = 6;
  $column3 = 3;
  
  if (Yii::app()->menu->current->getCountModule(SiteModule::PLACE_LEFT) == 0) {$column1 = 0; $column3 = 4;}
  if (Yii::app()->menu->current->getCountModule(SiteModule::PLACE_RIGHT) == 0) {$column3 = 0; $column1 = $column1*4/3;}
  $column2 = 12 - $column1 - $column3;
}

?>
        <?php if ($column1 > 0): // левая колонка ?>
        <div id="sidebarLeft" class="span<?php echo $column1; ?>">
          <?php $this->widget('BlockWidget', array("place" => SiteModule::PLACE_LEFT)); ?>
        </div>
        <?php endif ?>
        
        <div id="content" class="span<?php echo $column2; ?>">
          <div class="page-header">
            <h1><?php echo $this->caption; ?></h1>
          </div>
          
          <?php if ($this->useBreadcrumbs && isset($this->breadcrumbs)): // Цепочка навигации ?>
          <?php $this->widget('BreadcrumbsWidget', array(
            'homeLink' => array('Главная' => Yii::app()->homeUrl),
            'links' => $this->breadcrumbs,
          )); ?>
          <?php endif ?>

          <div class="cContent">
            <?php echo $content; ?>
          </div>
          <?php $this->widget('BlockWidget', array("place" => SiteModule::PLACE_BOTTOM)); ?>
        </div>

        <?php if ($column3 > 0): // левая колонка ?>
        <div id="sidebarRight" class="span<?php echo $column3; ?>">
          <?php $this->widget('BlockWidget', array("place" => SiteModule::PLACE_RIGHT)); ?>
        </div>
        <?php endif ?>

      </div>
<?php //Тут возможно какие-нить модули снизу ?>
      <div class="clr"></div>
    </div>
<?php // - Главный блок ?>

<div id="back-top"><span>↑</span></div>
    
  </div>


  <div id="footer" class="container">
    <div class="row">
      <div class="span4 logo">
        <img alt="Логотип компании" src="/themes/business/gfx/l0000000.gif">
      </div>
      <div class="span6">
        <?php $this->widget('BlockWidget', array("place" => SiteModule::PLACE_FOOTER)); ?>
      </div>
    </div>
  </div>

</body>
</html>