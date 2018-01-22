<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\models\Funds;
use yii\widgets\MaskedInput;
use backend\components\Bills\SelectBills;
use backend\assets\CalculateAsset;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FundsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Движение денежных средств';
$this->params['breadcrumbs'][] = $this->title;

CalculateAsset::register($this);

?>
<div class="funds-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать запись', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Текущий баланс', ['balance'], ['class' => 'btn btn-primary']) ?>
    </p>

    <p>
        Для правильного подсчитывания Итогов, нужно выбрать верные фильтры, например отфильтровать только по приходам или только по расходам,
        иначе он будет показывать сумму всех
    </p>

    <?= GridView::widget([
        'id' => 'arrival_or_expense',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'toggleDataOptions' => ['minCount' => 10],
        'columns' => [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],

            //'id',
            [
                'attribute'=>'arrival_or_expense',
                //'contentOptions' =>['class' => 'table_class','style'=>'display:block;'],
                'content'=>function($data){
                    return Funds::ArrivalOrExpens()[$data->arrival_or_expense];
                },
                'filter' => Funds::ArrivalOrExpens(),
                'width' => '150px',
                'pageSummary' => 'Итоги',
            ],
            [
                'attribute'=>'category',
                //'contentOptions' =>['class' => 'table_class','style'=>'display:block;'],
                'content'=>function($data){
                    return Funds::CategoriesList()[$data->category];
                },
                'filter' => Funds::CategoriesList(),
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['placeholder' => 'Выбрать'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ],
                'width' => '400px',
            ],
            [
                'attribute'=>'bill_id',
                'content'=>function($data){
                    return SelectBills::getBillsByUserArray()[$data->bill_id];
                },
                'filter' => SelectBills::getBillsByUserArray(),
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['placeholder' => 'Выбрать'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ],
            ],

            [
                'attribute' => 'sum',
                'width' => '200px',
                'pageSummary' => true
            ],
            [
                'attribute' => 'cause',
                'hidden' => true,
            ],
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
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy',
                        'todayHighlight' => true
                    ]
                ],
                'width' => '200px',
            ],

            ['class' => 'kartik\grid\ActionColumn'],
            ['class' => '\kartik\grid\CheckboxColumn'],
        ],
        'showPageSummary' => true,
        'toolbar' =>  [
            ['content'=>
               Html::button('<span class="glyphicon glyphicon-transfer"></span>', ['data-toggle' => 'modal', 'data-target' => '#transfer-modal', 'class' => 'btn btn-warning', 'title' => 'перевод со счета на счет']). Html::button('<i class="glyphicon glyphicon-equalizer"></i>', ['id' => 'calc_selected', 'class'=>'btn btn-success', 'title'=>'']).Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['class' => 'btn btn-info', 'title' => 'сбросить фильтр']).Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=> 'новая запись'])
            ],
            '{export}',
            '{toggleData}',
        ],
        'responsive' => true,
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> Расходы/доходы</h3>',
            'before' => '<div id="check_selected"></div>',
            'after' => false,
        ],
        'exportContainer' => ['class' => 'btn-group-md'],
    ]); ?>
    <div class="container">
        <div class="information">

        </div>
    </div>
</div>

<?php

Modal::begin([
    'id' => 'transfer-modal',
    'header' => '<h2>Перевод счета</h2>',
]);?>

    <p>
        Выберите счет с которого списать, и на который перевести
    </p>

<?php
 $form = ActiveForm::begin([
         'action' => 'funds/transfer',
         'method' => 'POST',
 ]);?>

   <div class="form-group">
       <?=Select2::widget([
            'name' => 'billFrom',
            'value' => null,
            'data' => SelectBills::getBillsByUserArray(),
            'options' => ['placeholder' => 'Выберите счет с которого списать'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);?>
   </div>

    <div class="form-group">
        <?=Select2::widget([
            'name' => 'billTo',
            'data' => SelectBills::getBillsByUserArray(),
            'value' => null,
            'options' => [
                'placeholder' => 'Выберите счет на который записать',
            ]
        ]);?>
    </div>

    <div class="form-group">
        <?=Html::textInput('transferSum', '', ['placeholder' => 'Введите сумму', 'class' => 'form-control']);?>
    </div>

    <div class="form-group">
        <?=Html::textInput('transferComment', '', ['placeholder' => 'Комментарий', 'class' => 'form-control']);?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end();

Modal::end();