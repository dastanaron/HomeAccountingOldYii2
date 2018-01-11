<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Личная программа для разаработок';
?>
<div class="container">

    <div class="row">
        <div class="col-sm-4">
            <a href="/funds" class="btn btn-info center-block">Движение денежных средств</a>
        </div>

        <div class="col-sm-4">
            <a href="/events" class="btn btn-info center-block">Напоминания</a>
        </div>

        <div class="col-sm-4">
            <a href="/" class="btn btn-info center-block">в разработке</a>
        </div>
    </div>

    <div class="exit margin-top-80">
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