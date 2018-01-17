<?php

use yii\helpers\Html;
use backend\assets\CalculateAsset;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/** @var $balance \backend\models\Balance */
/* @var $searchModel backend\models\FundsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Редактирование текущего баланса';
$this->params['breadcrumbs'][] = ['label' => 'Движение денежных средств', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
CalculateAsset::register($this);
?>
<div class="container margin-top-80">
    <p>
        Данный раздел показывает сумму всех счетов. Эту же сумму вы можете увидеть на вкладке <?=Html::a('Счета', '/bills/index');?>
    </p>
    <p>
        В будущем, раздел планируется расширить, еще на несколько информативных блоков
    </p>
    <div class="row">
        <div class="col-sm-3">
            <div class="current-total-summ">
                <div class="summ-info">Текущий баланс:</div>
                <div class="summ-result">
                    <?php
                        echo $model->total_sum;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
