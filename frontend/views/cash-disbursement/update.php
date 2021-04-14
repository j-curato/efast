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

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_new', [
        'model' => $model,
    ]) ?>

</div>
