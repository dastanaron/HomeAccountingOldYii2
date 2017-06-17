<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Funds;
use yii\helpers\VarDumper;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FundsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Движение денежных средств';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="funds-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать запись', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Рассчеты', ['calculates'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php
        //VarDumper::dump($searchModel, 10, true);
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute'=>'arrival_or_expense',
                //'contentOptions' =>['class' => 'table_class','style'=>'display:block;'],
                'content'=>function($data){
                    return Funds::ArrivalOrExpens()[$data->arrival_or_expense];
                },
                'filter' => Funds::ArrivalOrExpens(),
            ],
            [
                'attribute'=>'category',
                //'contentOptions' =>['class' => 'table_class','style'=>'display:block;'],
                'content'=>function($data){
                    return Funds::СategoriesList()[$data->category];
                },
                'filter' => Funds::СategoriesList(),
            ],
            'summ',
            'cause',
            [
                'attribute'=>'date',
                //'contentOptions' =>['class' => 'table_class','style'=>'display:block;'],
                'content'=>function($data){
                    return Funds::TimestampToDate($data->date);
                },
                'filter' => MaskedInput::widget([
                    'name' => 'FundsSearch[date]',
                    'mask' => '99.99.9999',
                    ]),
            ],
            // 'cr_time',
            // 'up_time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <div class="container">
        <div class="information">
            <div classs="total_summ">
               <!--Текущий баланс: <?= $balance->total_summ; ?> руб.!-->
            </div>
        </div>
    </div>
</div>
