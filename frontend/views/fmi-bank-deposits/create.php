<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBankDeposits */

$this->title = 'Create Fmi Bank Deposits';
$this->params['breadcrumbs'][] = ['label' => 'Fmi Bank Deposits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-bank-deposits-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
