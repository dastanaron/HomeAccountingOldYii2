<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\assets\CodeAsset;

CodeAsset::register($this);



$this->title = 'PHP';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="php">
    <h1>Раздел PHP кода</h1>
</div>
<div class="row">
    <div class="col-sm-3">
        <div class="block-code">
            <a class="block-code-href" href="/php/vkbot">
                <div class="block-code-header">
                    Пишем бота для ВК
                </div>
                <div class="block-code-desc">
                    Появилась у меня задумка написать бота для социальной сети Вконтакте. Вот что я придумал
                </div>
            </a>
        </div>
    </div>
    <!--<div class="col-sm-3">
        <div class="block-code">
            <a class="block-code-href" href="/javascriptlang/getrequestjavascript">
                <div class="block-code-header">
                    JavaScript с GET параметрами
                </div>
                <div class="block-code-desc">
                    Удобно при создании библиотек, а также скриптов для сторонних ресурсов
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="block-code">
            <a class="block-code-href" href="">
                <div class="block-code-header">
                    Название скрипта
                </div>
                <div class="block-code-desc">
                    Описание скрипта
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="block-code">
            <a class="block-code-href" href="">
                <div class="block-code-header">
                    Название скрипта
                </div>
                <div class="block-code-desc">
                    Описание скрипта
                </div>
            </a>
        </div>
    </div>!-->
</div>