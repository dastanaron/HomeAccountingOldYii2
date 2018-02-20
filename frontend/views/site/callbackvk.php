<?php

use common\components\vkAPI\ApiMethods;
use common\components\Logger\Logger;
use yii\web\Response;
use yii\helpers\VarDumper;
use common\models\User;
use common\components\ValidPassword;
use common\components\BotScenario\BotScenario;
use backend\models\Funds;
use backend\models\Balance;


//Yii::$app->response->format = Response::FORMAT_JSON; // На случай json вывода


$request = file_get_contents('php://input');

$app_key = Yii::$app->params['vk_key'];


$peer_id = Yii::$app->params['vk_peer'];

BotScenario::$peer = $peer_id;
BotScenario::$vk_api = new ApiMethods($app_key);


if (!empty($request)) {

    //Ответ боту, чтобы не было дублей
    echo 'ok';

    Logger::log('Принято сообщение от ВК: ' . $request);

    $request_array = json_decode($request, true);

    if(isset($request_array['object'])) {

        //Загружаем модель юзера
        $user_model = new User();
        //модель расчетов
        $funds_model = new Funds();
        //модель баланса
        $balance_model = new Balance();

        if (preg_match('#Помощь#', $request_array['object']['body'])) {

            //Отправляем приветствие
            BotScenario::hello($request_array['object']['user_id']);

        }
        else if (preg_match('#Регистрация#', $request_array['object']['body'])) {

            //При запросе регистрации вычленяем логин и пароль
           preg_match('#Регистрация (.*) (.*)#', $request_array['object']['body'], $match);


            //Ищем юзера с таким логином
           $user = $user_model->findOne(['username'=>'dastanaron']);

           //Проверяем пароль и сузествование юзера
           if(!empty($user) && ValidPassword::ValidatePassword($match[2], $user->password_hash)) {

               //Если нет записи вк id то сохраняем в модель и и отсылаем ответ об успешной регистрации
                if(empty($user->vk_id)){
                    $user->vk_id = $request_array['object']['user_id'];
                    $user->save();
                    BotScenario::succefulRegistration($request_array['object']['user_id']);
                }
                else {
                    //Говорим, что уже зарегистрирован
                    BotScenario::RegistrationLost($request_array['object']['user_id']);
                }
           }
           else {

               //посылаем ошибку, если не найден такой юзер или не соответствует пароль
               BotScenario::RegistrationError($request_array['object']['user_id']);

           }

        }
        //Если получаем запрос текущего баланса
        else if (preg_match('#Текущий баланс#',  $request_array['object']['body'])) {

            //Ищем юзера с таким vk_id
            $user = $user_model->findOne(['vk_id'=>$request_array['object']['user_id']]);

            if(!empty($user)) {

                $balance = $balance_model->findOne(['user_id'=>$user->id]);
            }
            else {
                BotScenario::UserNotFound($request_array['object']['user_id']);
            }

        }
        //Если получаем запрос категорий
        else if (preg_match('#Категории расходов#',  $request_array['object']['body'])) {

            //Ищем юзера с таким vk_id
            $user = $user_model->findOne(['vk_id'=>$request_array['object']['user_id']]);

            if(!empty($user)) {

                $categories = '';

                $sort_categories = Funds::CategoriesList();

                sort($sort_categories);

                //Собираем категории в строку
                foreach($sort_categories as $category) {

                    $categories .= $category . PHP_EOL;

                }

                BotScenario::CategoriesList($request_array['object']['user_id'], $categories);
            }
            else {
                BotScenario::UserNotFound($request_array['object']['user_id']);
            }

        }
        //Расход по категории
        else if (preg_match('#Расход (.*)#', $request_array['object']['body'])) {

            //Ищем юзера с таким vk_id
            $user = $user_model->findOne(['vk_id'=>$request_array['object']['user_id']]);

            if(!empty($user)) {

                //Ищем категорию сначала в выражении потом в массиве
                preg_match('#Расход (.*)#', $request_array['object']['body'], $match);

                $category = array_search($match[1], Funds::CategoriesList());

                //Получаем название категории
                $categoryname = Funds::CategoriesList()[$category];

                //Получаем дату начала месяца
                $date_month_start = new \DateTime(date('Y-m-01'));

                $category_funds = $funds_model->find()->where(['user_id'=>$user->id, 'category'=>$category])->andWhere(['>=', 'date', $date_month_start->getTimestamp()])->all();

                $sum = 0;

                foreach ($category_funds as $consumption){

                    $sum += $consumption->sum;

                }

                BotScenario::CategoryConsumption($request_array['object']['user_id'], $categoryname, $sum);
            }
            else {
                BotScenario::UserNotFound($request_array['object']['user_id']);
            }
        }
        else if (preg_match('#Доход за период с (.*) по (.*)#', $request_array['object']['body'])) {
            //Ищем юзера с таким vk_id
            $user = $user_model->findOne(['vk_id'=>$request_array['object']['user_id']]);

            if(!empty($user)) {

                //вытаскиваем период
                preg_match('#Доход за период с (.*) по (.*)#', $request_array['object']['body'], $match);

                $start_date = new DateTime($match[1]);
                $end_date = new DateTime($match[2]);

                //Получаем дату начала месяца
                $date_month_start = new \DateTime(date('Y-m-01'));

                $incomes = $funds_model->find()
                    ->where(['arrival_or_expense'=>1])
                    ->andWhere(['>=', 'date', $start_date->getTimestamp()])
                    ->andWhere(['<=', 'date', $end_date->getTimestamp()+86400])
                    ->all();

                $sum = 0;

                foreach ($incomes as $income){

                    $sum += $income->summ;

                }

                BotScenario::IncomeSumPeriod($request_array['object']['user_id'], $sum, $match);
            }
            else {
                BotScenario::UserNotFound($request_array['object']['user_id']);
            }
        }
        else if (preg_match('#Доход#', $request_array['object']['body'])) {
            //Ищем юзера с таким vk_id
            $user = $user_model->findOne(['vk_id'=>$request_array['object']['user_id']]);

            if(!empty($user)) {

                //Получаем дату начала месяца
                $date_month_start = new \DateTime(date('Y-m-01'));

                $incomes = $funds_model->find()->where(['arrival_or_expense'=>1])->andWhere(['>=', 'date', $date_month_start->getTimestamp()])->all();

                $sum = 0;

                foreach ($incomes as $income){

                    $sum += $income->summ;

                }

                BotScenario::IncomeSum($request_array['object']['user_id'], $sum);
            }
            else {
                BotScenario::UserNotFound($request_array['object']['user_id']);
            }
        }
        else {
            BotScenario::UndefinedCommand($request_array['object']['user_id']);
        }

    }

}

//echo '07dcd2c0';