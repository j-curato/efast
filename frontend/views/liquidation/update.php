<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidation */

$this->title = 'Update Liquidation: ' . $model->dv_number;
$this->params['breadcrumbs'][] = ['label' => 'Liquidations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dv_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="liquidation-update">


    <?= $this->render('_form_new', [
        'model' => $model,
        'update_type' => $update_type,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider
    ]) ?>

</div>