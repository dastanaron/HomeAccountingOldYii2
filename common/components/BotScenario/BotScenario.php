<?php

namespace common\components\BotScenario;

use common\components\vkAPI\ApiMethods;
use common\components\Logger\Logger;

class BotScenario {

    public static $peer;
    public static $vk_api;

    /**
     * Принимает id пользователя вк, для отправки ему сообщения
     * @param $user_id
     * @return bool
     */
    public static function hello($user_id)
    {
        if(!self::validatevars()) {
            logger::Log('Ошибка валидации свойств класса \common\components\BotScenario\BotScenario');
            return false;
        }

        self::$vk_api->SendMessageUser($user_id, 'И вам доброго времени суток.
             Я бот напоминалка, поэтому мне бесполезно задавать вопросы.
              Но прислав мне сообщение, вы разрешили мне напоминать вам о событиях в органайзере.
               Для регистрации, отправьте мне сообщение следующего содержания: регистрация (ваше имя пользователя в системе) (пароль) * все без скобок', self::$peer);

        logger::Log('Отправлен ответ: ' . self::$vk_api->APIExecute());

        return true;

    }

    public static function succefulRegistration($user_id)
    {
        self::$vk_api->SendMessageUser($user_id, 'Вы успешно зарегистрированы в системе', self::$peer);

        logger::Log('Отправлен ответ об успешной регистрации: ' . self::$vk_api->APIExecute());

    }

    public static function RegistrationLost($user_id)
    {
        self::$vk_api->SendMessageUser($user_id, 'Вы уже зарегистрированы в системе', self::$peer);

        logger::Log('Отправлен ответ о том, что пользователь уже был зарегестрирован: ' . self::$vk_api->APIExecute());
    }

    public static function RegistrationError($user_id)
    {
        self::$vk_api->SendMessageUser($user_id, 'Ошибка, пользователя с таким логином и паролем нет', self::$peer);

        logger::Log('Отправлен ответ об ошибке авторизации и регистрации: ' . self::$vk_api->APIExecute());
    }

    /**
     * Служит для проверки свойств класса
     * @return bool
     */
    private static function validatevars()
    {
        if (!empty(self::$peer) && self::$vk_api instanceof ApiMethods) {
            return true;
        }
        else {
            return false;
        }

    }
}