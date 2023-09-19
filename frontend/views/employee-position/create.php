<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EmployeePosition */

$this->title = 'Create Employee Position';
$this->params['breadcrumbs'][] = ['label' => 'Employee Positions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-position-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
