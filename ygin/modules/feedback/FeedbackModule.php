<?php

class FeedbackModule extends DaWebModuleAbstract {
  
  const ROUTE_FEEDBACK = 'feedback/feedback';
  const ROUTE_FEEDBACK_CAPTCHA = '/feedback/feedback/captcha';
  
  public $idEventType;
  
  protected $_urlRules = array(
    'feedback' => self::ROUTE_FEEDBACK,
  );
  /**
   * Событие, возникающее при формировании сообщения.
   * В обработчике данного события можно переопределить сообщение,
   * которое будет отправленно на почту.
   * $event->sender - модель класса Feedback,
   * $event->params['message'] - сообщение, которое будет отправленно на почту.
   * Если пустое, то будет сформировано сообщение по умолчанию.
   * Пример:
   * 'ygin.feedback' => array(
   *   'onFormMessage' => create_function('$event', '
   *     $model = $event->sender;
   *     $event->params["message"] = "Текст сообщения";
   *   '),
   * ),
   * @param CEvent $event
   */
  public function onFormMessage(CEvent $event) {
    $this->raiseEvent('onFormMessage', $event);
  }
  public function init() {
    $this->setImport(array(
      'feedback.models.*',
      'feedback.components.*',
      'feedback.views.*',
    ));
  }

}
