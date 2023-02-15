<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProcessOrs */

$this->title = 'Update ' . strtoupper($model->type) . ' No. : ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Process Ors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="process-ors-update">


    <?= $this->render('_form', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'model' => $model,
        'txnType' => 'update',
        'orsTxnAllotments' => $orsTxnAllotments,
        'GetOrsItems' => $GetOrsItems,
    ]) ?>

</div>