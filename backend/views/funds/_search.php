<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\FundsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="funds-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'arrival_or_expense') ?>

    <?= $form->field($model, 'category') ?>

    <?= $form->field($model, 'cause') ?>

    <?= $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'cr_time') ?>

    <?php // echo $form->field($model, 'up_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Сброс', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
