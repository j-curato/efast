<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TrialBalance */

$this->title = 'Update Trial Balance: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Trial Balances', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="trial-balance-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
