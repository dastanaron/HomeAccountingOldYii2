<?php

namespace backend\components\API;

use Yii;

class ApiResponse
{

    public static function Response($status=200, $message='ok', $other = array())
    {

        Yii::$app->response->setStatusCode($status);

        $response = ['status' => $status, 'message' => $message];

        if(!empty($other)) {

            foreach($other as $key => $value) {
                $response[$key] = $value;
            }

        }

        return $response;

    }

}