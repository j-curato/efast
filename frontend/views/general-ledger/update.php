<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GeneralLedger */

$this->title = 'Update General Ledger: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'General Ledgers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="general-ledger-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
