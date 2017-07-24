<?php

use common\components\vkAPI\ApiMethods;
use common\components\Logger\Logger;
use yii\web\Response;
use yii\helpers\VarDumper;
use common\models\User;
use common\components\ValidPassword;
use common\components\BotScenario\BotScenario;


//Yii::$app->response->format = Response::FORMAT_JSON; // На случай json вывода


$request = file_get_contents('php://input');

$app_key = 'd2070e93f4d52563de92cb829b63873d901ca7bbe14877f159fcf3f12b7bf6a1e4309803acb2a03281414';


$peer_id = '150773608';

BotScenario::$peer = $peer_id;
BotScenario::$vk_api = new ApiMethods($app_key);


if (!empty($request)) {

    Logger::log('Принято сообщение от ВК: ' . $request);

    $request_array = json_decode($request, true);

    if(isset($request_array['object'])) {

        if (preg_match('#привет#', $request_array['object']['body'])) {

            BotScenario::hello($request_array['object']['user_id']);

        }
        else if (preg_match('#регистрация#', $request_array['object']['body'])) {

           preg_match('#регистрация (.*) (.*)#', $request_array['object']['body'], $match);

           $user_model = new User();

           $user = $user_model->findOne(['username'=>'dastanaron']);

           if(ValidPassword::ValidatePassword($match[2], $user->password_hash)) {

                if(empty($user->vk_id)){
                    $user->vk_id = $request_array['object']['user_id'];
                    $user->save();
                    BotScenario::succefulRegistration($request_array['object']['user_id']);
                }
                else {
                    BotScenario::RegistrationLost($request_array['object']['user_id']);
                }
           }
           else {

               BotScenario::RegistrationError($user_id);

           }





        }

    }


}

//echo '07dcd2c0';