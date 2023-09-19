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

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
