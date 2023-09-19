<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\IrTransmittal */

$this->title = 'Create Ir Transmittal';
$this->params['breadcrumbs'][] = ['label' => 'Ir Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ir-transmittal-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'action' => $action,
    ]) ?>

</div>
