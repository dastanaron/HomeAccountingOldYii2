<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Funds;
use kartik\select2\Select2;
use backend\components\Bills\SelectBills;

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

    <?= $form->field($model, 'arrival_or_expense')->widget(Select2::className(), [
        'data' => Funds::ArrivalOrExpens(),
        'options' => ['placeholder' => 'Выберите', 'disabled' => !$model->isNewRecord],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'bill_id')->widget(Select2::className(), [
            'data' => SelectBills::getBillsByUserArray(),
            'options' => ['placeholder' => 'Выберите счет', 'required' => true,],
            'pluginOptions' => [
                'allowClear' => true,
            ],
    ])->label('Счёт (обязательно к заполнению)');?>

    <?= $form->field($model, 'category')->listBox(Funds::CategoriesList(),['size' => 1]) ?>

    <?= $form->field($model, 'sum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cause')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '99.99.9999',
    ])->textInput(['value' => $date_default]) ?>
    <?= $form->field($model, 'user_id')->hiddenInput(['value'=> Yii::$app->user->getId()])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
