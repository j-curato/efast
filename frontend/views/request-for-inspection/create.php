<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RequestForInspection */

$this->title = 'Create Request For Inspection';
$this->params['breadcrumbs'][] = ['label' => 'Request For Inspections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-for-inspection-create">


    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>