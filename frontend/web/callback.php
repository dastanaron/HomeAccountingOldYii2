<?php


require(__DIR__ . '/../../common/components/vkAPI/ApiMethods.php');
require(__DIR__ . '/vkcallback/request.php');
require(__DIR__ . '/vkcallback/logger.php');

use common\components\vkAPI\ApiMethods;

$request = file_get_contents('php://input');

$app_key = 'd2070e93f4d52563de92cb829b63873d901ca7bbe14877f159fcf3f12b7bf6a1e4309803acb2a03281414';

$peer_id = '150773608';

if (!empty($request)) {

    Logger::log('Принято сообщение от ВК: ' . $request);

    $vk_api = new ApiMethods($app_key);

    $request_array = json_decode($request, true);

    if(preg_match('#привет#',$request_array['object']['body'])) {

        $vk_api->SendMessageUser($request_array['object']['user_id'], 'И вам доброго времени суток. Я бот напоминалка, поэтому мне бесполезно задавать вопросы. Но прислав мне сообщение, вы разрешили мне напоминать вам о событиях в органайзере.', $peer_id);

        logger::Log('Отправлен ответ: ' . $vk_api->APIExecute());

    }

}
echo 'ok';


function dump($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}