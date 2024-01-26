<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Main frontend application asset bundle.
 */
class JqueryPluginAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // 'css/site.css',
        // // '/web/css/styles.css',
        'css/select2.min.css'
    ];
    public $js = [
        'js/jspdf.js',
        'js/jspdf-autotable.js',
        'frontend/web/js/globalFunctions.js',
        "js/maskMoney.js",
        'js/v-money.min.js',
        'js/sheetJs.min.js'

    ];
    public $depends = [
        JqueryAsset::class
    ];
}
