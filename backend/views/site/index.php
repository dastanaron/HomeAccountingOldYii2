<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Личная программа для разаработок';
?>
<div class="container">

    <div class="row">
        <div class="col-sm-6">
            <a href="/funds" class="btn btn-info center-block">Движение денежных средств</a>
        </div>
        <div class="col-sm-6">
            <?php
            echo Html::beginForm(['/site/logout'], 'post');
            echo Html::submitButton(
                'Выход (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-primary center-block']
            );
            echo Html::endForm();
            ?>
        </div>
    </div>

</div>
