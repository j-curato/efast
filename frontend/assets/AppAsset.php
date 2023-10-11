<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        // '/web/css/styles.css',
        'css/select2.min.css'
    ];
    public $js = [
        // 'js/jquery.min.js',
        'app.js',
        // 'maskmoney/dist/jquery.maskMoney.min.js',
        // 'js/select2.min.js',
        // 'js/scripts.js',
        // 'manifest.webmanifest',
        // 'js/vue.js',
        'js/axios.min.js'



    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
