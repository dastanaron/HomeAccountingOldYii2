<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\models\Funds;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FundsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Движение денежных средств';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="funds-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать запись', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Рассчеты', ['calculates'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Текущий баланс', ['balance'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

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
            'sum',
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

            ['class' => 'kartik\grid\ActionColumn'],
        ],
        'toolbar' =>  [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=> 'новая запись'])
            ],
            '{export}',
        ],
        'responsive' => true,
        'floatHeader' => true,
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> Расходы/доходы</h3>',
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Сбросить фильтр', ['index'], ['class' => 'btn btn-info']),
            'footer'=>false
        ],
        'exportContainer' => ['class' => 'btn-group-md'],
    ]); ?>
    <div class="container">
        <div class="information">

        </div>
    </div>
</div>
