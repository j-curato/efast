<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CashAdjustment */

$this->title = 'Update Cash Adjustment: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cash Adjustments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cash-adjustment-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
