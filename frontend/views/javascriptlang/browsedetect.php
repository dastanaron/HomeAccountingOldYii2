<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\assets\CodeAsset;

CodeAsset::register($this);

$this->title = 'Определитель браузера';
$this->params['breadcrumbs'][] = ['label' => 'JavaScript', 'url' => ['/javascriptlang']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="js">
    <h1>Определитель браузераt</h1>
</div>

<div class="description-code-block">
    <p>
        Здесь довольно сложно разобраться, но код рабочий, хоть и далеко не точный. Принцип работы кода основан на создании
        объекта <b>BrowserDetect</b>, в котором различные методы, отыскивают элементы в объекте <b>navigator</b>, который,
        в свою очередь встроен в большинство браузеров. На основе полученных данных, он выводит результат.
        Однако, те кто разбираются во встроенных объектах браузера, знает, что эти параметры сами по себе не очень точны.
    </p>
</div>

<div class="code-examples">
    <div class="block-code-example">
        <div class="code-example-header">Пример 1</div>
        <pre>
            <code class="js">
var BrowserDetect = {
    init: function () {
        this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
        this.version = this.searchVersion(navigator.userAgent) || this.searchVersion(navigator.appVersion) || "an unknown version";
        this.OS = this.searchString(this.dataOS) || "an unknown OS";
    },
    searchString: function (data) {
        for (var i=0;i&lt;data.length;i++)    {
            var dataString = data[i].string;
            var dataProp = data[i].prop;
            this.versionSearchString = data[i].versionSearch || data[i].identity;
            if (dataString) {
                if (dataString.indexOf(data[i].subString) != -1)
                    return data[i].identity;
            }
            else if (dataProp)
                return data[i].identity;
        }
    },
    searchVersion: function (dataString) {
        var index = dataString.indexOf(this.versionSearchString);
        if (index == -1) return;
        return parseFloat(dataString.substring(index+this.versionSearchString.length+1));
    },
    dataBrowser: [
        {
            string: navigator.userAgent,
            subString: "Chrome",
            identity: "Chrome"
        },
        {     string: navigator.userAgent,
            subString: "OmniWeb",
            versionSearch: "OmniWeb/",
            identity: "OmniWeb"
        },
        {
            string: navigator.vendor,
            subString: "Apple",
            identity: "Safari",
            versionSearch: "Version"
        },
        {
            prop: window.opera,
            identity: "Opera",
            versionSearch: "Version"
        },
        {
            string: navigator.vendor,
            subString: "iCab",
            identity: "iCab"
        },
        {
            string: navigator.vendor,
            subString: "KDE",
            identity: "Konqueror"
        },
        {
            string: navigator.userAgent,
            subString: "Firefox",
            identity: "Firefox"
        },
        {
            string: navigator.vendor,
            subString: "Camino",
            identity: "Camino"
        },
        {
            /* For Newer Netscapes (6+) */
            string: navigator.userAgent,
            subString: "Netscape",
            identity: "Netscape"
        },
        {
            string: navigator.userAgent,
            subString: "MSIE",
            identity: "Internet Explorer",
            versionSearch: "MSIE"
        },
        {
            string: navigator.userAgent,
            subString: "Gecko",
            identity: "Mozilla",
            versionSearch: "rv"
        },
        {
            /* For Older Netscapes (4-) */
            string: navigator.userAgent,
            subString: "Mozilla",
            identity: "Netscape",
            versionSearch: "Mozilla"
        }
    ],
    dataOS : [
        {
            string: navigator.platform,
            subString: "Win",
            identity: "Windows"
        },
        {
            string: navigator.platform,
            subString: "Mac",
            identity: "Mac"
        },
        {
            string: navigator.userAgent,
            subString: "iPhone",
            identity: "iPhone/iPod"
        },
        {
            string: navigator.platform,
            subString: "Linux",
            identity: "Linux"
        }
    ]

};
BrowserDetect.init();

console.log('Браузер: ' + BrowserDetect.browser + ' Версия: ' + BrowserDetect.version + ' Платформа: ' + BrowserDetect.OS);
            </code>
        </pre>
    </div>
</div>

<div class="source-or-author">
    https://yraaa.ru/scripts/opredelenie-brauzera-javascript
</div>