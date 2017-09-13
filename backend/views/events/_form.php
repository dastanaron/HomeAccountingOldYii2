<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Events */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="events-form">

    <?php


    if (empty($model->date_notification)) {
        $default_date = new \DateTime();
    }
    else {
        $default_date = new \DateTime($model->date_notification);
    }

    ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->hiddenInput(['value'=> Yii::$app->user->getId()])->label(false) ?>

    <?= $form->field($model, 'head_event')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message_event')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'completed')->checkbox() ?>

    <?= $form->field($model, 'date_notification')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '99.99.9999 99:99:00',
    ])->textInput(['value' => $default_date->format('d.m.Y Hi:s')]) ?>

    <?php

        //Чтобы не перезаписывать время
        if(empty($model->timestamp)) {
            $timestamp = Yii::$app->formatter->asDatetime(time(), 'yyyy-MM-dd HH:mm:ss');
        }
        else {
            $timestamp = $model->timestamp;
        }

    ?>

    <?= $form->field($model, 'timestamp')->hiddenInput(['value'=> $timestamp])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
