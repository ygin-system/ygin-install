<?php

class FeedbackController extends Controller {
  
  public function actionIndex() {
    $model = BaseActiveRecord::newModel('Feedback');
    $modelClass = get_class($model);
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'feedbackForm') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
    if (isset($_POST[$modelClass])) {
      $model->attributes=$_POST[$modelClass];
      $model->onAfterSave = array($this, 'sendMessage'); //Регистрируем обработчик события
      if ($model->save()) {
        Yii::app()->user->setFlash('feedback-success', 'Спасибо за обращение. Ваше сообщение успешно отправлено.');
      } else {
        // вообще сюда попадать в штатных ситуациях не должны
        // только если кул хацкер резвится
        Yii::app()->user->setFlash('feedback-message', CHtml::errorSummary($model, '<p>Не удалось отправить форму</p>'));
      }
    }
    $this->redirect(Yii::app()->user->returnUrl);
  }
  
  public function sendMessage(CEvent $event) {
    /**
     * @var Feedback $model
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
      $tmpl = "Отправлено новое сообщение через форму обратной связи:\n".
              "время: {time}\n".
              "имя: {name}\n".
              "телефон: {phone}\n".
              "e-mail: {email}\n".
              "Текст сообщения:\n{msg}\n\n".
              "---\n".
              "Данное сообщение отправлено автоматически, отвечать на него не нужно.";
    
      $message = strtr($tmpl, array(
        '{time}'  => date('d.m.Y H:i', $model->date),
        '{name}'  => $model->fio,
        '{phone}' => $model->phone,
        '{email}' => $model->mail,
        '{msg}'   => $model->message,
      ));
    }
    Yii::app()->notifier->addNewEvent(Yii::app()->getModule('feedback')->idEventType, $message);
  }
  
}