<?php

namespace developeruz\yii2_custom_errorhandler;

use Yii;
use yii\base\Action;
use yii\helpers\Url;

class ErrorHandler extends Action
{
    public $array_of_exceptions = [];
    public $defaultErrorAction = 'yii\web\ErrorAction';

    public function run()
    {
        $currentExceptionCode = Yii::$app->getErrorHandler()->exception->statusCode;

        if(array_key_exists($currentExceptionCode, $this->array_of_exceptions))
        {
            return $this->array_of_exceptions[$currentExceptionCode]();
        }
        $defaultAction = new $this->defaultErrorAction('error', $this->controller);
        return $defaultAction->run();
    }
}