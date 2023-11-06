<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PoTransaction */

$this->title = 'Update  Transaction: ' . $model->tracking_number;
$this->params['breadcrumbs'][] = ['label' => 'Po Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tracking_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="po-transaction-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
