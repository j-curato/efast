<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class AdminLtePluginAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/bower_components';
    public $css = [
        // 'chart.js/Chart.css',
        // more plugin CSS here
        'Ionicons/css/ionicons.min.css',
        'fullcalendar/dist/fullcalendar.min.css',
        // 'fullcalendar/dist/fullcalendar.print.min.css'

    ];
    public $js = [
        // 'chart.js/Chart.js',
        // 'fullcalendar/dist/fullcalendar.min.js',
        'moment/moment.js'
        // more plugin Js here
    ];
    public $depends = [
        // 'dmstr\adminlte\web\AdminLteAsset',
    ];
}
