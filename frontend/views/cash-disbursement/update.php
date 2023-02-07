<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CashDisbursement */

$this->title = 'Update Cash Disbursement: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cash Disbursements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cash-disbursement-update">


    <?= $this->render('_form', [
        'model' => $model,
        'dv_details' => $dv_details,
    ]) ?>

</div>