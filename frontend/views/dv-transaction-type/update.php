<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DvTransactionType */

$this->title = 'Update Dv Transaction Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Dv Transaction Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dv-transaction-type-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
