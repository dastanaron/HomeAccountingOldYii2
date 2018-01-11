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
            $model->save();
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
     * Creates a new Funds model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Funds();

        if ($model->load(Yii::$app->request->post())) {

            $model->date = Funds::DateToTimestamp(Yii::$app->request->post()['Funds']['date']);

            $create_date = new \DateTime();

            $model->cr_time = $create_date->format('Y-m-d H:i:s');

            //Считаем общую сумму в зависимости от записи
            if($this->CalculateBalance($model->arrival_or_expense, $model->sum)) {
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else {
                return $this->render('create', [
                    'model' => $model,
                    'error' => 'Ошибка изменения общей суммы',
                ]);
            }
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
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        //Берем сумму до изменения из формы
        $current_sum = $model->sum;

        if ($model->load(Yii::$app->request->post())) {

            $model->date = Funds::DateToTimestamp(Yii::$app->request->post()['Funds']['date']);

            //Делаем флаг записи в базу баланса
            $record_to_balance = true;

            //Сравниваем сумму до записи в модель и после
            if ($model->sum !== $current_sum) {
                //Если изменилась - записываем и возвращаем значение флагу, если ошибка,  будет false и сообщит об ошибке.
                $record_to_balance = $this->CalculateBalance($model->arrival_or_expense, $model->sum);
            }

            //Считаем общую сумму в зависимости от записи
            if($record_to_balance) {

                $model->save();
                return $this->redirect(['view', 'id' => $model->id, 'date' => $model->date]);

            }
            else {
                return $this->render('update', [
                    'model' => $model,
                    'error' => 'Ошибка изменения общей суммы',
                ]);
            }
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
        $this->findModel($id)->delete();

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

    /**
     * @param $dynamic
     * @param $sum
     * @return bool
     */
    protected function CalculateBalance($dynamic,$sum) {

        $balance = $this->getBalanceModel();

        $total_sum = $balance->total_sum;

        if(empty($total_sum)) {
            $total_sum = 0;
        }

        if ($dynamic == '1') {

            $balance->total_sum = $total_sum + $sum;

            $balance->save();

        }
        elseif ($dynamic == '2') {

            $balance->total_sum = $total_sum - $sum;

            $balance->save();

        }
        else {

            return false;

        }

        return true;

    }


}
