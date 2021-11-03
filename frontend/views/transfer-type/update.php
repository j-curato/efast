<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TransferType */

$this->title = 'Update Transfer Type: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Transfer Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transfer-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
