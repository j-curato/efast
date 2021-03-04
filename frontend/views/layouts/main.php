<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login') {
    /**
     * Do not use this code in your template. Remove it. 
     * Instead, use the code  $this->layout = '//main-login'; in your controller.
     */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">

    <head>
        <meta charset="<?= Yii::$app->charset ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <style>
            /* .modal-wide {
                width: 90%;
            } */
        </style>

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
        ?>
    </head>

    <body class="hold-transition skin-blue sidebar-mini ">
        <?php $this->beginBody() ?>
        <!-- <div class="page-loader-wrapper">
            <div class="loader">
                <div class="preloader">
                    <div class="spinner-layer pl-red">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                </div>
                <p>Please wait...</p>
            </div>
        </div> -->

        <!-- <div class="col-sm-2">
            <div id="bars1">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
            <h5>bars1</h5>
        </div> -->
        <div class="wrapper">

            <?= $this->render(
                'header.php',
                ['directoryAsset' => $directoryAsset]
            ) ?>

            <?= $this->render(
                'left.php',
                ['directoryAsset' => $directoryAsset]
            )
            ?>

            <?= $this->render(
                'content.php',
                ['content' => $content, 'directoryAsset' => $directoryAsset]
            ) ?>

        </div>

        <?php $this->endBody() ?>
    </body>

    <?php
    $js = <<<JS
        // setTimeout(function () {
        //     $('.page-loader-wrapper').fadeOut();
        // }, 300);
    JS;
    $this->registerJs($js, \yii\web\View::POS_READY);

    ?>

    </html>
    <?php $this->endPage() ?>
<?php } ?>