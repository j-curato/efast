<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBankDepositTypes */

$this->title = 'Update Fmi Bank Deposit Types: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Bank Deposit Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fmi-bank-deposit-types-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
