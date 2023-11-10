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
        'js/jspdf-autotable.js'
    ];
    public $depends = [
        JqueryAsset::class
    ];
}
