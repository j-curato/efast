<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBankDepositTypes */

$this->title = 'Create Fmi Bank Deposit Types';
$this->params['breadcrumbs'][] = ['label' => 'Fmi Bank Deposit Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-bank-deposit-types-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
