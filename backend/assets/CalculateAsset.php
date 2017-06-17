<?php
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class CalculateAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/jquery-ui.min.css',
        '//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css',
        'css/site.css',
        'css/calculates.css',
    ];
    public $js = [
        'js/jquery-ui.min.js',
        'js/datepicker_ru.js',
        'js/calculates.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
