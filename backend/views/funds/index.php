<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\models\Funds;
use yii\widgets\MaskedInput;
use backend\components\Bills\SelectBills;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FundsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Движение денежных средств';
$this->params['breadcrumbs'][] = $this->title;

$tools = '<div class="tools">'.
        '<div class="tool form-group">'.
            Html::a('<i class="glyphicon glyphicon-repeat"></i> Сбросить фильтр', ['index'], ['class' => 'btn btn-info']) .
        '</div>'.
        '<div class="tool form-group">'.
            Html::button('Посчитать выбранные', ['id' => 'calc_selected', 'class'=>'btn btn-success']).
            '<div id="check_selected"></div>'.
        "</div>".
    "</div>";

?>
<div class="funds-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать запись', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Рассчеты (deprecated)', ['calculates'], ['class' => 'btn btn-danger']) ?>
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
                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=> 'новая запись'])
            ],
            '{export}',
            '{toggleData}',
        ],
        'responsive' => true,
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> Расходы/доходы</h3>',
            'after'=> $tools,
            'footer'=>false
        ],
        'exportContainer' => ['class' => 'btn-group-md'],
    ]); ?>
    <div class="container">
        <div class="information">

        </div>
    </div>
</div>
<?php

$js = <<<JS
$(document).ready(function () {
    $('button#calc_selected').click(function () {
        var keys = $('#arrival_or_expense').yiiGridView('getSelectedRows');
        
        if(keys == '' || keys == []) {
            $('#check_selected').html('');
            alert('ничего не выбрано');
            return ;
        }
        console.log(keys);
        
        var sum = 0;
        
        $.each(keys, function (index, value) {
            sum += parseInt($('#arrival_or_expense').find('table').find('tr[data-key='+value+']').children('td[data-col-seq=4]').html());
        });
        
        console.log(sum);
        $('#check_selected').html('Сумма выбранных: <span class="calc_value">' + sum + '</span>');
    });
});


JS;

$this->registerJs($js, \yii\web\View::POS_END);