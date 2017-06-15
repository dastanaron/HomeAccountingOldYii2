<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Funds */

$this->title = 'Изменить запись: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Движение денежных средств', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="funds-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
