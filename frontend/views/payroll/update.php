<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Payroll */

$this->title = 'Update Payroll: ' . $model->payroll_number;
$this->params['breadcrumbs'][] = ['label' => 'Payrolls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->payroll_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payroll-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>