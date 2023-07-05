<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PoTransmittal */

$this->title = 'Create Po Transmittal';
$this->params['breadcrumbs'][] = ['label' => 'Po Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="po-transmittal-create">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => [],
    ]) ?>

</div>
