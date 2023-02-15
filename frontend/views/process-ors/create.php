<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProcessOrs */

$this->title = 'Create Process ' . strtoupper($type);
$this->params['breadcrumbs'][] = ['label' => 'Process Ors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-ors-create">


    <?= $this->render('_form', [

        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'model' => $model,
        'txnType' => 'create',
        'orsTxnAllotments' => [],
        'GetOrsItems' => [],
    ]) ?>

</div>