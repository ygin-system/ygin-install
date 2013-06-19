<?php

class DefaultController extends Controller {
  
  protected $urlAlias = "faq";

  public function actionIndex() {
    $model = BaseActiveRecord::newModel('Question');

    if (isset($_POST['ajax'])) {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }

    if (isset($_POST['Question'])) {
      $model->attributes = $_POST['Question'];
      $model->visible = ($this->module->moderate) ? 0 : 1;
      $model->onAfterSave = array($this, 'sendMessage');
      if ($model->save()) {
        Yii::app()->user->setFlash('questionAdd', 'Спасибо, ваш вопрос отправлен.');
        $this->refresh();
      }
    }
    
    $criteria = new CDbCriteria();
    $criteria->condition = 'visible = 1';
    $criteria->order = 'ask_date DESC';

    $dataProvider = new CActiveDataProvider('Question', array(
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
    $msg = "В разделе Вопрос-ответ отправлено новое сообщение.\n".
           "время: {time}\n".
           "имя: {name}\n".
           "e-mail: {email}\n".
           "Текст сообщения:\n{msg}\n\n".
           "---\n".
           "Данное сообщение отправлено автоматически, отвечать на него не нужно.";
    /**
     * @var $model Question
     */
    $model = $event->sender;
    
    Yii::app()->notifier->addNewEvent(
      Yii::app()->getModule('faq')->idEventType,
      strtr($msg, array(
        '{time}'  => date('d.m.Y H:i', $model->ask_date),
        '{name}'  => $model->name,
        '{email}' => $model->email,
        '{msg}'   => $model->question,
      ))
    );
  }

}