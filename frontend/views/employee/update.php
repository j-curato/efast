<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = 'Update Employee: ' . $model->employee_id;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->employee_id, 'url' => ['view', 'id' => $model->employee_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="employee-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
