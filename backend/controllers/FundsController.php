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
use backend\components\Bills\SelectBills;

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
                        'actions' => ['logout', 'index', 'view', 'create', 'update', 'delete', 'calculates', 'balance', 'transfer'],
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
     * @return string
     * @throws \Exception
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
     * @throws \Exception
     */
    public function actionBalance() {

        FundsCalculator::CalculateBalance();

        $model = $this->getBalanceModel();

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
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionTransfer()
    {
        //Функция переброса со счета на счет

        $billFrom = Yii::$app->request->post('billFrom');
        $billTo = Yii::$app->request->post('billTo');
        $comment = Yii::$app->request->post('transferComment');
        $sum = Yii::$app->request->post('transferSum');

        /**
         * Логика следующая:
         * Берем счет с которого снимаем, пересчитываем этот счет, без указания расхода. ЛОжим на новый счет, с указанимем
         * дохода и комментарием, указывая, с какого счета, и пересчитываем этот счет
         */

        $billFromModel = $this->findBill($billFrom);

        FundsCalculator::calculateBill($billFromModel, $sum, 2);

        $fundsModel = new Funds();

        $fundsModel->bill_id = $billTo;
        $fundsModel->user_id = Yii::$app->user->identity->getId();
        $fundsModel->arrival_or_expense = 1;
        $fundsModel->date = time();
        $fundsModel->cr_time = Yii::$app->formatter->asDatetime(time(), 'php: Y-m-d H:i:s');
        $fundsModel->category = 13;
        $fundsModel->sum = $sum;
        $fundsModel->cause = 'Перевод со счета: ' . SelectBills::getBillsByUserArray()[$billFrom] . ' [' . $comment . ']';

        $billToModel = $this->findBill($billTo);

        FundsCalculator::calculateBill($billToModel, $sum, 1);

        if ($fundsModel->save()) {
            return $this->redirect('/bills/index');
        }
        else {
            Yii::$app->session->setFlash('warning', 'Ошибка записи прихода');
            return $this->redirect('index');
        }

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
     * @return null|static|Balance
     * @throws \Exception
     */
    protected function getBalanceModel() {

        $balance = Balance::findOne(['user_id' =>  Yii::$app->user->getId()]);

        if(!empty($balance)) {

            return $balance;

        }
        else {
            throw new \Exception('not created balance row');
        }

    }




}
