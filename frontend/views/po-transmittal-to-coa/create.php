<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PoTransmittalToCoa */

$this->title = 'Create Po Transmittal To Coa';
$this->params['breadcrumbs'][] = ['label' => 'Po Transmittal To Coas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="po-transmittal-to-coa-create">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => [],
    ]) ?>

</div>