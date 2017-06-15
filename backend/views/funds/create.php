<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Funds */

$this->title = 'Создание записи';
$this->params['breadcrumbs'][] = ['label' => 'Движение денежных средств', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="funds-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
