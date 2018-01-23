<?php

namespace common\components;

use yii;

/**
 * Class ValidPassword
 * @package common\components
 */
class ValidPassword {

    public static function ValidatePassword($password, $hash)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $hash);
    }

}