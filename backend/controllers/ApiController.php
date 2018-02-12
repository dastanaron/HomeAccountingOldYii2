<?php

namespace backend\controllers;

use backend\components\API\AccessControl;
use backend\components\API\ApiResponse;
use backend\components\Calculators\FundsCalculator;
use backend\models\Funds;
use Yii;
use common\models\User;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\Request;


class ApiController extends Controller
{

    public function actionIndex()
    {
        return ['status' => 200, 'message'=> 'API platform is ok.'];
    }

    public function actionTest()
    {
        return Yii::$app->controller;
    }

    public function actionBalance()
    {
        return FundsCalculator::CalculateBalance(AccessControl::getUser());
    }

    public function actionLogin()
    {
        return AccessControl::authorization();
    }

    public function actionTokenError()
    {
        return ApiResponse::Response(403, 'Access denied');
    }

    public function beforeAction($action)
    {
        if($action->id !== 'login') {
            if(AccessControl::CheckToken() !== true) {
                $this->redirect('/api/token-error');
            }
        }
        return parent::beforeAction($action);
    }

}