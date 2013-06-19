<?php

class DefaultController extends Controller {
  
  protected $urlAlias = "review";

  public function actionIndex() {
    $model = BaseActiveRecord::newModel('Review');
    $modelClass = get_class($model);
    if (isset($_POST['ajax'])) {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }

    if (isset($_POST[$modelClass])) {
      $model->attributes = $_POST[$modelClass];
      $model->visible = (int)($this->module->moderate);
      $model->onAfterSave = array($this, 'sendMessage');
      if ($model->save()) {
        Yii::app()->user->setFlash('reviewAdd', 'Спасибо, ваш отзыв отправлен.');
        $this->refresh();
      }
    }
    
    $criteria = new CDbCriteria();
    $criteria->condition = 'visible = 1';
    $criteria->order = 'create_date DESC';

    $dataProvider = new CActiveDataProvider($modelClass, array(
            'criteria' => $criteria,
            'pagination' => array(
               'pageSize' => $this->module->pageSize,
            ),
    ));

    $this->render('/index', array(
      'dataProvider' => $dataProvider,
      'model' => $model,
    ));
  }
  
  public function sendMessage(CEvent $event) {
    /**
     * @var Review $model
     */
    $model = $event->sender;
    $message = '';
    $default = true;
    if ($this->module->hasEventHandler('onFormMessage')) {
      $formMsgEvent = new CEvent($model);
      $this->module->onFormMessage($formMsgEvent);
      $message = HArray::val($formMsgEvent->params, 'message');
      //если сообщение пришло пустое
      //то формируем ссобщение по-умолчанию
      $default = empty($message);
    }
    if ($default) {
      $tmpl = "Отправлен новый отзыв.\n".
        "время: {time}\n".
        "имя: {name}\n".
        "контакты: {email}\n".
        "Текст сообщения:\n{msg}\n\n".
        "---\n".
        "Данное сообщение отправлено автоматически, отвечать на него не нужно.";
      
      $message = strtr($tmpl, array(
        '{time}'  => date('d.m.Y H:i', $model->create_date),
        '{name}'  => $model->name,
        '{email}' => $model->contact,
        '{msg}'   => $model->review,
      ));
    }
    Yii::app()->notifier->addNewEvent($this->module->idEventType, $message);
  }

}