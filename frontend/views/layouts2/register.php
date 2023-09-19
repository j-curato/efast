<?php

use yii\helpers\Html;
use yii\bootstrap4\Modal;
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
        <script src="/dti-afms-2/frontend/web/js/jquery.min.js" type="text/javascript"></script>
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" ></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" type="text/css" rel="stylesheet" /> -->
        <link href="/dti-afms-2/frontend/web/js/select2.min.js" />
        <link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" />
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>



   
    </head>

    <body class="hold-transition skin-blue sidebar-mini ">
        <?php $this->beginBody() ?>

        <div class="wrapper">
        <?php
        echo $content;
        ?>

        </div>

        <?php $this->endBody() ?>
    </body>



    </html>
    <?php $this->endPage() ?>
<?php } ?>