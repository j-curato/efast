<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DvAucs */

$this->title = 'Create Routing Slip';
$this->params['breadcrumbs'][] = ['label' => 'Routing Slips', 'url' => ['tracking-index']];
$this->params['breadcrumbs'][] = $this->title;
$reporting_period = $model->reporting_period;
$nature_of_transaction = $model->nature_of_transaction_id;
$book = $model->book_id;
$payee = $model->payee_id;
$transaction_type = $model->transaction_type;
$mrd_classification = $model->mrd_classification_id;
$particular = $model->particular;
?>
<div class="dv-aucs-create">


    <?= $this->render('_routing_form', [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
        'model' => $model,
        'items' => []

    ]) ?>

</div>