<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConsoTrialBalance */

$this->title = 'Update Conso Trial Balance: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Conso Trial Balances', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="conso-trial-balance-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
