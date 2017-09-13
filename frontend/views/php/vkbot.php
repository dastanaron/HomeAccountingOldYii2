<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\assets\CodeAsset;

CodeAsset::register($this);

$this->title = 'Пишем бота для ВК';
$this->params['breadcrumbs'][] = ['label' => 'php', 'url' => ['/php']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerMetaTag(['description' => Yii::$app->params['descriptions']['php']['vkbot']]);

?>

<div class="PHP">
    <h1>Пишем бота для ВК</h1>
</div>

<div class="description-code-block">
    <p>
        Как то раз, один из кучи ботов, в названной социальной сети просто атаковал меня своими сообщениями с предложениями.
        Тут я и подумал, а что, хороший способ заманивать клиентов, а также посылать стикеры, сообщения или просто информировать пользователя своей системы.
    </p>
    <p>
        Итак перед нами раскрывается два возможных варианта построения бота.
    </p>
    <ol class="numeric-list">
        <li>Создание фейковой странички, подключение к ней методами АПИ, и общение, как бы от пользователя</li>
        <li>Создание сообщества, настройка callbackAPI и моментальная отдача сообщений</li>
    </ol>

    <p>
        Первый способ хорош тем, что бот может сам начать общение, в отличии от бота в сообществе. Таким образом для созыва
        и рекламитрования своих услуг он подходит хорошо, но есть и минусы:
    </p>
    <ol class="numeric-list">
        <li>Такие боты запрещены, и могут быть забанены по жалобе</li>
        <li>
            Требуется придумывать методы для обработки входящих сообщений, тут я реализовал просто, поставил cron на каждую минуту,
            он запрашивал новые сообщения полученные за минуту и организовывал ответ. Как видите, такой способ отличается от моментального ответа
        </li>
        <li>
            Проблемы с поддержкой авторизации. Это вообще отдельная тема, если захотите, попробуйте разобраться, я решил ее далеко не самым
            разумным способом, поэтому даже обозревать ее не буду.
        </li>
    </ol>

    <p>
        У второго способа минус состоит в том, что он не может написать первым. Человек должен зайти в сообщество, и даже если не вступать в него,
        то хотябы отправить сообщение боту. <br />
        Однако, с обработкой все намного проще. Вам необходимо создать некий API интерфейс, куда бот, будет ложить все уведомления, которые,
        вы ему настроите. Но VK ложит событие в момент его создания, поэтому ответ может поступить незамедлительно
    </p>
    <p class="nb">
        ВНИМАНИЕ!!! При обработке уведомлений через callbackAPI необходимо всегда в ответе выводить "ok"
    </p>
    <pre>
            <code class="php">
/*
**Тут куча кода обработчика
*/
//Если успешно
if($success) {
    echo 'ok';
}
else {
    Logger:log('error.log', 'Ошибка обработки уведомления');
}
            </code>
    </pre>
    <p>
        С сообществом проще и момент авторизации. Вы реггистрируете приложение, привязанное к сообществу, и получаете ключ доступа.
        По этому же ключу, вы можете управлять сообществом, в том числе и и ботом в нем. <br />
        Как я уже и сказал, ответы такого бота могут быть моментальными, все зависит от вашего обработчика и сервера.
        Кроме того, callbackAPI работает с форматом <i>json</i>, что тоже не может не радовать. Примеры есть в настройках этого самого сообщества.
    </p>
    <p>
        Перейдем к коду.<br />
        Для успешного и легкого взаимодействия я написал основные методы для класса. Там еще пока далеко не все, но уже есть подходящие для
        бота, который работает от страницы пользователя и которые подходят для сообщества.<br />
        Всё, что сейчас разработано есть на моем <a href="https://github.com/dastanaron/vk.api.bot" target="_blank">GITHUB</a>, пользуйтесь.
        Там же вы можете посмотреть описание методов и сам код.
    </p>

    <p>
        Я приспособил своего бота для программы домашней бухгалтерии. Он должен принимать сообщение, распарсивать его, и давать ответы
        по доходам и расходам за определенный период. Кроме того, предусмотрена регистрация.
    </p>
    <p>
        Суть регистрации в том, что пользователь, зарегистрированный в моей системе домашней бухгалтерии не привязан к странице вк.
        С помощью регистрации у бота, он привязывает свою страницу ВК к боту. Таким образом, мы избегаем проблемы первого сообщения(боту нужно написать первым),
        а также безошибочно вносим в таблицу БД юзверяего vkID. Всё, с этого момента бот может отвечать на запросы, и при правильном построении
        событийуведомления, уведомлять своих пользователей в вк о тех или иных "вещах".
    </p>

    <p>
        Теперь я покажу вам пример своего callbackAPI, он склеен с Yii2, поэтому много кода будет в соответствии с архитектурой Yii.
    </p>
</div>

<div class="code-examples">
    <div class="block-code-example">
        <div class="code-example-header">Пример 1</div>
        <pre>
            <code class="php">
&lt;?php

use common\components\vkAPI\ApiMethods;
use common\components\Logger\Logger;
use yii\web\Response;
use yii\helpers\VarDumper;
use common\models\User;
use common\components\ValidPassword;
use common\components\BotScenario\BotScenario;
use backend\models\Funds;
use backend\models\CurrentBalance;


//Yii::$app->response->format = Response::FORMAT_JSON; // На случай json вывода

//Получаем, то что нам скидывает ВК
$request = file_get_contents('php://input');

//Заводим ключ доступа
$app_key = Yii::$app->params['vk_key'];

//Получаем пира сообщения
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
        $balance_model = new CurrentBalance();

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
                VarDumper::dump($request_array['object'], 10, true);
                //BotScenario::CurrentBalance($request_array['object']['user_id'], $balance->total_summ);
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

                $sort_categories = Funds::СategoriesList();

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

                $category = array_search($match[1], Funds::СategoriesList());

                //Получаем название категории
                $categoryname = Funds::СategoriesList()[$category];

                //Получаем дату начала месяца
                $date_month_start = new \DateTime(date('Y-m-01'));

                $category_funds = $funds_model->find()->where(['user_id'=>$user->id, 'category'=>$category])->andWhere(['>=', 'date', $date_month_start->getTimestamp()])->all();

                $sum = 0;

                foreach ($category_funds as $consumption){

                    $sum += $consumption->summ;

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
            </code>
        </pre>
    </div>
</div>
<p>
    Для выполнения сценарных действий, я создал класс со статическими методами <b>BotScenario</b><br />
    Данный класс позволяет облегчить код в обработчике callbackAPI. А также помогает избежать дублирования схожих участков.
    Я не знаю как сделать обработку без таких множественных условий. Поэтому реализовал так, если у кого есть идеи, поделитесь, буду очень рад.
</p>
<p>
    Данная программа создана только для уведомлений пользователей по запросу, чтобы не было необходимости заходить в программу, чтобы посмотреть свой
    текущий баланс или другие цифры, связанные с доходами или расходами. Надеюсь это вам поможет в ваших наработках. Спасибо!
</p>

<div class="source-or-author">
    © Dastanaron <?=date('Y');?>
    <div class="created-date">Дата создания статьи: 13.09.2017</div>
</div>