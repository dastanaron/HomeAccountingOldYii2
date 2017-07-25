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
     * @return bool or void
     */
    public static function hello($user_id)
    {
        if(!self::validatevars()) {
            logger::Log('Ошибка валидации свойств класса \common\components\BotScenario\BotScenario');
            return false;
        }

        $message = '
        Для начала работы с ботом, вам необходимо пройти регистрацию в системе http://frserver.ru
        Далее вам необходимо зарегистрировать этого бота, отправив ему сообщение
         с текстом: регистрация ваш_логин пароль - в системе frserver.ru
        '.PHP_EOL.'
        Список доступных комманд:
        Помощь - выведет это сообщение
        Регистрация - регистрирует пользователя, как описано выше
        Текущий баланс - пришлет вам текущий баланс в системе
        Категории расходов - пришлет список категорий, которые на данный момент используются в программе
        Расход [название категории] - без скобок и название как в присылаемом списке, вернет вам значение расходов по текущей категории
        Доход за период с [дата] по [дата] - доход за период входящий в указанные даты (вводить без скобок в привычном формате).
        Доход - выведет сумму доходов за текущий месяц, с его начала
        ';

        self::$vk_api->SendMessageUser($user_id, $message, self::$peer);

        logger::Log('Отправлен ответ: ' . self::$vk_api->APIExecute());

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

    public static function CurrentBalance($user_id, $balance)
    {
        self::$vk_api->SendMessageUser($user_id, 'Ваш баланс составляет '.$balance.' руб.', self::$peer);

        logger::Log('Отправлена информация о балансе: ' . self::$vk_api->APIExecute());
    }

    public static function UserNotFound($user_id)
    {
        self::$vk_api->SendMessageUser($user_id, 'Ошибка, ваш пользователь не найден', self::$peer);

        logger::Log('Отправлена информация об ошибке пользователя с таким vk_id( '.$user_id.'): ' . self::$vk_api->APIExecute());
    }

    public static function CategoriesList($user_id, $categories)
    {
        self::$vk_api->SendMessageUser($user_id, 'Категории расходов: '.PHP_EOL.$categories, self::$peer);

        logger::Log('Отправлена информация о категориях расходов' . self::$vk_api->APIExecute());
    }

    public static function CategoryConsumption($user_id, $categoryname, $sum)
    {
        self::$vk_api->SendMessageUser($user_id, 'По категории "'.$categoryname.'" сумма расходов за текущий месяц составила: '.$sum.' руб.', self::$peer);

        logger::Log('Отправлена информация о расходе по категории' . self::$vk_api->APIExecute());
    }

    public static function IncomeSum($user_id, $sum)
    {
        self::$vk_api->SendMessageUser($user_id, 'Сумма доходов за текущий месяц составила: '.$sum.' руб.', self::$peer);

        logger::Log('Отправлена информация о доходе' . self::$vk_api->APIExecute());
    }

    public static function IncomeSumPeriod($user_id, $sum, $match)
    {
        self::$vk_api->SendMessageUser($user_id, 'Сумма доходов за период с '.$match[1].' по '.$match[2].' составляет: '.$sum.' руб.', self::$peer);

        logger::Log('Отправлена информация о доходе' . self::$vk_api->APIExecute());
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