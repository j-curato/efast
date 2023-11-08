<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CashDeposits */

$this->title = 'Update Cash Deposits: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cash Deposits', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cash-deposits-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
