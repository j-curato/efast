<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;
use frontend\assets\JqueryPluginAssets;
use yii\helpers\Html;
use yii\bootstrap4\Modal;
use yii\web\JqueryAsset;
use yii\widgets\Pjax;

\hail812\adminlte3\assets\FontAwesomeAsset::register($this);
\hail812\adminlte3\assets\AdminLteAsset::register($this);

$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');

$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

$publishedRes = Yii::$app->assetManager->publish('@vendor/hail812/yii2-adminlte3/src/web/js');
$this->registerJsFile($publishedRes[1] . '/control_sidebar.js', ['depends' => '\hail812\adminlte3\assets\AdminLteAsset']);


if (Yii::$app->user->isGuest) {
    return $this->render(
        'main-login',
        ['content' => $content]
    );
}
AppAsset::register($this);
$this->registerJsFile("@web/js/jquery.min.js", ['position' => $this::POS_HEAD]);
$this->registerJsFile("@web/js/select2.min.js", ['depends' => [JqueryAsset::class]]);
$this->registerCssFile("@web/css/select2.min.css",);
$this->registerJsFile("@web/js/vue.js", ['position' => $this::POS_HEAD]);
// $this->registerJsFile(
//     '@web/frontend/web/js/globalFunctions.js',
//     ['depends' => [\yii\web\JqueryAsset::class]]
// )
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php
    Modal::begin(
        [
            //'header' => '<h2>Create New Region</h2>',
            'id' => 'genericModal',
            'size' => 'modal-md',
            'clientOptions' => ['backdrop' => 'static', 'keyboard' => TRUE, 'class' => 'modal modal-primary '],
            'options' => [
                'tabindex' => false // important for Select2 to work properly
            ],
        ]
    );
    echo "<div class='box box-success' id='modalContent'></div>";
    Modal::end();
    Modal::begin(
        [
            //'header' => '<h2>Create New Region</h2>',
            'id' => 'lrgModal',
            'size' => 'modal-lg',
            'clientOptions' => ['backdrop' => 'static', 'keyboard' => TRUE, 'class' => 'modal modal-primary '],
            'options' => [
                'tabindex' => false // important for Select2 to work properly
            ],
        ]
    );
    echo "<div class='box box-success' id='lrgModalContent'></div>";
    Modal::end();
    ?>
    <style>
        body {
            overflow-x: hidden;
        }

        .select2-container--krajee-bs4 {
            width: 100% !important;
        }

        @media print {

            @page {
                size: auto !important;
            }

        }
    </style>
    <?php $this->head() ?>
</head>

<body class="hold-transition sidebar-mini">

    <?php $this->beginBody();
    ?>


    <div class="wrapper" id='content-container'>
        <!-- Navbar -->
        <?= $this->render('navbar', ['assetDir' => $assetDir]) ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?= $this->render('sidebar', ['assetDir' => $assetDir]) ?>

        <!-- Content Wrapper. Contains page content -->
        <?= $this->render('content', ['content' => $content, 'assetDir' => $assetDir]) ?>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <?= $this->render('control-sidebar') ?>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <?= $this->render('footer') ?>
    </div>



    <?php $this->endBody() ?>

</body>


</html>


<?php $this->endPage() ?>