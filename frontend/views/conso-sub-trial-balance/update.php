<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConsoSubTrialBalance */

$this->title = 'Update Conso Sub Trial Balance: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Conso Sub Trial Balances', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="conso-sub-trial-balance-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
