<?php

namespace common\components;

use yii;

class ValidPassword {

    public static function ValidatePassword($password, $hash)
    {
        //$hash = Yii::$app->getSecurity()->generatePasswordHash($password);

        if (Yii::$app->getSecurity()->validatePassword($password, $hash)) {

            return true;

        } else {

            return false;

        }
    }

}