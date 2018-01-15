<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BillsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Счета';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bills-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Новый счет', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn',],
            [
                'attribute' => 'name',
                'pageSummary' => 'Итоги',
            ],
            [
                'attribute' => 'sum',
                'pageSummary' => true
            ],
            'deadline',
            'comment',
            'created_at',
            'updated_at',

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', '/bills/view?id='.$model['id']);
                    },
                    'update' => function ($url,$model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            '/bills/update?id='.$model['id']);
                    },
                    'delete' => function ($url,$model,$key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>',
                            '/bills/delete?id='.$model['id'],
                            [
                                'data' => [
                                    'confirm' => 'Вы действительно хотите удалить элемент?',
                                    'method' => 'post',
                                ],
                            ]
                        );
                    },
                ],
            ],
        ],
        'showPageSummary' => true,
        'toolbar' =>  [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=> 'Новый счет'])
            ],
            '{export}',
            '{toggleData}',
        ],
        'responsive' => true,
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> Счета</h3>',
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Сбросить фильтр', ['index'], ['class' => 'btn btn-info']),
            'footer'=>false
        ],
        'exportContainer' => ['class' => 'btn-group-md'],
    ]); ?>
</div>
