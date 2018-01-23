<?php
namespace console\controllers;

use yii\console\Controller;

use common\components\vkAPI\ApiMethods;
use common\components\Logger\Logger;
use common\models\User;
use backend\models\Events;

/**
 * Class CronController
 * @package console\controllers
 */
class CronController extends Controller
{

    /**
     *
     */
    public function actionEvents()
    {

        Logger::$logname = 'cron_event.log';
        Logger::Log('======Script Start =======');

        //Текущее время
        $current_time = new \DateTime();
        //Модель событий
        $model_events = new Events();
        //Модель пользователей
        $model_users = new User();

        //Ключ доступа ВК
        $app_key = \Yii::$app->params['vk_key'];
        //Пир бота
        $peer = \Yii::$app->params['vk_peer'];
        //Объект АПИ ВК
        $vk_api = new ApiMethods($app_key);

        Logger::Log('Заведены основные данные');
        //Ищем событие подходящее под условие
        $events =
            $model_events->find()
                ->where(['<=', 'date_notification', $current_time->format('Y-m-d H:i:s')])
                ->andWhere(['completed' => 0])
                ->all();

        Logger::Log('Поиск событий завершен');

        //VarDumper::dump($events, 10, true);

        foreach ($events as $event) {

            /**@var $event Events*/

            //Ищем юзера для уведомления
            $user = $model_users->findOne($event->user_id);

            if(!empty($user->vk_id)) {

                //Составляем сообщение
                $date_message = new \DateTime($event->date_notification);
                $message = 'Событие: '.$event->head_event.'
                Сообщение: '.$event->message_event.'
                Когда: '.$date_message->format('d.m.Y H:i:s').'
                ';
                if($this->SendNotif($vk_api, $user->vk_id, $peer, $message))
                {
                    //Говорим что отправили событие
                    $event->completed = true;

                    //Сохраняем в базу
                    $event->save();

                    Logger::Log('Отправлено уведомление пользователю '.$user->username);

                }
                else {
                    Logger::Log('Ошибка отправки уведомления пользователю ' . $user->username);
                }

            }
            else {
                Logger::Log('У пользователя '.$user->username.' нет привязанного ВК профиля, некуда послать уведомление');
            }
        }

        Logger::Log('======Script END =======');

    }


    /**
     * @param ApiMethods $vk_api
     * @param $vk_user_id
     * @param $peer
     * @param $message
     * @return bool
     */
    public function SendNotif($vk_api, $vk_user_id, $peer, $message)
    {
        $vk_api->SendMessageUser($vk_user_id, $message, $peer);

        $response = $vk_api->APIExecute();

        $vk_api->ClearAPI();

        if (preg_match('#\"response\":(.*)#U', $response)) {
            return true;
        }
        else {
            return false;
        }
    }

}