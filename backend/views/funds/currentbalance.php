<?php

use yii\helpers\Html;
use backend\assets\CalculateAsset;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FundsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Редактирование текущего баланса';
$this->params['breadcrumbs'][] = ['label' => 'Движение денежных средств', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
CalculateAsset::register($this);
?>
<div class="container margin-top-80">
    <div class="row">
        <div class="col-sm-3">
            <div class="current-total-summ">
                <div class="summ-info">Текущий баланс:</div>
                <div class="summ-result">
                    <?php
                    if (is_object($model)) {
                        echo $model->total_summ;
                    }
                    else {
                        echo $model;
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-9">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'total_summ')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'user_id')->hiddenInput(['value'=> Yii::$app->user->getId()])->label(false) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
