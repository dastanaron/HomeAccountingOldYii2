<?php

namespace backend\controllers;


use Yii;
use backend\models\Funds;
use backend\models\FundsSearch;
use backend\models\FundsFilter;
use backend\models\Balance;
use yii\db\Exception;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Bills;
use backend\components\Calculators\FundsCalculator;
/**
 * FundsController implements the CRUD actions for Funds model.
 */
class FundsController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'view', 'create', 'update', 'delete', 'calculates', 'balance'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Funds models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FundsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'balance' => $this->getBalanceModel(),
        ]);
    }

    /**
     * @return string
     */
    public function actionCalculates()
    {

        if(Yii::$app->user->identity->getId() !== 1)
        {
            Yii::$app->session->setFlash('error', "Извините, раздел временно не доступен");
            return $this->redirect('/funds');
        }

        $FilterModel = new FundsFilter();
        $dataProvider = $FilterModel->search(Yii::$app->request->post());

        return $this->render('calculates', [
            'FilterModel' =>  $FilterModel,
            'dataProvider' => $dataProvider,
            'balance' => $this->getBalanceModel(),
            'params' => Yii::$app->request->post(),
        ]);
    }

    /**
     * @return string
     */
    public function actionBalance() {

        $model = $this->getBalanceModel();

        if (!is_object($model)){
            $model = new Balance();
        }

        if ($model->load(Yii::$app->request->post())) {
            FundsCalculator::CalculateBalance();
        }

        return $this->render('balance', [
            'model' => $model,
        ]);

    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionCreate()
    {
        $model = new Funds();

        if ($model->load(Yii::$app->request->post())) {

            $model->date = Funds::DateToTimestamp(Yii::$app->request->post()['Funds']['date']);

            $create_date = new \DateTime();

            $model->cr_time = $create_date->format('Y-m-d H:i:s');

            $bill = $this->findBill($model->bill_id);

            FundsCalculator::calculateBill($bill, $model->sum, $model->arrival_or_expense);

            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        //Берем сумму до изменения из формы
        $beforeSum = $model->sum;

        if ($model->load(Yii::$app->request->post())) {

            $model->date = Funds::DateToTimestamp(Yii::$app->request->post()['Funds']['date']);


            $bill = $this->findBill($model->bill_id);
            FundsCalculator::calculateBill($bill, $model->sum, $model->arrival_or_expense, true, $beforeSum);

            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $beforeSum = $model->sum;

        $bill = $this->findBill($model->bill_id);

        FundsCalculator::calculateBill($bill, 0, $model->arrival_or_expense, true, $beforeSum);

        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Funds model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Funds
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Funds::findOne(['id'=>$id, 'user_id'=>Yii::$app->user->getId()])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $id
     * @return Bills
     * @throws NotFoundHttpException
     */
    protected function findBill($id)
    {
        if (($model = Bills::findOne(['id'=>$id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return null|string|static|Balance
     */
    protected function getBalanceModel() {

        $balance = Balance::findOne(['user_id' =>  Yii::$app->user->getId()]);

        if(!empty($balance)) {

            return $balance;

        }
        else {
            return 'Нет данных';
        }

    }




}
