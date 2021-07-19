<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PoTransmittal */

$this->title = 'Update Po Transmittal: ' . $model->transmittal_number;
$this->params['breadcrumbs'][] = ['label' => 'Po Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->transmittal_number, 'url' => ['view', 'id' => $model->transmittal_number]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="po-transmittal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
