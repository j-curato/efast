<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidation */

$this->title = 'Create Liquidation';
$this->params['breadcrumbs'][] = ['label' => 'Liquidations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="liquidation-create">


    <?= $this->render('_form_new', [
        'model' => $model,
        'update_type' => $update_type,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,

        'certified_by' => [],
        'approved_by' => [],
    ]) ?>

</div>