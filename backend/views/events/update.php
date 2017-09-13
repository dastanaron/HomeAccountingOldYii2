<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Events */

$this->title = 'Изменить событие: ' . $model->head_event;
$this->params['breadcrumbs'][] = ['label' => 'Напоминания', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->head_event, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="events-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
