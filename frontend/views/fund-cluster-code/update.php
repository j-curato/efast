<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FundClusterCode */

$this->title = 'Update Fund Cluster Code: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Fund Cluster Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fund-cluster-code-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
