<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\assets\CodeAsset;

CodeAsset::register($this);

$this->title = 'GET параметры в JavaScript';
$this->params['breadcrumbs'][] = ['label' => 'JavaScript', 'url' => ['/javascriptlang']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="js">
    <h1>GET параметры в JavaScript</h1>
</div>

<div class="description-code-block">
    <p>
        Данный скрипт может хорошо пригодиться, кода ваш сервер работает для создания виджетов илил другой вспомогательной
        информации для других сайтов.
    </p>
    <p>
        Здесь особо нечего объяснять, кто искал данную информацию, наверняка, уже знает зачем ему это нужно. Но все же,
        если вы случайно забрели сюда, то объясню.
    </p>
    <p>
        Скрипты сторонних ресурсов, должны выдаваться с ключами, например access tocken или иным способом. Иначе говоря это то,
        каким образом, ваш сервер должен отличать работу одного ресурса от другого. Возможно, вы будете использовать его
        для сбора статистики с сайта своего партнера, что-то нужно будет записывать в базу, так вот, как понять, что
        это именно тот партнер? А? Вот вам и ответ, присвойте ему ID или access tocken и на его основе, получите
        признак своего партнера.
    </p>
</div>

<div class="code-examples">
    <div class="block-code-example">
        <div class="code-example-header">Пример 1</div>
        <pre>
            <code class="js">
var search = window.location.search.substr(1),
keys = {};

search.split('&').forEach(function(item) {
item = item.split('=');
keys[item[0]] = item[1];
});

console.log(keys);
//Автор ruslan_mart
            </code>
        </pre>
    </div>
    <div class="block-code-example">
        <div class="code-example-header">Пример 1</div>
        <pre>
            <code class="js">
function $_GET(key) {
var s = window.location.search;
s = s.match(new RegExp(key + '=([^&=]+)'));
return s ? s[1] : false;
}

alert( $_GET('test') );

//Автор ruslan_mart
            </code>
        </pre>
    </div>
</div>

<div class="source-or-author">
    https://javascript.ru/forum/misc/44530-poluchit-get-parametry-s-url.html
</div>