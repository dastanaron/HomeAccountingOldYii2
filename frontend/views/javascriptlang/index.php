<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\assets\CodeAsset;

CodeAsset::register($this);

$this->title = 'JavaScript';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="js">
    <h1>Раздел JavaScript кода</h1>
</div>
<div class="row">
    <div class="col-sm-3">
        <div class="block-code">
            <a class="block-code-href" href="/javascriptlang/browsedetect">
                <div class="block-code-header">
                    Определитель браузера
                </div>
                <div class="block-code-desc">
                    Скрипт для определения браузера, конечно не точно, но многие определяет, а также версию и систему
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-3">
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
    <!--<div class="col-sm-3">
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