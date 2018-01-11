<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\Funds;
use yii\helpers\Url;
use backend\assets\CalculateAsset;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FundsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Рассчетные данные';
$this->params['breadcrumbs'][] = ['label' => 'Движение денежных средств', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

CalculateAsset::register($this);
?>
<div class="funds-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container margin-top-80">
        <div class="row">
            <div class="col-sm-2 border-right">
                <div class="current-total-summ">
                    <div class="summ-info">Текущий баланс:</div>
                    <div class="summ-result">
                        <?php
                        if (is_object($balance)) {
                            echo $balance->total_sum;
                        }
                        else {
                            echo $balance;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-10">
                <div class="control-elements row">
                    <?php $form = ActiveForm::begin(/*['id' => 'calculate-form']*/); ?>
                    <div class="element col-sm-3">
                        <div class="control-label">Приход/Расход</div>
                        <div class="control-element">
                            <?=Html::listBox('arrival_or_expense', empty($params['arrival_or_expense']) ? 1 : $params['arrival_or_expense'], Funds::ArrivalOrExpens(),['size' => 1, 'class' => 'form-control']);?>
                        </div>
                    </div>
                    <div class="element col-sm-3">
                        <div class="control-label">Категория</div>
                        <div class="control-element">
                            <?=Html::listBox('category', empty($params['category']) ? null : $params['category'], Funds::СategoriesList(),['size' => 1, 'class' => 'form-control']);?>
                        </div>
                    </div>
                    <div class="element col-sm-3">
                        <div class="control-label">Период: дата от:</div>
                        <div class="control-element">
                            <?= Html::textInput ( 'date_start', empty($params['date_start']) ? null : $params['date_start'], $options = ['class' => 'form-control date_control'] ) ?>
                        </div>
                    </div>
                    <div class="element col-sm-3">
                        <div class="control-label">Период: дата по:</div>
                        <div class="control-element">
                            <?= Html::textInput ( 'date_end', empty($params['date_end']) ? null : $params['date_end'], $options = ['class' => 'form-control date_control'] ) ?>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row buttons-control">
                    <div class="col-sm-3">
                        <?= Html::submitButton('Запросить', ['class' => 'btn btn-success calc-button']) ?>
                    </div>
                    <div class="col-sm-3">
                        <?= Html::a('Сбросить', [Url::current()], ['class' => 'btn btn-primary calc-button']);?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class="calculates-result">
            <?php

            $total_sum = 0;

            if(empty($dataProvider)) {
                echo '<div class="error">Не найдено соответствий по фильтру</div>';
            }
            else {
                ?>
            <table class="calculate-table">
                <tr class="head-table"><td>Дата</td><td>Приход/Расход</td><td>Категория</td><td>Сумма</td><td>Причина</td></tr>
            <?php
                foreach ($dataProvider as $item) {
                    echo '<tr>
                        <td>' . Funds::TimestampToDate($item->date) . '</td>
                        <td>' . Funds::ArrivalOrExpens()[$item->arrival_or_expense] . '</td>
                        <td>' . Funds::СategoriesList()[$item->category] . '</td>
                        <td>' . $item->sum . '</td>
                        <td>' . $item->cause . '</td>
                    </tr>';
                    $total_sum += $item->sum;
                }?>
                <tr><td></td><td></td><td class="bold">Итого:</td><td><?=$total_sum;?></td><td></td></tr>
            </table>
            <?php }?>
        </div>
    </div>
</div>

