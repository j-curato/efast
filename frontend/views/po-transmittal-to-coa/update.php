<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PoTransmittalToCoa */

$this->title = 'Update Po Transmittal To Coa: ' . $model->transmittal_number;
$this->params['breadcrumbs'][] = ['label' => 'Po Transmittal To Coas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->transmittal_number, 'url' => ['view', 'id' => $model->transmittal_number]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="po-transmittal-to-coa-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
