<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CashFlow */

$this->title = 'Update Cash Flow: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cash Flows', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cash-flow-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
