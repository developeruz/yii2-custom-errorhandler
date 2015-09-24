Кастомная обработка ошибок в приложениях Yii 2.x
============
Модуль позволяет повесить свою обработку на исключения (Exception). 

###Когда это нужно###
Например для отлавливания "опасных" действий пользователя. При возникновении ошибки, вы можете залогировать данные и отследить частоту 
ForbiddenHttpException или NotFoundHttpException для конкретного пользователя или IP. Таким образом своевременно заблокировать пользователя 
и/или сообщить администратору о попытке взлома приложения. Самый простой из способов использования модуля - 
при получении ForbiddenHttpException проверить залогинен ли юзер и если нет - то отправить его на страницу авторизации. 

###Установка:###
```bash
$ php composer.phar require developeruz/yii2-custom-errorhandler "*"
```

###Настройка:###
* В конфиге приложения прописываем action который будет вызываться при возникновении ошибок
```php
 'components' => [
 ...
    'errorHandler' => [
       'errorAction' => 'site/error',
    ],
 ...
```
* Настраиваем action, указанный на предыдущем шаге. В моем примере пишем в site контроллер: 
```php
 public function actions()
    {
        return [
            'error' => [
                'class' => 'developeruz\yii2_custom_errorhandler\ErrorHandler',
                'array_of_exceptions' => [
                    403 => function()
                    {
                        return $this->redirect(Url::to('/site/login'));
                    }, 
                    500 => function()
                    {
                        //send notification to administrator 
                        ...
                        return $this->redirect(Url::to('/site/index'));
                    }, 
                ]
            ]
        ];
    }
```
Теперь при возникновении ForbiddenHttpException пользователя будет перекидывать на страницу авторизации.
 Если обработка ошибки не описана в array_of_exceptions, то она обработается ErrorAction, указанным в параметре $defaultErrorAction 
 (по умолчанию yii\web\ErrorAction)