<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Events */

$this->title = 'Создание напоминания';
$this->params['breadcrumbs'][] = ['label' => 'Напоминания', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
