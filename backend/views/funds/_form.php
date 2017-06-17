<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Funds;

/* @var $this yii\web\View */
/* @var $model backend\models\Funds */
/* @var $form yii\widgets\ActiveForm */

$date_default = date('d.m.Y');

if(!empty($model->date)) {
    $date_default = Funds::TimestampToDate($model->date);
}

?>

<div class="funds-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'arrival_or_expense')->listBox(Funds::ArrivalOrExpens(),['size' => 1]) ?>

    <?= $form->field($model, 'category')->listBox(Funds::СategoriesList(),['size' => 1]) ?>

    <?= $form->field($model, 'summ')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cause')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '99.99.9999',
    ])->textInput(['value' => $date_default]) ?>

    <?php
    /*
    $form->field($model, 'cr_time')->textInput()

    $form->field($model, 'up_time')->textInput()

    */
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
