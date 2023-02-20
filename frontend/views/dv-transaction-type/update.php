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

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
