<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBankDeposits */

$this->title = 'Update Fmi Bank Deposits: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Bank Deposits', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fmi-bank-deposits-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
