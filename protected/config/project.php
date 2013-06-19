<?php

return array(
  'name'=>'Engine macro',
  'language' => 'ru',
  'theme' => 'business',

  // используемые в проекте модули
  'modules' => array(
    'ygin.shop' => array(
      'modelsConfig' => array( //Настройки моделей
        'Product' => array(
          'ImagePreview' => array(
            'formats' => array( //Форматы изображений
              '_list' => array( //Список
                'width' => 100,
              ),
              '_one' => array( //Одиночный
                'width' => 300,
                'height' => 300,
              ),
              '_offer' => array( //В заказе
                'width' => 50,
                'height' => 50,
              ),
            ),
          ),
        ),
        'ProductCategory' => array(
          'ImagePreview' => array(
            'formats' => array(
              '_sm' => array( //В списке
                'width' => 170,
                'height' => 130,
              ),
            ),
          ),
        ),
      ),
    ),
    'ygin.comments' =>array(
      //'class' => 'ygin.modules.comments.CommentsModule',
      //you may override default config for all connecting models
      'defaultModelConfig' => array(
        //only registered users can post comments
        'registeredOnly' => false,
        'useCaptcha' => false,
        //allow comment tree
        'allowSubcommenting' => true,
        //display comments after moderation
        'premoderate' => false,
        //action for postig comment
        'postCommentAction' => 'comments/comment/postComment',
        //super user condition(display comment list in admin view and automoderate comments)
        'isSuperuser'=>'false',
        //order direction for comments
        'orderComments'=>'ASC',
        //отправлять ли уведомление по комментариям
        'sendNoticeAboutComment' => true,
      ),
      'modelClassMap' => array(
        502 => 'News',
      ),
    )
  ),
  
  'plugins' => array(
      'ygin.news' => array(
          'class' => 'ygin.modules.news.NewsPlugin'
          /*'defaultConfig' => array(
              'models'=>array(
                'News'=>'application.MyNews',
              ),
          ),*/
      ),
      'ygin.search' => array('class' => 'ygin.modules.search.SearchPlugin'),
      'ygin.feedback' => array('class' => 'ygin.modules.feedback.FeedbackPlugin'),
      'ygin.photogallery' => array('class' => 'ygin.modules.photogallery.PhotogalleryPlugin'),
      'ygin.shop' => array('class' => 'ygin.modules.shop.ShopPlugin'),
      'ygin.faq' => array('class' => 'ygin.modules.faq.FaqPlugin'),
      'ygin.vote' => array('class' => 'ygin.modules.vote.VotePlugin'),
      'ygin.banners' => array('class' => 'ygin.modules.banners.BannerPlugin'),
      'ygin.specoffers' => array('class' => 'ygin.modules.banners.widgets.specialOffer.SpecialOfferPlugin'),
      'ygin.vitrine' => array('class' => 'ygin.widgets.vitrine.VitrinePlugin'),
      'ygin.cloudim' => array('class' => 'ygin.widgets.cloudim.CloudimPlugin'),
      'ygin.review' => array('class' => 'ygin.modules.review.ReviewPlugin'),
      //'ygin.quiz' => array('class' => 'ygin.modules.quiz.QuizPlugin'),
      'ygin.siteMap' => array('class' => 'ygin.modules.siteMap.SiteMapPlugin'),
      'ygin.cabinet' => array('class' => 'ygin.modules.user.CabinetPlugin'),
  ),

  // проектные компоненты
  'components'=>array(
  ),


);
