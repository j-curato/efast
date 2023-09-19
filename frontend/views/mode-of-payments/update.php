<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ModeOfPayments */

$this->title = 'Update Mode Of Payments: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Mode Of Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mode-of-payments-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
