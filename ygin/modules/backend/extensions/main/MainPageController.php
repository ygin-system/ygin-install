<?php

class MainPageController extends DaBackendController implements IBackendExtension {

  public function actionIndex() {

    $this->render('backend.extensions.main.view', array(
    ));

  }
  
  // реализация события класса как компонента
  public function registerEvent($category, $obj) {
    if ($category == BackendModule::CATEGORY_BACKEND_WINDOW) {
      $obj->attachEventHandler(BackendModule::EVENT_ON_BEFORE_TOP_MENU, array($this, 'onBeforeTopMenu'));
    }
  }
  public function onBeforeTopMenu($event) {
    $sender = $event->sender;
    
    array_unshift($sender->items, array(
      'label' => 'Главная',
      'url' => Yii::app()->createUrl('mainPage'),
      'active' => false,
    ));
  }
}
