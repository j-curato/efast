<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RemittancePayee */

$this->title = 'Update Remittance Payee: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Remittance Payees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="remittance-payee-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
