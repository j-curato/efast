<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\IrTransmittal */

$this->title = 'Update IR #: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Ir Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ir-transmittal-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'items' => $items,
        'action' => $action,
    ]) ?>

</div>