<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidation */

$this->title = 'Update Liquidation: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Liquidations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="liquidation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_new', [
        'model' => $model,
        'update_type'=>$update_type
    ]) ?>

</div>
