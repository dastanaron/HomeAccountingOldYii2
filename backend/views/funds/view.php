<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Funds;

/* @var $this yii\web\View */
/* @var $model backend\models\Funds */

$this->title = $model->cause;
$this->params['breadcrumbs'][] = ['label' => 'Движение денежных средств', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="funds-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить эемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'Приход или расход',
                'value' => $model->ArrivalOrExpens()[$model->arrival_or_expense],
            ],
            [
                'label' => 'Категория',
                'value' => $model->CategoriesList()[$model->category],
            ],
            'sum',
            'cause',
            [
                'label' => 'Дата',
                'value' => $model->TimestampToDate($model->date),
            ],
            'up_time',
            'cr_time',
        ],
    ]) ?>

</div>
