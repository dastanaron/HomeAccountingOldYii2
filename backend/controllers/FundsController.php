<?php

namespace backend\controllers;

use Yii;
use backend\models\Funds;
use backend\models\FundsSearch;
use backend\models\FundsFilter;
use backend\models\CurrentBalance;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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
                        'actions' => ['logout', 'index', 'view', 'create', 'update', 'delete', 'calculates', 'currentbalance'],
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
            'balance' => $this->getCurrentBalanceModel(),
        ]);
    }

    public function actionCalculates()
    {
        $FilterModel = new FundsFilter();
        $dataProvider = $FilterModel->search(Yii::$app->request->post());

        return $this->render('calculates', [
            'FilterModel' =>  $FilterModel,
            'dataProvider' => $dataProvider,
            'balance' => $this->getCurrentBalanceModel(),
            'params' => Yii::$app->request->post(),
        ]);
    }

    public function actionCurrentbalance() {

        $model = $this->getCurrentBalanceModel();

        if (!is_object($model)){
            $model = new CurrentBalance();
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->save();
        }

        return $this->render('currentbalance', [
            'model' => $model,
        ]);

    }

    /**
     * Displays a single Funds model.
     * @param integer $id
     * @return mixed
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

            $model->date = Funds::DateToTimestamp($model->date);

            $create_date = new \DateTime();

            $model->cr_time = $create_date->format('Y-m-d H:i:s');

            //Считаем общую сумму в зависимости от записи
            if($this->CalculateCurrentBalance($model->arrival_or_expense, $model->summ)) {
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
     * Updates an existing Funds model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        //Берем сумму до изменения из формы
        $current_summ = $model->summ;

        if ($model->load(Yii::$app->request->post())) {

            $model->date = Funds::DateToTimestamp(Yii::$app->request->post()['Funds']['date']);

            //Делаем флаг записи в базу баланса
            $record_to_balance = true;

            //Сравниваем сумму до записи в модель и после
            if ($model->summ !== $current_summ) {
                //Если изменилась - записываем и возвращаем значение флагу, если ошибка,  будет false и сообщит об ошибке.
                $record_to_balance = $this->CalculateCurrentBalance($model->arrival_or_expense, $model->summ);
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
     * Deletes an existing Funds model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
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
     * @return Funds the loaded model
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
     * @return static object
     */
    protected function getCurrentBalanceModel() {

        $balance = CurrentBalance::findOne(['user_id' =>  Yii::$app->user->getId()]);

        if(!empty($balance)) {

            return $balance;

        }
        else {
            return 'Нет данных';
        }

    }

    protected function CalculateCurrentBalance($dynamic,$summ) {

        $balance = $this->getCurrentBalanceModel();

        $total_summ = $balance->total_summ;

        if(empty($total_summ)) {
            $total_summ = 0;
        }

        if ($dynamic == '1') {

            $balance->total_summ = $total_summ + $summ;

            $balance->save();

        }
        elseif ($dynamic == '2') {

            $balance->total_summ = $total_summ - $summ;

            $balance->save();

        }
        else {

            return false;

        }

        return true;

    }


}
