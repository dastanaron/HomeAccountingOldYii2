<?php

namespace backend\components\API;

use Yii;
use common\models\User;
use yii\web\HttpException;

class AccessControl
{

    public static function authorization()
    {
        if(!Yii::$app->request->isPost) {
            throw new HttpException(400, 'Bad request type', 400);
        }

        $login = Yii::$app->request->post('login');

        $user = self::findLoginUser($login);

        if(!empty($user)) {

            $password = Yii::$app->request->post('password');

            if(Yii::$app->security->validatePassword($password, $user->password_hash) === true)
            {

                $user->generateAuthKey();
                $user->save();

                return ApiResponse::Response(200, 'Authorization success', ['token' => $user->auth_key]);

            }

            return ApiResponse::Response(400, 'Incorrect password', ['token' => false]);

        }

        return ApiResponse::Response(400, 'User does not exist', ['token' => false]);

    }

    /**
     * @return bool
     */
    public static function CheckToken()
    {
        $user = self::getUser();

        if(!empty($user)) {
            return true;
        }

        return false;

    }

    public static function getUser()
    {
        $token = Yii::$app->request->get('token');
        return self::findUserByToken($token);
    }

    /**
     * @param $login
     * @return array|null|\yii\db\ActiveRecord|User
     */
    protected static function findLoginUser($login)
    {
        return User::find()->where(['username'=>$login, 'status' => 10])->one();
    }

    /**
     * @param $token
     * @return array|null|\yii\db\ActiveRecord|User
     */
    protected static function findUserByToken($token)
    {
        return User::find()->where(['auth_key' => $token, 'status' => 10])->one();
    }




}