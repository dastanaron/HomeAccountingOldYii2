<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\VarDumper;
use common\components\vkAPI\AuthorizeVK;
use common\components\vkAPI\ApiMethods;

if (Yii::$app->user->getId() == 1) {
    $this->title = 'О сайте';
    $this->params['breadcrumbs'][] = $this->title;

    VarDumper::dump(Yii::$app->request->get(), 10, true);

    /*$vk_autorize = new AuthorizeVK(6123728, 150773608, 'blank.html');

    echo $vk_autorize->getUrl();

    $vk_autorize->BuildRequestAccessTocken('I1bdlUpvFMSaLo869oly','a7cc1eefd66206a5a6');
    echo '<br />';
    echo $vk_autorize->getUrl();


    echo $vk_autorize->exec();*/

    $app_key = 'd2070e93f4d52563de92cb829b63873d901ca7bbe14877f159fcf3f12b7bf6a1e4309803acb2a03281414';

    $peer_id = '150773608';

    $vk_api = new ApiMethods($app_key);

    $vk_api->SendMessageUser(219981829, 'Добро пожаловать в сообщество', $peer_id);

    VarDumper::dump($vk_api->APIExecute(), 10, true);

}