<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CashRecieved */

$this->title = 'Update Cash Recieved: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cash Recieveds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cash-recieved-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
